<?php

namespace App\Core;

/**
 * Clase para actualizar la documentación de un archivo.
 */
class DocumentationUpdater {

    /**
     * @var string Ruta al archivo de documentación que se actualizará.
     */
    private $filePath;

    /**
     * Constructor de la clase.
     *
     * @param string $filePath Ruta al archivo de documentación.
     */
    public function __construct($filePath) {
        $this->filePath = $filePath;
    }

    /**
     * Actualiza el archivo de documentación con el nuevo contenido.
     *
     * @param string $newContentFilePath Ruta al archivo que contiene el nuevo contenido para la documentación.
     * @return bool True si la actualización fue exitosa, false si no.
     */
    public function update($newContentFilePath) {
        // 1. Leer el archivo original
        $originalContent = file_get_contents($this->filePath);

        // 2. Leer el nuevo contenido
        $newContent = file_get_contents($newContentFilePath);

        // 3. Definir los delimitadores
        $startDelimiter = "## Estructura del Proyecto";
        $endDelimiter = "## Contribución";

        // 4. Encontrar las posiciones de los delimitadores
        $startPos = strpos($originalContent, $startDelimiter);
        $endPos = strpos($originalContent, $endDelimiter, $startPos + strlen($startDelimiter)); // Buscar después del primer delimitador

        // 5. Verificar si los delimitadores fueron encontrados
        if ($startPos === false || $endPos === false) {
            echo "Error: Delimitadores no encontrados en el archivo original." . PHP_EOL;
            return false;
        }

        // 6. Construir el nuevo contenido
        $newOriginalContent = substr($originalContent, 0, $startPos + strlen($startDelimiter) + 1) .  // +1 para incluir la nueva línea después del título
                              "\n" . '```' . "\n" . $newContent . "\n" . '```' . "\n" .
                              substr($originalContent, $endPos);

        // 7. Escribir el nuevo contenido en el archivo original
        if (file_put_contents($this->filePath, $newOriginalContent) !== false) {
            echo "Archivo actualizado exitosamente." . PHP_EOL;
            return true;
        } else {
            echo "Error al escribir en el archivo." . PHP_EOL;
            return false;
        }
    }
}
