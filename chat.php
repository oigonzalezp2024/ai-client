<?php

// Asegúrate de que el autoloader esté disponible
require_once __DIR__ . '/vendor/autoload.php';

// DECLARACIONES 'use' DEBEN IR AQUÍ (corregido previamente)
use Dotenv\Dotenv;
use App\Infrastructure\Persistence\MySQL\DatabaseConnection;
use App\Infrastructure\Persistence\MySQL\PersonaRepository;
use App\Infrastructure\Persistence\MySQL\PersonaSearch\PersonaSearch;
use App\Application\UseCases\Persona\CreatePersonaUseCase;
use App\Application\UseCases\Persona\GetPersonaByCellphoneUseCase;
use App\Infrastructure\Persistence\MySQL\PreguntaRepository;
use App\Application\UseCases\Pregunta\CreatePreguntaUseCase;
use App\Infrastructure\AI\AIPromptProcessor;


// --- IMPORTANTE: Se ha eliminado la configuración de errores y el buffering de salida,
//     para que puedas ver los errores directamente en el navegador.
//     Asegúrate de que 'display_errors = On' en tu php.ini para desarrollo. ---

// Establecer la cabecera Content-Type a application/json inmediatamente
header('Content-Type: application/json');

// Inicializar la respuesta JSON
$response = [
    'status' => 'error',
    'message' => 'Ha ocurrido un error desconocido.',
    'aiResponse' => ''
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
                        $formato = "Responder en español, máximo 249 carácteres:";
                        $promnt = $formato ." ". $preguntaTexto;
                        $aiResponseText = $aiProcessor->getAIResponse($promnt);
                        
                        // Limpia la respuesta de la IA de cualquier tipo de espacio en blanco (newlines, tabs, multiple spaces)
                        // y recorta los espacios al inicio/final.
                        $aiResponseText = trim(preg_replace('/\s+/', ' ', $aiResponseText));

                        $response['status'] = 'success';
                        $response['message'] = "Respuesta de IA obtenida."; // Mensaje simplificado para la API
                        $response['aiResponse'] = $aiResponseText;

                    } catch (Exception $e) {
                        error_log("ERROR AI en chat.php: " . $e->getMessage());
                        $response['message'] = "Fallo al obtener respuesta de la IA: " . $e->getMessage();
                        $response['aiResponse'] = "Lo siento, no pude obtener una respuesta en este momento debido a un error de la IA.";
                        $response['status'] = 'warning'; // O 'error' si lo prefieres
                    }
                }
                // --- FIN PROCESAMIENTO AI ---

                // Guardar la pregunta del usuario y la respuesta de la IA en la base de datos
                // Esto se hace independientemente del éxito de la IA para registrar la interacción
                $preguntaRepository = new PreguntaRepository($pdo);
                $createPreguntaUseCase = new CreatePreguntaUseCase($preguntaRepository);
                
                // Asegúrate de que $persona sea un objeto válido con getIdPersona()
                $personaId = $persona ? $persona->getIdPersona() : null;

                if ($personaId) {
                    $nuevaPregunta = $createPreguntaUseCase->execute(
                        $preguntaTexto, // su_pregunta: la pregunta original del usuario
                        $aiResponseText, // respuesta: la respuesta obtenida de la IA
                        $personaId
                    );
                    // Puedes añadir el ID de la pregunta al response si es útil para el frontend
                    $response['preguntaId'] = $nuevaPregunta->getIdPregunta();
                } else {
                    error_log("Error: No se pudo obtener el ID de la persona para guardar la pregunta.");
                    // Este error puede sobrescribir un éxito de IA, ajustar la lógica si es necesario
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
exit(); // Asegura que no se imprima nada más
