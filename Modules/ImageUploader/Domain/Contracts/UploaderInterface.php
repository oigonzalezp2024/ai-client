<?php

namespace Modules\ImageUploader\Domain\Contracts;

/**
 * Interface UploaderInterface
 * Define los métodos para subir y eliminar archivos.
 */
interface UploaderInterface
{
    /**
     * Sube un archivo al sistema.
     *
     * @param array $file Archivo del array $_FILES
     * @param string|null $directory Directorio de destino relativo
     * @return string Ruta del archivo guardado
     */
    public function upload(array $file, ?string $directory = null): string;

    /**
     * Elimina un archivo del sistema.
     *
     * @param string $path Ruta completa del archivo
     * @return bool Verdadero si fue eliminado con éxito
     */
    public function delete(string $path): bool;
}
