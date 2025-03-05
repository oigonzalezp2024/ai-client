<?php

// Uso de AI

require __DIR__ . '/../vendor/autoload.php';

use App\Core\AIFileProcessor;

try {
    $inputFile = __DIR__ . '/../storage/ai_input/prompt.txt';
    $outputFile = __DIR__ . '/../storage/ai_output/response.txt';
    $processor = new AIFileProcessor();
    $processor->processFile($inputFile, $outputFile);
    echo "Archivo procesado con éxito.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
