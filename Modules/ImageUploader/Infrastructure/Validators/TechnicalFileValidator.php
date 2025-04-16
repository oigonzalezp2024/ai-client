<?php

namespace Modules\ImageUploader\Infrastructure\Validators;

use Modules\ImageUploader\Domain\Contracts\TechnicalValidatorInterface;
use Modules\ImageUploader\Domain\Exceptions\UploadFailedException;

/**
 * Valida aspectos técnicos mínimos del archivo antes de ser procesado.
 */
class TechnicalFileValidator implements TechnicalValidatorInterface
{
    /**
     * Verifica que el archivo ha sido correctamente subido.
     *
     * @param array $file
     * @throws UploadFailedException
     */
    public function validate(array $file): void
    {
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new UploadFailedException("El archivo no se subió correctamente.");
        }

        if (!is_uploaded_file($file['tmp_name'] ?? '')) {
            throw new UploadFailedException("No se reconoce el archivo como una subida válida.");
        }
    }
}
