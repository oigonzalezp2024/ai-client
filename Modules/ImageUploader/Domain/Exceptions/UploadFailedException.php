<?php

namespace Modules\ImageUploader\Domain\Exceptions;

use Exception;

/**
 * Excepción personalizada para fallos de subida.
 */
class UploadFailedException extends Exception
{
    public function __construct(string $message = "No se pudo subir el archivo.", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
