<?php

namespace App\Core\AutoCode\Root;

use App\Core\AutoCode\Root\CodeConventionConverter;
use App\Core\AutoCode\Root\DataDefinition; // Asegúrate de que esta clase exista y esté correctamente referenciada.

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
        $repoVarName = $this->converter->toCamelCase($entityName); // Nombre de la variable de la entidad en el repo (ej: $persona)
        $idMethodName = 'get' . ucfirst($this->converter->toCamelCase($idField)); // Nombre del getter para el ID (ej: getIdPersona)
        $idSetMethodName = 'set' . ucfirst($this->converter->toCamelCase($idField)); // Nombre del setter para el ID (ej: setIdPersona)

        // --- Construcción de lógica para el método create (INSERT) ---
        $fieldsForInsert = [];
        $placeholdersForInsert = [];
        $manualBindValuesForInsert = [];

        foreach ($definitions as $index => $definition) {
            if ($index === 0) continue; // Omitir el ID para INSERT
            $fieldName = $definition->getFieldName();
            $camelFieldName = $this->converter->toCamelCase($fieldName);
            $getMethodName = 'get' . ucfirst($camelFieldName);
            $dataType = $definition->getDataType();
            
            $fieldsForInsert[] = "`{$fieldName}`";
            $placeholdersForInsert[] = ":{$fieldName}";
            
            if ($dataType === 'bool') {
                $manualBindValuesForInsert[] = "\$stmt->bindValue(':" . $fieldName . "', (int)\${$repoVarName}->{$getMethodName}(), \PDO::PARAM_INT);";
            } elseif ($definition->isNullable()) {
                $manualBindValuesForInsert[] = "if (\${$repoVarName}->{$getMethodName}() === null) {\n                \$stmt->bindValue(':" . $fieldName . "', null, \PDO::PARAM_NULL);\n            } else {\n                \$stmt->bindValue(':" . $fieldName . "', \${$repoVarName}->{$getMethodName}());\n            }";
            } else {
                $manualBindValuesForInsert[] = "\$stmt->bindValue(':" . $fieldName . "', \${$repoVarName}->{$getMethodName}());";
            }
        }
        $fieldsForInsertString = implode(', ', $fieldsForInsert);
        $placeholdersForInsertString = implode(', ', $placeholdersForInsert);
        $bindValuesForInsertString = implode("\n            ", $manualBindValuesForInsert);

        // --- Construcción de lógica para el método update (UPDATE) ---
        $updateSetParts = [];
        $bindValuesForUpdate = [];
        foreach ($definitions as $index => $definition) {
            if ($index === 0) continue; // Omitir el ID para la cláusula SET
            
            $fieldName = $definition->getFieldName();
            $camelFieldName = $this->converter->toCamelCase($fieldName);
            $getMethodName = 'get' . ucfirst($camelFieldName);
            $dataType = $definition->getDataType();
            
            $updateSetParts[] = "`{$fieldName}` = :{$fieldName}";

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


        // ***** CAMBIO AQUÍ: Definición del método create *****
        // Es mejor tener 'create' y 'update' separados en el repositorio si los Use Cases los usan así.
        // Si tu CreatePersonaUseCase llama a un método 'save' que inserta, mantén el `saveMethodBody` y el `createMethodBody` separado.
        // Dado que el test de integración llama a `create` directamente, necesitamos un `create` separado.
        $createMethodBody = <<<PHP
        \$sql = "INSERT INTO {$tableName} ({$fieldsForInsertString}) VALUES ({$placeholdersForInsertString})";
        \$stmt = \$this->connection->prepare(\$sql);
        {$bindValuesForInsertString}
        \$stmt->execute();
        \${$repoVarName}->{$idSetMethodName}((int)\$this->connection->lastInsertId());
        return \${$repoVarName};
PHP;

        // ***** CAMBIO AQUÍ: Definición del método update *****
        // Este es el método que será llamado por UpdatePersonaUseCase
        $updateMethodBody = <<<PHP
        \$sql = "UPDATE {$tableName} SET {$updateSetString} WHERE {$idField} = :{$idField}";
        \$stmt = \$this->connection->prepare(\$sql);
        {$bindValuesForUpdateString}
        \$stmt->bindValue(':{$idField}', \${$repoVarName}->{$idMethodName}(), \PDO::PARAM_INT);
        \$stmt->execute();
        return \$stmt->rowCount() > 0; // CRÍTICO: Devolver booleano
PHP;

        // ***** CAMBIO AQUÍ: Definición del método delete *****
        // El método delete del repositorio debe recibir un INT
        $deleteMethodBody = <<<PHP
        \$stmt = \$this->connection->prepare("DELETE FROM {$tableName} WHERE {$idField} = :id");
        \$stmt->bindParam(':id', \$id, \PDO::PARAM_INT); // bindParam con el ID recibido
        \$stmt->execute();
        return \$stmt->rowCount() > 0; // CRÍTICO: Devolver booleano
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
            // ***** CAMBIO AQUÍ: Nuevos placeholders para los métodos separados *****
            '{{CREATE_METHOD_BODY}}' => $createMethodBody,
            '{{UPDATE_METHOD_BODY}}' => $updateMethodBody,
            '{{DELETE_METHOD_BODY}}' => $deleteMethodBody,
            // NOTA: Si aún tienes el placeholder {{SAVE_METHOD_BODY}} en tu plantilla,
            // deberías eliminarlo de la plantilla o reemplazarlo por un string vacío si ya no lo usas.
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $template);
    }
}
