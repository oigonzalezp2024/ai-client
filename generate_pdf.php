<?php

declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL & ~E_DEPRECATED);

require_once __DIR__ . '/vendor/autoload.php';
require_once(__DIR__ . '/app/PDF/Infrastructure/Adapters/ProyectoFPDFAdapter.php');

use Dotenv\Dotenv;
use App\ChatbotProjectAI\Application\DTO\CreateProjectCommand;
use App\ChatbotProjectAI\Application\DTO\ProjectCreatedResponse;
use App\ChatbotProjectAI\Application\Orchestrator\FeedbackImplement;
use App\ChatbotProjectAI\Application\Service\AIProjectService;
use App\ChatbotProjectAI\Application\Service\ProjectFormatterService;
use App\ChatbotProjectAI\Application\Service\PdfGeneratorService;

// Carga variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__ . '/');
$dotenv->load();

$apiKey = $_ENV['AI_API_KEY'] ?? null;
$baseUri = $_ENV['AI_BASE_URI'] ?? null;
$model = $_ENV['AI_MODEL'] ?? null;

if (!$apiKey || !$baseUri || !$model) {
    die("Error: faltan variables de entorno AI_API_KEY, AI_BASE_URI o AI_MODEL.");
}

// Prompt y nombre del PDF
$prompt = "Proyecto de creación de un restaurante colombiano en cucuta.";
$filePdfName = "reporte_ecommerce.pdf";

// Orquestación de servicios
$command = new CreateProjectCommand($prompt);
$feedbackProcessor = new FeedbackImplement(new ProjectCreatedResponse());

$projectService = new AIProjectService($feedbackProcessor);
$responseDto = $projectService->create($command, $apiKey, $baseUri, $model);

$formatter = new ProjectFormatterService($responseDto);
$data = $formatter->getData();

$pdfService = new PdfGeneratorService($data, $filePdfName);
$pdfService->generateAndOutput();
