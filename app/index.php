<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Core\AIFileProcessor;
use App\Core\StructureBuilder;
use App\Core\DocumentationUpdater;
use App\Core\DirectoryStructure;

try {
    // Creación de nueva estructura de archivos.
    $estructura = new StructureBuilder('../docs/structureFile.txt');
    $estructura->createStructure();
    echo "Estructura de proyectos creada.";
    //*/

    // Actualizacion de documentación.
    // Consulta la estructura de carpetas actual.
    $directoryPath = "../../ai-client";
    $structure = new DirectoryStructure($directoryPath);
    $content = $structure->displayStructure(); // cadena que contiene estructura actual.
    // Actualiza la documentacion de esa estructura.
    $filePath1 = '../README.md';
    $filePath2 = '../docs/structureFile.txt';
    $doc = new DocumentationUpdater($filePath1, $content); //content es opcional. // en este caso si aplica.
    $doc->update($filePath2); // de lo contrario tomara el contenido de $filePath2 // sono en caso de no $content.
    //*/

    // Asistente AI para desarrollo.
    //*
    $inputFile = __DIR__ . '/../app/Core/StructureBuilder.php';
    $outputFile = __DIR__ . '/../storage/ai_output/response.md';
    $processor = new AIFileProcessor();
    $processor->processFile($inputFile, $outputFile);
    echo "Archivo procesado con éxito.";
    //*/
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
