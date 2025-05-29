<?php

namespace App\Core\AutoCode\Root;

use PDO; // Asegúrate de que PDO esté disponible para los tipos de parámetros

class CodeConventionConverter
{
    /**
     * Convierte una cadena a formato PascalCase (primera letra de cada palabra en mayúscula).
     * Ej: "id_proveedor" -> "IdProveedor", "nombre" -> "Nombre"
     *
     * @param string $string La cadena a convertir.
     * @return string La cadena en formato PascalCase.
     */
    public function toPascalCase(string $string): string
    {
        // Convierte guiones bajos y medios a espacios, luego capitaliza la primera letra de cada palabra
        // y elimina los espacios.
        return str_replace(' ', '', ucwords(str_replace(['_', '-'], ' ', $string)));
    }

    /**
     * Convierte una cadena a formato camelCase (primera letra en minúscula, el resto de palabras capitalizadas).
     * Ej: "id_proveedor" -> "idProveedor", "nombre" -> "nombre"
     *
     * @param string $string La cadena a convertir.
     * @return string La cadena en formato camelCase.
     */
    public function toCamelCase(string $string): string
    {
        // Reutiliza toPascalCase y luego convierte la primera letra a minúscula.
        return lcfirst($this->toPascalCase($string));
    }

    /**
     * Convierte una cadena a formato snake_case (todo en minúsculas, palabras separadas por guiones bajos).
     * Ej: "NombreProducto" -> "nombre_producto", "idProveedor" -> "id_proveedor"
     *
     * @param string $string La cadena a convertir.
     * @return string La cadena en formato snake_case.
     */
    public function toSnakeCase(string $string): string
    {
        $string = preg_replace('/(?<!^)[A-Z]/', '_$0', $string);
        return strtolower($string);
    }

    /**
     * Mapea un tipo de dato de base de datos/definición a un tipo de dato PHP nativo.
     *
     * @param string $dataType El tipo de dato de la definición (ej. 'string', 'int', 'bool', 'datetime').
     * @return string El tipo de dato PHP correspondiente (ej. 'string', 'int', 'bool', 'DateTimeImmutable').
     */
    public function mapDataTypeToPhpType(string $dataType): string
    {
        switch (strtolower($dataType)) {
            case 'varchar':
            case 'text':
            case 'char':
            case 'mediumtext':
            case 'longtext':
                return 'string';
            case 'int':
            case 'tinyint':
            case 'smallint':
            case 'mediumint':
            case 'bigint':
                return 'int';
            case 'float':
            case 'double':
            case 'decimal':
                return 'float';
            case 'boolean':
            case 'bool':
                return 'bool';
            case 'date':
            case 'datetime':
            case 'timestamp':
                return 'DateTimeImmutable'; // Usar DateTimeImmutable para inmutabilidad
            default:
                return 'string'; // Por defecto, si no se reconoce, usar string
        }
    }

    /**
     * Obtiene el tipo de parámetro PDO correspondiente a un tipo de dato PHP.
     *
     * @param string $phpType El tipo de dato PHP (ej. 'string', 'int', 'bool', 'DateTimeImmutable').
     * @return int La constante PDO::PARAM_* correspondiente.
     */
    public function getPdoParamType(string $phpType): int
    {
        switch (strtolower($phpType)) {
            case 'int':
                return PDO::PARAM_INT;
            case 'bool':
                return PDO::PARAM_BOOL;
            case 'null': // Para cuando el valor es explícitamente null
                return PDO::PARAM_NULL;
            case 'string':
            case 'float': // PDO::PARAM_STR puede manejar floats para bindValue
            case 'datetimeimmutable': // Convertir a string antes de bindear
            default:
                return PDO::PARAM_STR;
        }
    }
}