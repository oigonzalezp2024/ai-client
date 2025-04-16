<?php

namespace Modules\ImageUploader\Domain\Contracts;

use Modules\ImageUploader\Domain\Exceptions\UploadFailedException;

/**
 * Define la interfaz para los validadores técnicos de archivos subidos.
 */
interface TechnicalValidatorInterface
{
    /**
     * Valida aspectos técnicos mínimos del archivo.
     *
     * @param array $file El array $_FILES del archivo a validar.
     * @throws UploadFailedException Si la validación técnica falla.
     */
    public function validate(array $file): void;
}
