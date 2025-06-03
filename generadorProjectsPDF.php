<?php

// Asegúrate de que el autoloader esté disponible
require_once __DIR__ . '/vendor/autoload.php';

// Cargar ProyectoFPDFAdapter directamente ya que no usa namespaces
// Ajusta esta ruta si tu ProyectoFPDFAdapter.php no está en este lugar
require_once(__DIR__ . '/app/PDF/Infrastructure/Adapters/ProyectoFPDFAdapter.php');

// DECLARACIONES 'use' DEBEN IR AQUÍ
use App\Chatbot\Application\UseCases\Persona\CreatePersonaUseCase;
use App\Chatbot\Application\UseCases\Persona\GetPersonaByCellphoneUseCase;
use App\Chatbot\Application\UseCases\Pregunta\CreatePreguntaUseCase;
use App\Chatbot\Infrastructure\Persistence\MySQL\DatabaseConnection;
use App\Chatbot\Infrastructure\Persistence\MySQL\PersonaSearch;
use App\Chatbot\Infrastructure\Persistence\MySQL\PreguntaRepository;
use App\Chatbot\Infrastructure\Persistence\MySQL\PersonaRepository;
use App\Chatbot\Infrastructure\AI\AIPromptProcessor;
use Dotenv\Dotenv;

// Nuevas dependencias para la generación de PDF
use App\ChatbotProjectAI\Application\DTO\CreateProjectCommand;
use App\ChatbotProjectAI\Application\DTO\ProjectCreatedResponse;
use App\ChatbotProjectAI\Application\Orchestrator\FeedbackImplement;
use App\ChatbotProjectAI\Application\Service\AIProjectService;
use App\ChatbotProjectAI\Application\Service\ProjectFormatterService;
use App\ChatbotProjectAI\Application\Service\PdfGeneratorService;

// Establecer la cabecera Content-Type a application/json inmediatamente
header('Content-Type: application/json');

// Inicializar la respuesta JSON
$response = [
    'status' => 'error',
    'message' => 'Ha ocurrido un error desconocido.',
    'aiResponse' => '' // El frontend solo leerá esta clave como texto
];

try {
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    // --- Configuración de la Base de Datos ---
    $dbConfig = [
        'host' => $_ENV['DB_HOST'] ?? 'localhost',
        'name' => $_ENV['DB_NAME'] ?? 'chatbot',
        'user' => $_ENV['DB_USER'] ?? 'root',
        'password' => $_ENV['DB_PASSWORD'] ?? '',
    ];

    // --- Configuración de la AI ---
    $aiApiKey = $_ENV['AI_API_KEY'] ?? null;
    $aiBaseUri = $_ENV['AI_BASE_URI'] ?? null;
    $aiModel = $_ENV['AI_MODEL'] ?? null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // 1. Obtener y sanear los datos del formulario
        $nombre = filter_input(INPUT_POST, 'nombre', FILTER_UNSAFE_RAW);
        $celular = filter_input(INPUT_POST, 'celular', FILTER_UNSAFE_RAW);
        $preguntaTexto = filter_input(INPUT_POST, 'pregunta', FILTER_UNSAFE_RAW);
        $fecha = date('Y-m-d');

        // 2. Validación básica de datos
        if (!$nombre || !$celular || !$preguntaTexto) {
            $response['message'] = "Los campos Nombre, Celular y Pregunta son obligatorios. Por favor, complételos.";
        } else {
            try {
                // 3. Conexión a la Base de Datos
                $dbConnection = new DatabaseConnection(
                    $dbConfig['host'],
                    $dbConfig['name'],
                    $dbConfig['user'],
                    $dbConfig['password']
                );
                $pdo = $dbConnection->connect();

                // 4. Lógica de Negocio (Usando Casos de Uso)
                $personaSearch = new PersonaSearch($pdo);
                $personaRepository = new PersonaRepository($pdo);
                $getPersonaByCellphoneUseCase = new GetPersonaByCellphoneUseCase($personaSearch);
                $createPersonaUseCase = new CreatePersonaUseCase($personaRepository);

                $persona = $getPersonaByCellphoneUseCase->execute($celular);

                if (!$persona) {
                    // Crear nueva persona usando la fecha generada internamente
                    $persona = $createPersonaUseCase->execute(
                        $nombre,
                        $celular,
                        $fecha,
                        true // Asumiendo que 'true' es para 'isActive'
                    );
                }

                // --- PROCESAMIENTO DE LA PREGUNTA CON LA IA ---
                if (empty($aiApiKey) || empty($aiBaseUri) || empty($aiModel)) {
                    error_log("Advertencia: Variables de entorno de IA incompletas en chat.php. AI_API_KEY: " . ($aiApiKey ? 'SET' : 'NOT SET') . ", AI_BASE_URI: " . ($aiBaseUri ?? 'NOT SET') . ", AI_MODEL: " . ($aiModel ?? 'NOT SET'));
                    $response['message'] = "Error interno del servidor: Configuración de IA incompleta.";
                } else {
                    try {
                        $aiProcessor = new AIPromptProcessor($aiApiKey, $aiBaseUri, $aiModel);
                        $promnt = $preguntaTexto;
                        $aiResponseText = $aiProcessor->getAIResponse($promnt);
                        
                        // Limpia la respuesta de la IA de cualquier tipo de espacio en blanco (newlines, tabs, multiple spaces)
                        $aiResponseText = trim(preg_replace('/\s+/', ' ', $aiResponseText));

                        $response['status'] = 'success';
                        $response['message'] = "Respuesta de IA obtenida.";
                        $response['aiResponse'] = $aiResponseText; // Inicialmente solo el texto de la IA

                        // --- NUEVA FUNCIONALIDAD: GENERAR PDF ---
                        $pdfDownloadUrl = null; // Inicializar a null

                        try {
                            $command = new CreateProjectCommand($preguntaTexto);
                            $feedbackProcessor = new FeedbackImplement(new ProjectCreatedResponse());
                            $projectService = new AIProjectService($feedbackProcessor);
                            $responseDto = $projectService->create($command, $aiApiKey, $aiBaseUri, $aiModel);

                            $formatter = new ProjectFormatterService($responseDto);
                            $data = $formatter->getData();

                            $uniqueFileName = 'reporte_' . time() . '_' . uniqid() . '.pdf';
                            $pdfPath = __DIR__ . '/pdfs/' . $uniqueFileName;

                            if (!is_dir(__DIR__ . '/pdfs')) {
                                mkdir(__DIR__ . '/pdfs', 0755, true);
                            }

                            $pdfService = new PdfGeneratorService($data, $pdfPath);
                            $pdfService->generateAndSave(); // Generar y guardar el PDF

                            $pdfDownloadUrl = '/download_pdf.php?file=' . urlencode($uniqueFileName);
                            $response['message'] .= " PDF generado y listo para descargar.";

                        } catch (Exception $pdfEx) {
                            error_log("ERROR al generar PDF en chat.php: " . $pdfEx->getMessage());
                            // No es un error crítico para la respuesta de texto de la IA, pero lo registramos.
                            $response['message'] .= " Sin embargo, hubo un problema al generar el PDF: " . $pdfEx->getMessage();
                            // No establecemos pdfDownloadUrl si hubo error en la generación
                        }

                        // AQUI ES DONDE AGREGAMOS EL ENLACE AL TEXTO DE aiResponse
                        // Si se generó el PDF, agregamos la URL al texto de aiResponse
                        if (!empty($pdfDownloadUrl)) {
                            // Añadimos un salto de línea y un texto descriptivo con la URL.
                            // Si el frontend no interpreta HTML, la URL se mostrará como texto plano.
                            // Si el frontend *sí* interpreta HTML, puedes usar la línea comentada para un enlace clicable.
                            $pagina = "http://localhost/web20250530/ai-client";
                            $response['aiResponse'] = 'Informe completo aquí: <a href="'.$pagina . $pdfDownloadUrl.'">Descargar proyecto</a>';
                            // O si el frontend interpreta HTML:
                            // $response['aiResponse'] .= "\n\n<a href=\"". $pdfDownloadUrl ."\" target=\"_blank\">Descargar informe completo</a>";
                        }

                    } catch (Exception $e) {
                        error_log("ERROR AI en chat.php: " . $e->getMessage());
                        $response['message'] = "Fallo al obtener respuesta de la IA: " . $e->getMessage();
                        $response['aiResponse'] = "Lo siento, no pude obtener una respuesta en este momento.";
                        $response['status'] = 'warning';
                    }
                }
                // --- FIN PROCESAMIENTO AI Y PDF ---

                // Guardar la pregunta del usuario y la respuesta de la IA en la base de datos
                $preguntaRepository = new PreguntaRepository($pdo);
                $createPreguntaUseCase = new CreatePreguntaUseCase($preguntaRepository);
                
                $personaId = $persona ? $persona->getIdPersona() : null;

                if ($personaId) {
                    // Guarda la respuesta ORIGINAL de la IA (sin la URL del PDF añadida para el frontend).
                    // Esto es importante para mantener la base de datos limpia de las URLs de interfaz.
                    $nuevaPregunta = $createPreguntaUseCase->execute(
                        $preguntaTexto,
                        $aiResponseText,
                        $personaId
                    );
                    $response['preguntaId'] = $nuevaPregunta->getIdPregunta();
                } else {
                    error_log("Error: No se pudo obtener el ID de la persona para guardar la pregunta.");
                    $response['message'] = "Error interno: No se pudo asociar la pregunta a una persona.";
                    $response['status'] = 'error';
                }

            } catch (PDOException $e) {
                error_log("ERROR BD en chat.php: " . $e->getMessage());
                $response['message'] = "Error de base de datos: " . $e->getMessage();
            } catch (Exception $e) {
                error_log("ERROR GENERAL in chat.php: " . $e->getMessage());
                $response['message'] = "Ocurrió un error inesperado: " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine();
            }
        }
    } else {
        $response['message'] = "Método no permitido. Solo se aceptan solicitudes POST.";
    }
} catch (\Throwable $e) { // Captura cualquier error fatal o excepción no manejada
    error_log("ERROR FATAL en chat.php (fuera del bloque principal): " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine());
    $response['status'] = 'error';
    $response['message'] = "Error crítico del servidor: " . $e->getMessage();
    $response['aiResponse'] = "Lo siento, un error crítico ha impedido procesar tu solicitud.";
}

// Envía la respuesta JSON
echo json_encode($response);
exit();