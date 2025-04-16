<?php

namespace Modules\ImageUploader\Infrastructure;

use Modules\ImageUploader\Domain\Contracts\UploaderInterface;
use Modules\ImageUploader\Domain\Exceptions\UploadFailedException;

/**
 * Implementación concreta de subida de archivos al sistema de archivos.
 */
class LocalFileUploader implements UploaderInterface
{
    public function __construct(
        private string $basePath
    ) {
        $this->basePath = rtrim($basePath, '/') . '/';
    }

    public function upload(array $file, ?string $directory = null): string
    {
        $dir = $this->basePath . ($directory ? trim($directory, '/') . '/' : '');

        if (!is_dir($dir)) {
            if (!mkdir($dir, 0755, true) && !is_dir($dir)) {
                throw new UploadFailedException("No se pudo crear el directorio: {$dir}");
            }
        }

        $filename = $this->generateFilename($file['name']);
        $destination = $dir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new UploadFailedException("Error al mover el archivo a: {$destination}");
        }

        return $destination;
    }

    public function delete(string $path): bool
    {
        return file_exists($path) && unlink($path);
    }

    /**
     * Genera un nombre único para el archivo.
     *
     * @param string $originalName
     * @return string
     */
    private function generateFilename(string $originalName): string
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        return uniqid('img_', true) . '.' . $extension;
    }
}
