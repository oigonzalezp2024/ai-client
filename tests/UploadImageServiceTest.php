<?php

use PHPUnit\Framework\TestCase;
use Modules\ImageUploader\Application\Services\UploadImageService;
use Modules\ImageUploader\Domain\Contracts\UploaderInterface;
use Modules\ImageUploader\Domain\Validators\ImageUploadBusinessValidator;
use Modules\ImageUploader\Infrastructure\Validators\TechnicalFileValidator;
use Modules\ImageUploader\Domain\Exceptions\UploadFailedException;

class UploadImageServiceTest extends TestCase
{
    public function testSuccessfulUpload()
    {
        // Crear un mock para el validador técnico
        $technicalValidator = $this->createMock(TechnicalFileValidator::class);
        $technicalValidator->expects($this->once())
            ->method('validate')
            ->with($this->isType('array'))
            // No se devuelve nada para un método void
            ->willReturnCallback(function () {});

        // Crear un mock para el validador de negocio
        $businessValidator = $this->createMock(ImageUploadBusinessValidator::class);
        $businessValidator->expects($this->once())
            ->method('validate')
            ->with($this->isType('array'))
            // No se devuelve nada para un método void
            ->willReturnCallback(function () {});

        // Crear un mock para el uploader
        $uploader = $this->createMock(UploaderInterface::class);
        $uploader->expects($this->once())
            ->method('upload')
            ->willReturn('path/to/uploaded/file.jpg');

        // Crear la instancia del servicio con los mocks
        $uploadService = new UploadImageService($technicalValidator, $businessValidator, $uploader);

        // Simular un archivo
        $file = [
            'name' => 'image.jpg',
            'tmp_name' => '/tmp/php/file.jpg',
            'error' => UPLOAD_ERR_OK,
            'size' => 1024
        ];

        // Ejecutar el método handle y verificar la salida
        $path = $uploadService->handle($file);
        $this->assertEquals('path/to/uploaded/file.jpg', $path);
    }

    public function testHandleThrowsExceptionOnValidationFailure()
    {
        // Crear un mock para el validador técnico
        $technicalValidator = $this->createMock(TechnicalFileValidator::class);
        $technicalValidator->expects($this->once())
            ->method('validate')
            ->with($this->isType('array'))
            ->willThrowException(new UploadFailedException("Archivo no válido"));

        // Crear un mock para el validador de negocio
        $businessValidator = $this->createMock(ImageUploadBusinessValidator::class);
        $businessValidator->expects($this->once())
            ->method('validate')
            ->with($this->isType('array'))
            ->willReturnCallback(function () {});

        // Crear un mock para el uploader
        $uploader = $this->createMock(UploaderInterface::class);
        $uploader->expects($this->never()) // No debe ser llamado si falla la validación
            ->method('upload');

        // Crear la instancia del servicio con los mocks
        $uploadService = new UploadImageService($technicalValidator, $businessValidator, $uploader);

        // Simular un archivo
        $file = [
            'name' => 'image.jpg',
            'tmp_name' => '/tmp/php/file.jpg',
            'error' => UPLOAD_ERR_OK,
            'size' => 1024
        ];

        // Verificar que se lanza la excepción
        $this->expectException(UploadFailedException::class);
        $this->expectExceptionMessage("Archivo no válido");

        // Ejecutar el método handle
        $uploadService->handle($file);
    }
}
