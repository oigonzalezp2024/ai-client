<?php

namespace App\Core\AutoCode\Root;

/**
 * Clase responsable de escribir contenido de string a archivos en el sistema de archivos.
 * Maneja la creación de directorios si no existen.
 */
class CodeFileWriter
{
    /**
     * Escribe el contenido proporcionado en un archivo específico.
     * Crea el directorio si no existe.
     *
     * @param string $directory La ruta del directorio donde se guardará el archivo.
     * @param string $filename El nombre del archivo (ej. 'MyClass.php').
     * @param string $content El contenido (string) a escribir en el archivo.
     * @return bool True si el archivo se escribió exitosamente, false en caso contrario.
     */
    public function write(string $directory, string $filename, string $content): bool
    {
        // Asegurarse de que el directorio existe, y si no, intentar crearlo
        if (!is_dir($directory)) {
            // Usa mkdir con recursividad y permisos 0755
            if (!mkdir($directory, 0755, true) && !is_dir($directory)) {
                // Si mkdir falla y el directorio todavía no existe (posible error de permisos)
                error_log("Error: No se pudo crear el directorio '{$directory}'.");
                return false;
            }
        }

        $filePath = rtrim($directory, '/') . '/' . $filename; // Asegura una única barra

        // Escribir el contenido en el archivo
        if (file_put_contents($filePath, $content) === false) {
            error_log("Error: No se pudo escribir en el archivo '{$filePath}'.");
            return false;
        }

        return true;
    }
}
