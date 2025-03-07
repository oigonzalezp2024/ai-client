<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Core\AIFileProcessor;
use App\Core\StructureBuilder;
use App\Core\DocumentationUpdater;

try {
    // Creación de estructura de archivos.
    $estructura = new StructureBuilder('../docs/structureFile.txt');
    $estructura->createStructure();
    echo "Estructura de proyectos creada.";

    // Actualizacion de documentación.
    $filePath1 = '../README.md';
    $filePath2 = '../docs/structureFile.txt';
    $doc = new DocumentationUpdater($filePath1);
    $doc->update($filePath2);

    // Asistente AI para desarrollo.
    /*
    $inputFile = __DIR__ . '/../storage/ai_input/prompt.md';
    $outputFile = __DIR__ . '/../storage/ai_output/response.md';
    $processor = new AIFileProcessor();
    $processor->processFile($inputFile, $outputFile);
    echo "Archivo procesado con éxito.";
    // */
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
