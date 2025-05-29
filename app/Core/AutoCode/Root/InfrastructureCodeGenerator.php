<?php

namespace App\Core\AutoCode\Root;

use App\Core\AutoCode\Root\CodeConventionConverter;
use App\Core\AutoCode\Root\DataDefinition;

class InfrastructureCodeGenerator
{
    private string $templatePath;
    private string $connectionClassFqn;
    private string $connectionClassName;
    private CodeConventionConverter $converter;

    public function __construct(string $templatePath, string $connectionClassFqn, string $connectionClassName, CodeConventionConverter $converter)
    {
        $this->templatePath = $templatePath;
        $this->connectionClassFqn = $connectionClassFqn;
        $this->connectionClassName = $connectionClassName;
        $this->converter = $converter;
    }

    public function generate(array $definitions, string $entityName, string $infrastructureNamespace, string $domainEntitiesNamespace, string $domainRepositoriesNamespace): string
    {
        $template = file_get_contents($this->templatePath);

        // Pluralización de nombres de tabla (regla específica para 'proveedor' y por defecto)
        // Para que coincida con tu configuración de DB actual, seguimos usando 'proveedors'.
        $singularTableName = $this->converter->toSnakeCase($entityName);
        // Si tu DB se llama 'proveedores' (con 'es'), entonces descomenta la siguiente línea y comenta la subsiguiente.
        // $tableName = ($singularTableName === 'proveedor') ? 'proveedores' : $singularTableName . 's'; 
        // Si tu DB se llama 'proveedors' (con 's'), usa esta línea.
        $tableName = $singularTableName . 's'; // Asumimos que tu DB ahora usa 'proveedors'


        /** @var DataDefinition $idDefinition */
        $idDefinition = $definitions[0]; // Asumimos que el primer campo es el ID
        $idField = $idDefinition->getFieldName(); 
        $idPhpType = $idDefinition->getDataType(); // Tipo PHP para el ID (ej: int)
        $repoVarName = $this->converter->toCamelCase($entityName); // Nombre de la variable de la entidad en el repo (ej: $proveedor)
        $idMethodName = 'get' . ucfirst($this->converter->toCamelCase($idField)); // Nombre del getter para el ID (ej: getIdProveedor)
        $idSetMethodName = 'set' . ucfirst($this->converter->toCamelCase($idField)); // Nombre del setter para el ID (ej: setIdProveedor)

        // --- Construcción de lógica para el método save (INSERT) ---
        $fieldsForInsert = [];
        $placeholdersForInsert = [];
        $bindValuesForInsert = [];

        foreach ($definitions as $index => $definition) {
            $fieldName = $definition->getFieldName();
            $camelFieldName = $this->converter->toCamelCase($fieldName);
            $getMethodName = 'get' . ucfirst($camelFieldName);
            $dataType = $definition->getDataType();

            // Para INSERT: Omitimos el campo ID si es autoincremental (asumiendo que el primer campo es el ID)
            if ($index > 0) { 
                $fieldsForInsert[] = "`{$fieldName}`";
                $placeholdersForInsert[] = ":{$fieldName}";
                
                $bindLine = "\$stmt->bindValue(':" . $fieldName . "', \${$repoVarName}->{$getMethodName}(";
                if ($dataType === 'bool') {
                    $bindLine .= "));"; // Se manejará la conversión a int en el repositorio generado
                } elseif ($definition->isNullable()) {
                     $bindLine .= ") ?? null, \PDO::PARAM_NULL);"; // Si es nullable, añadir la lógica de null
                } else {
                    $bindLine .= "));";
                }
                $bindValuesForInsert[] = str_replace("));", "()));", $bindLine); // Ajuste temporal para el bug del paréntesis doble
            }
        }

        $fieldsForInsertString = implode(', ', $fieldsForInsert);
        $placeholdersForInsertString = implode(', ', $placeholdersForInsert);
        
        // RE-GENERAR bindValuesForInsertString para más robustez (manual)
        $manualBindValuesForInsert = [];
        foreach ($definitions as $index => $definition) {
            if ($index === 0) continue; // Omitir el ID
            $fieldName = $definition->getFieldName();
            $camelFieldName = $this->converter->toCamelCase($fieldName);
            $getMethodName = 'get' . ucfirst($camelFieldName);
            $dataType = $definition->getDataType();
            
            if ($dataType === 'bool') {
                $manualBindValuesForInsert[] = "\$stmt->bindValue(':" . $fieldName . "', (int)\${$repoVarName}->{$getMethodName}(), \PDO::PARAM_INT);";
            } elseif ($definition->isNullable()) {
                $manualBindValuesForInsert[] = "if (\${$repoVarName}->{$getMethodName}() === null) {\n                \$stmt->bindValue(':" . $fieldName . "', null, \PDO::PARAM_NULL);\n            } else {\n                \$stmt->bindValue(':" . $fieldName . "', \${$repoVarName}->{$getMethodName}());\n            }";
            } else {
                $manualBindValuesForInsert[] = "\$stmt->bindValue(':" . $fieldName . "', \${$repoVarName}->{$getMethodName}());";
            }
        }
        $bindValuesForInsertString = implode("\n            ", $manualBindValuesForInsert);


        // --- Construcción de lógica para el método save (UPDATE) ---
        $updateSetParts = [];
        $bindValuesForUpdate = [];
        foreach ($definitions as $index => $definition) {
            // ¡IMPORTANTE! Omitimos el ID para la cláusula SET en UPDATE, solo se usa en el WHERE
            if ($index === 0) continue; 

            $fieldName = $definition->getFieldName();
            $camelFieldName = $this->converter->toCamelCase($fieldName);
            $getMethodName = 'get' . ucfirst($camelFieldName);
            $dataType = $definition->getDataType();
            
            $updateSetParts[] = "`{$fieldName}` = :{$fieldName}";

            // RE-GENERAR bindValuesForUpdate (manual)
            if ($dataType === 'bool') {
                $bindValuesForUpdate[] = "\$stmt->bindValue(':" . $fieldName . "', (int)\${$repoVarName}->{$getMethodName}(), \PDO::PARAM_INT);";
            } elseif ($definition->isNullable()) {
                $bindValuesForUpdate[] = "if (\${$repoVarName}->{$getMethodName}() === null) {\n                \$stmt->bindValue(':" . $fieldName . "', null, \PDO::PARAM_NULL);\n            } else {\n                \$stmt->bindValue(':" . $fieldName . "', \${$repoVarName}->{$getMethodName}());\n            }";
            } else {
                $bindValuesForUpdate[] = "\$stmt->bindValue(':" . $fieldName . "', \${$repoVarName}->{$getMethodName}());";
            }
        }
        $updateSetString = implode(', ', $updateSetParts);
        $bindValuesForUpdateString = implode("\n            ", $bindValuesForUpdate);


        $saveMethodBody = <<<PHP
        if (\${$repoVarName}->{$idMethodName}() === null) {
            // Insertar
            \$sql = "INSERT INTO {$tableName} ({$fieldsForInsertString}) VALUES ({$placeholdersForInsertString})";
            \$stmt = \$this->connection->prepare(\$sql);
            {$bindValuesForInsertString}
            \$stmt->execute();
            \${$repoVarName}->{$idSetMethodName}((int)\$this->connection->lastInsertId());
        } else {
            // Actualizar
            \$sql = "UPDATE {$tableName} SET {$updateSetString} WHERE {$idField} = :{$idField}";
            \$stmt = \$this->connection->prepare(\$sql);
            {$bindValuesForUpdateString}
            \$stmt->bindValue(':{$idField}', \${$repoVarName}->{$idMethodName}(), \PDO::PARAM_INT); // Enlazar el ID para la cláusula WHERE como INT
            \$stmt->execute();
        }
        return \${$repoVarName};
PHP;

        // --- Construcción de lógica para el método delete ---
        $deleteMethodBody = <<<PHP
        \$stmt = \$this->connection->prepare("DELETE FROM {$tableName} WHERE {$idField} = :id");
        \$idToDelete = \${$repoVarName}->{$idMethodName}();
        \$stmt->bindParam(':id', \$idToDelete, \PDO::PARAM_INT);
        \$stmt->execute();
PHP;

        $replacements = [
            '{{NAMESPACE}}' => $infrastructureNamespace,
            '{{ENTITY_NAME}}' => $entityName,
            '{{ENTITY_NAMESPACE}}' => $domainEntitiesNamespace,
            '{{REPOSITORY_INTERFACE_NAMESPACE}}' => $domainRepositoriesNamespace,
            '{{REPOSITORY_INTERFACE_NAME}}' => "{$entityName}RepositoryInterface",
            '{{CONNECTION_CLASS_FQN}}' => $this->connectionClassFqn,
            '{{CONNECTION_CLASS_NAME}}' => $this->connectionClassName,
            '{{TABLE_NAME}}' => $tableName,
            '{{ID_FIELD}}' => $idField,
            '{{ID_PHP_TYPE}}' => $idPhpType,
            '{{REPO_VAR_NAME}}' => $repoVarName,
            '{{SAVE_METHOD_BODY}}' => $saveMethodBody,
            '{{DELETE_METHOD_BODY}}' => $deleteMethodBody,
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $template);
    }
}
