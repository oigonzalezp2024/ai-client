<?php

namespace Modules\ImageUploader\Application\Services;

use Modules\ImageUploader\Domain\Contracts\UploaderInterface;
use Modules\ImageUploader\Domain\Validators\ImageUploadBusinessValidator;
use Modules\ImageUploader\Infrastructure\Validators\TechnicalFileValidator;
use Modules\ImageUploader\Domain\Exceptions\UploadFailedException;

/**
 * Servicio para manejar la subida de imágenes.
 */
class UploadImageService
{
    private TechnicalFileValidator $technicalValidator;
    private ImageUploadBusinessValidator $businessValidator;
    private UploaderInterface $uploader;

    public function __construct(
        TechnicalFileValidator $technicalValidator,
        ImageUploadBusinessValidator $businessValidator,
        UploaderInterface $uploader
    ) {
        $this->technicalValidator = $technicalValidator;
        $this->businessValidator = $businessValidator;
        $this->uploader = $uploader;
    }

    /**
     * Maneja la subida de una imagen, validando primero los aspectos técnicos y luego los de negocio.
     *
     * @param array $file Archivo del array $_FILES
     * @return string Ruta del archivo subido
     * @throws UploadFailedException Si alguna validación falla o el upload no puede completarse
     */
    public function handle(array $file): string
    {
        try {
            // Validaciones técnicas
            $this->technicalValidator->validate($file);

            // Validaciones del negocio (como tipo de archivo y tamaño)
            $this->businessValidator->validate($file);

            // Si todo es válido, se sube el archivo
            return $this->uploader->upload($file);
        } catch (UploadFailedException $e) {
            // Loguear el error o manejarlo adecuadamente
            throw new UploadFailedException('Subida de archivo fallida: ' . $e->getMessage());
        }
    }
}
