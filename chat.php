<?php

// C:\xampp\htdocs\web20250528\ai-client_chatbot_2\chat.php

/**
 * Script de procesamiento para los datos del formulario de Persona y Pregunta.
 * Recibe datos POST, utiliza casos de uso, asigna la fecha internamente y redirige con mensajes de estado.
 *
 * @package Web20250528
 * @subpackage AiClientChatbot
 * @author Oscar Gonzalez <oscar.gonzalez@example.com>
 * @version 1.0.2
 * @since 2024-05-28
 */

// Asegúrate de que el autoloader esté disponible
require_once __DIR__ . '/vendor/autoload.php';

use App\Infrastructure\Persistence\MySQL\DatabaseConnection;
use App\Infrastructure\Persistence\MySQL\PersonaRepository;
use App\Infrastructure\Persistence\MySQL\PersonaSearch\PersonaSearch;
use App\Application\UseCases\Persona\CreatePersonaUseCase;
use App\Application\UseCases\Persona\GetPersonaByCellphoneUseCase;
use App\Infrastructure\Persistence\MySQL\PreguntaRepository;
use App\Application\UseCases\Pregunta\CreatePreguntaUseCase;

// --- Configuración de la Base de Datos ---
$dbConfig = [
    'host' => 'localhost',
    'name' => 'chatbot', // Asegúrate de que este sea el nombre correcto de tu base de datos
    'user' => 'root',
    'password' => '',
];

$redirectParams = []; // Para construir los parámetros de la URL de redirección
$status = 'error';
$message = 'Ha ocurrido un error desconocido.';

// Solo procesa si la solicitud es POST (viene del formulario)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Obtener y sanear los datos del formulario
    // IMPORTANTE: Se usa FILTER_UNSAFE_RAW para evitar que los caracteres especiales
    // como tildes y ñ se conviertan a entidades HTML antes de ser guardados en la DB.
    // La seguridad contra inyección SQL la proporcionan los Prepared Statements de PDO.
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_UNSAFE_RAW); // Cambio aquí
    $celular = filter_input(INPUT_POST, 'celular', FILTER_UNSAFE_RAW); // Cambio aquí
    $preguntaTexto = filter_input(INPUT_POST, 'pregunta', FILTER_UNSAFE_RAW); // Cambio aquí

    // La fecha se genera internamente al momento del procesamiento
    $fecha = date('Y-m-d'); // Obtiene la fecha actual en formato AAAA-MM-DD

    // Rellenar parámetros para el formulario en caso de error o para mantener datos
    $redirectParams['nombre'] = $nombre;
    $redirectParams['celular'] = $celular;
    $redirectParams['pregunta'] = $preguntaTexto;

    // 2. Validación básica de datos
    if (!$nombre || !$celular || !$preguntaTexto) {
        $message = "Los campos ID Persona, Nombre, Celular y Pregunta son obligatorios. Por favor, complételos.";
    } else {
        try {
            // 3. Conexión a la Base de Datos
            // Se usa la clase DatabaseConnection que ya configuramos con utf8mb4
            $dbConnection = new DatabaseConnection(
                $dbConfig['host'],
                $dbConfig['name'],
                $dbConfig['user'],
                $dbConfig['password']
            );
            $pdo = $dbConnection->connect();
            // PDO::ATTR_ERRMODE, PDO::ATTR_EMULATE_PREPARES y charset=utf8mb4 ya se configuran en DatabaseConnection

            // 4. Lógica de Negocio (Usando Casos de Uso)
            $personaSearch = new PersonaSearch($pdo);
            $personaRepository = new PersonaRepository($pdo);
            $getPersonaByCellphoneUseCase = new GetPersonaByCellphoneUseCase($personaSearch);
            $createPersonaUseCase = new CreatePersonaUseCase($personaSearch);

            $persona = $getPersonaByCellphoneUseCase->execute($celular);

            $operationMessage = '';
            if (!$persona) {
                // Crear nueva persona usando la fecha generada internamente
                $persona = $createPersonaUseCase->execute(
                    $nombre,
                    $celular,
                    $fecha,
                    true // Asumiendo que 'true' es para 'isActive'
                );
                $operationMessage .= "Persona con ID {$persona->getIdPersona()} creada exitosamente. ";
            } else {
                $operationMessage .= "Persona con ID {$persona->getIdPersona()} ('{$persona->getNombre()}') encontrada. ";
                // Si la persona ya existe, podrías considerar actualizar sus datos aquí
                // si el formulario lo permite, o simplemente continuar.
            }

            $preguntaRepository = new PreguntaRepository($pdo);
            $createPreguntaUseCase = new CreatePreguntaUseCase($preguntaRepository);

            $nuevaPregunta = $createPreguntaUseCase->execute(
                $preguntaTexto,
                $persona->getIdPersona()
            );

            // Importante: Aquí el mensaje usa los strings puros (UTF-8), no los escapados.
            $operationMessage .= "Pregunta '" . $nuevaPregunta->getSuPregunta() . "' asociada a la Persona ID {$persona->getIdPersona()} (Pregunta ID: {$nuevaPregunta->getIdPregunta()}).";
            $status = 'success';
            $message = $operationMessage;

        } catch (PDOException $e) {
            error_log("ERROR BD en chat.php: " . $e->getMessage());
            $message = "Error de base de datos: " . $e->getMessage() . ". Por favor, verifica la conexión, los permisos y la codificación UTF-8 en la base de datos y en los archivos PHP.";
        } catch (Exception $e) {
            error_log("ERROR GENERAL en chat.php: " . $e->getMessage());
            $message = "Ocurrió un error inesperado: " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine();
        }
    }
} else {
    $message = "Acceso directo no permitido al procesador de formulario. Por favor, use el formulario.";
    $redirectParams = [];
}

// Preparar parámetros de URL para la redirección
$redirectParams['status'] = $status;
$redirectParams['message'] = urlencode($message); // El mensaje se codifica para la URL

$queryString = http_build_query($redirectParams);
header('Location: index.php?' . $queryString);
exit();
