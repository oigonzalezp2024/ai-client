<?php

namespace App\Core\AutoCode\Root;

use InvalidArgumentException;
use DateTimeImmutable; // Asegúrate de que DateTimeImmutable esté importado

class CodeGenerator
{
    private string $templatePath;
    private CodeConventionConverter $converter;

    public function __construct(string $templatePath, CodeConventionConverter $converter)
    {
        if (!file_exists($templatePath) || !is_readable($templatePath)) {
            throw new InvalidArgumentException("Template file not found or not readable: {$templatePath}");
        }
        $this->templatePath = $templatePath;
        $this->converter = $converter;
    }

    /**
     * Genera el código PHP para una entidad de dominio basándose en las definiciones de datos.
     *
     * @param DataDefinition[] $definitions Array de objetos DataDefinition.
     * @param string $entityName El nombre de la entidad (ej: 'Proveedor').
     * @param string $namespace El namespace para la entidad generada.
     * @return string El código PHP generado para la entidad.
     */
    public function generate(array $definitions, string $entityName, string $namespace): string
    {
        $propertiesCode = '';
        $gettersCode = '';
        $settersCode = '';
        $fromArrayBodyCode = '';
        $usesCode = []; // Para manejar los 'use' dinámicamente

        // Propiedades
        foreach ($definitions as $dataDefinitionObject) {
            $fieldName = $dataDefinitionObject->getFieldName();
            $camelCaseField = $this->converter->toCamelCase($fieldName);
            $phpDataType = $dataDefinitionObject->getDataType();
            $nullablePrefix = $dataDefinitionObject->isNullable() ? '?' : '';

            // Añadir 'use' para DateTimeImmutable si se usa como tipo
            if ($phpDataType === 'DateTimeImmutable' && !in_array('DateTimeImmutable', $usesCode)) {
                $usesCode[] = 'DateTimeImmutable';
            }

            $propertiesCode .= "    private {$nullablePrefix}{$phpDataType} \${$camelCaseField};\n";
        }

        // Getters y Setters
        foreach ($definitions as $dataDefinitionObject) {
            $fieldName = $dataDefinitionObject->getFieldName();
            $camelCaseField = $this->converter->toCamelCase($fieldName);
            $phpDataType = $dataDefinitionObject->getDataType();
            $nullablePrefix = $dataDefinitionObject->isNullable() ? '?' : '';
            $methodName = ucfirst($camelCaseField); // Ej: 'IdProveedor', 'Nombre'

            // Getters
            $gettersCode .= "\n    public function get{$methodName}(): {$nullablePrefix}{$phpDataType}\n";
            $gettersCode .= "    {\n";
            $gettersCode .= "        return \$this->{$camelCaseField};\n";
            $gettersCode .= "    }\n";

            // Setters
            $settersCode .= "\n    public function set{$methodName}({$nullablePrefix}{$phpDataType} \${$camelCaseField}): void\n";
            $settersCode .= "    {\n";
            $settersCode .= "        \$this->{$camelCaseField} = \${$camelCaseField};\n";
            $settersCode .= "    }\n";
        }

        // fromArray Method Body
        // Incluimos InvalidArgumentException porque fromArray puede lanzarla (aunque tu plantilla actual no lo hace)
        if (!in_array('InvalidArgumentException', $usesCode)) {
             $usesCode[] = 'InvalidArgumentException';
        }
        $fromArrayBodyCode .= "        \$entity = new self();\n";
        foreach ($definitions as $dataDefinitionObject) {
            $fieldName = $dataDefinitionObject->getFieldName();
            $camelCaseField = $this->converter->toCamelCase($fieldName);
            $columnName = $dataDefinitionObject->getColumnName();
            $phpDataType = $dataDefinitionObject->getDataType();
            $isNullable = $dataDefinitionObject->isNullable();
            $defaultValue = $dataDefinitionObject->getDefaultValue();
            
            $conversion = '';
            $nullCoalescing = '';

            // Decide cómo convertir y si usar coalescing
            switch ($phpDataType) {
                case 'int':
                    $conversion = '(int)';
                    $nullCoalescing = ($isNullable) ? ' : null' : ' : 0'; // Si es nullable, null; si no, 0.
                    break;
                case 'string':
                    $conversion = '(string)';
                    $nullCoalescing = ($isNullable) ? ' : null' : ' : \'\''; // Si es nullable, null; si no, string vacía.
                    break;
                case 'bool':
                    $conversion = '(bool)';
                    $nullCoalescing = ($isNullable) ? ' : null' : ' : false'; // Si es nullable, null; si no, false.
                    break;
                case 'float':
                    $conversion = '(float)';
                    $nullCoalescing = ($isNullable) ? ' : null' : ' : 0.0'; // Si es nullable, null; si no, 0.0.
                    break;
                case 'DateTimeImmutable':
                    // Para DateTimeImmutable, si es nullable, puede ser null. Si no, debería ser un objeto.
                    $conversion = ''; // No castear directamente a objeto aquí
                    $nullCoalescing = ($isNullable) ? ' : null' : ' : (new DateTimeImmutable())'; // Si no es nullable, inicializa a uno nuevo
                    break;
                default:
                    $conversion = ''; 
                    $nullCoalescing = ($isNullable) ? ' : null' : ' : throw new InvalidArgumentException("Missing non-nullable object for {$fieldName}")';
                    break;
            }

            $fromArrayBodyCode .= "        \$entity->set" . ucfirst($this->converter->toCamelCase($fieldName)) . "(isset(\$data['{$columnName}']) ? {$conversion}\$data['{$columnName}']{$nullCoalescing});\n";
        }
        $fromArrayBodyCode .= "        return \$entity;\n";


        // Constructor
        $constructorBodyCode = '';
        foreach ($definitions as $dataDefinitionObject) {
            $camelCaseField = $this->converter->toCamelCase($dataDefinitionObject->getFieldName());
            $phpDataType = $dataDefinitionObject->getDataType();
            $nullable = $dataDefinitionObject->isNullable();

            if ($nullable) {
                $constructorBodyCode .= "        \$this->{$camelCaseField} = null;\n";
            } else {
                // Inicializar non-nullable fields a su valor por defecto apropiado
                switch ($phpDataType) {
                    case 'string':
                        $constructorBodyCode .= "        \$this->{$camelCaseField} = '';\n";
                        break;
                    case 'int':
                        $constructorBodyCode .= "        \$this->{$camelCaseField} = 0;\n";
                        break;
                    case 'float':
                        $constructorBodyCode .= "        \$this->{$camelCaseField} = 0.0;\n";
                        break;
                    case 'bool':
                        $constructorBodyCode .= "        \$this->{$camelCaseField} = false;\n";
                        break;
                    case 'DateTimeImmutable':
                        $constructorBodyCode .= "        \$this->{$camelCaseField} = new DateTimeImmutable();\n";
                        break;
                    default:
                        break;
                }
            }
        }


        // Renderizar la plantilla
        $templateContent = file_get_contents($this->templatePath);

        $replacements = [
            '{{NAMESPACE}}' => $namespace, // ¡CORREGIDO! Ahora coincide con la plantilla
            '{{ENTITY_NAME}}' => $entityName, // ¡CORREGIDO!
            '{{USES}}' => !empty($usesCode) ? 'use ' . implode(";\nuse ", array_unique($usesCode)) . ';' : '', // ¡CORREGIDO!
            '{{PROPERTIES}}' => trim($propertiesCode), // ¡CORREGIDO!
            '{{CONSTRUCTOR_BODY}}' => trim($constructorBodyCode), // ¡CORREGIDO!
            '{{GETTERS}}' => trim($gettersCode), // ¡CORREGIDO!
            '{{SETTERS}}' => trim($settersCode), // ¡CORREGIDO!
            '{{FROM_ARRAY_BODY}}' => trim($fromArrayBodyCode), // ¡CORREGIDO!
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $templateContent);
    }
}
