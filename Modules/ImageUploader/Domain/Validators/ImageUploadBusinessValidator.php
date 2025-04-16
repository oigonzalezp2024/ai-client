<?php

namespace Modules\ImageUploader\Domain\Validators;

use Modules\ImageUploader\Domain\Exceptions\UploadFailedException;

/**
 * Valida reglas de negocio para la subida de imágenes.
 */
class ImageUploadBusinessValidator
{
    private array $allowedMimeTypes;
    private int $maxFileSize;

    public function __construct(
        array $allowedMimeTypes = ['image/jpeg', 'image/png'],
        int $maxFileSize = 2_097_152 // 2MB
    ) {
        $this->allowedMimeTypes = $allowedMimeTypes;
        $this->maxFileSize = $maxFileSize;
    }

    /**
     * Verifica que el archivo cumpla con las reglas del negocio.
     *
     * @param array $file
     * @throws UploadFailedException
     */
    public function validate(array $file): void
    {
        $mime = mime_content_type($file['tmp_name']);
        $size = $file['size'] ?? 0;
        $fileName = $file['name'];

        if (!in_array($mime, $this->allowedMimeTypes)) {
            throw new UploadFailedException("El tipo de archivo '$mime' para el archivo '{$fileName}' no está permitido.");
        }

        if ($size > $this->maxFileSize) {
            throw new UploadFailedException("El archivo '{$fileName}' excede el tamaño máximo de {$this->maxFileSize} bytes.");
        }
    }
}
