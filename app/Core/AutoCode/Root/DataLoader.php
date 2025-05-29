<?php

namespace App\Core\AutoCode\Root;

use InvalidArgumentException;
use App\Core\AutoCode\Root\DataDefinition;

class DataLoader
{
    private string $dataDefinitionsFile;

    public function __construct(string $dataDefinitionsFile)
    {
        if (!file_exists($dataDefinitionsFile) || !is_readable($dataDefinitionsFile)) {
            throw new InvalidArgumentException("Data definition file not found or not readable: {$dataDefinitionsFile}");
        }
        $this->dataDefinitionsFile = $dataDefinitionsFile;
    }

    /**
     * Carga las definiciones de datos desde el archivo y las convierte en objetos DataDefinition.
     *
     * @return array Un array asociativo donde la clave es el nombre de la entidad
     * y el valor es un array de objetos DataDefinition.
     * @throws InvalidArgumentException Si el archivo de definiciones no retorna un array válido.
     */
    public function load(): array
    {
        $rawDefinitions = require $this->dataDefinitionsFile;

        if (!is_array($rawDefinitions)) {
            throw new InvalidArgumentException("Data definition file must return an array.");
        }

        $loadedDefinitions = [];
        foreach ($rawDefinitions as $entityName => $entityDefArrays) {
            if (!is_string($entityName) || !is_array($entityDefArrays)) {
                throw new InvalidArgumentException("Invalid entity definition for '{$entityName}'. Expected string key and array value.");
            }

            $loadedDefinitions[$entityName] = [];
            foreach ($entityDefArrays as $defArray) {
                if (!is_array($defArray)) {
                    throw new InvalidArgumentException("Invalid field definition for entity '{$entityName}'. Expected an array.");
                }

                $requiredKeys = ['field_name', 'data_type', 'column_name', 'is_primary_key', 'is_autoincrement', 'is_nullable'];
                foreach ($requiredKeys as $key) {
                    if (!array_key_exists($key, $defArray)) {
                        throw new InvalidArgumentException("Missing required key '{$key}' for a field definition in entity '{$entityName}'.");
                    }
                }
                
                // --- INICIO DEL CAMBIO CRÍTICO QUE HICIMOS AQUÍ ---
                $isPrimaryKey = (bool)$defArray['is_primary_key'];
                $isAutoincrement = (bool)$defArray['is_autoincrement'];
                $isNullable = (bool)$defArray['is_nullable'];

                // Si es llave primaria y autoincremental, forzamos que sea nullable a nivel de la aplicación.
                // Esto es crucial para la inicialización de propiedades tipadas en PHP.
                if ($isPrimaryKey && $isAutoincrement) {
                    $isNullable = true; 
                }
                // --- FIN DEL CAMBIO CRÍTICO ---

                $loadedDefinitions[$entityName][] = DataDefinition::create(
                    $defArray['field_name'],
                    $defArray['data_type'],
                    $defArray['column_name'],
                    $isPrimaryKey,
                    $isAutoincrement,
                    $isNullable, // Usamos la variable $isNullable que puede haber sido forzada a true
                    $defArray['default_value'] ?? null
                );
            }
        }

        return $loadedDefinitions;
    }
}
