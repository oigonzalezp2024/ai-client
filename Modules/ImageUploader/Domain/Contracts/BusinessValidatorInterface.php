<?php

namespace Modules\ImageUploader\Domain\Contracts;

/**
 * Interfaz para la validación de reglas de negocio durante la subida de imágenes.
 */
interface BusinessValidatorInterface
{
    /**
     * Valida el archivo desde una perspectiva de negocio.
     *
     * @param array $file Archivo proveniente de $_FILES
     * @return void
     * @throws \Modules\ImageUploader\Domain\Exceptions\UploadFailedException
     */
    public function validate(array $file): void;
}
