<?php
require __DIR__ . '/../../vendor/autoload.php';

use Modules\ImageUploader\Application\Services\UploadImageService;
use Modules\ImageUploader\Infrastructure\LocalFileUploader;
use Modules\ImageUploader\Infrastructure\Validators\TechnicalFileValidator;
use Modules\ImageUploader\Domain\Validators\ImageUploadBusinessValidator;

$uploader = new LocalFileUploader(__DIR__ . '/assets/images');
$technicalValidator = new TechnicalFileValidator();
$businessValidator = new ImageUploadBusinessValidator();

// Ahora pasamos todas las dependencias necesarias al constructor
$uploadService = new UploadImageService($technicalValidator, $businessValidator, $uploader);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    try {
        $path = $uploadService->handle($_FILES['image']);
        echo "Imagen subida exitosamente a: $path";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
header("Location: view.php");