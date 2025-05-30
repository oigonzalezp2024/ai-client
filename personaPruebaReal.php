<?php
// Carga el autoloader de Composer. Asegúrate de que esta ruta sea correcta.
require_once __DIR__ . '/vendor/autoload.php';

use App\Infrastructure\Persistence\MySQL\DatabaseConnection;
use App\Infrastructure\Persistence\MySQL\PersonaRepository;
use App\Application\UseCases\Persona\CreatePersonaUseCase;
use App\Application\UseCases\Persona\ListPersonasUseCase;
use App\Application\UseCases\Persona\GetPersonaByIdUseCase;
use App\Application\UseCases\Persona\UpdatePersonaUseCase;
use App\Application\UseCases\Persona\DeletePersonaUseCase;
use App\Domain\Entities\Persona;

echo "--- Iniciando Prueba de Casos de Uso para Persona ---\n";

try {
    // 1. Instanciar la conexión a la base de datos
    $dbHost = 'localhost';
    $dbName = 'chatbot'; 
    $dbUser = 'root';        
    $dbPassword = '';        

    $dbConnection = new DatabaseConnection($dbHost, $dbName, $dbUser, $dbPassword);
    $pdo = $dbConnection->connect();

    echo "Conexión a la base de datos establecida exitosamente.\n";

    // 2. Instanciar el repositorio concreto, inyectando la conexión PDO
    $PersonaRepository = new PersonaRepository($pdo);

    // --- PRUEBA DE CREATE ---
    echo "\n--- Probando CreatePersonaUseCase ---\n";
    $createUseCase = new CreatePersonaUseCase($PersonaRepository);

    // Los parámetros deben coincidir con la definición de Persona: 'nombre', 'fecha_registro', 'activo'
    $nuevoPersona = $createUseCase->execute(
        'Mi Persona S.A. de CV', // 1. nombre (string)
        '3228858439', // 1. nombre (string)
        '2024-05-28',              // 2. fecha_registro (string 'YYYY-MM-DD' o null)
        true                       // 3. activo (bool)
    );
    echo "Persona creado exitosamente con ID: " . $nuevoPersona->getIdPersona() . " (Nombre: " . $nuevoPersona->getNombre() . ")\n";

    // --- PRUEBA DE GET BY ID ---
    echo "\n--- Probando GetPersonaByIdUseCase ---\n";
    $getByIdUseCase = new GetPersonaByIdUseCase($PersonaRepository);
    $PersonaEncontrado = $getByIdUseCase->execute($nuevoPersona->getIdPersona());

    if ($PersonaEncontrado) {
        echo "Persona encontrado por ID: " . $PersonaEncontrado->getNombre() . " (Activo: " . ($PersonaEncontrado->getActivo() ? 'Sí' : 'No') . ")\n";
    } else {
        echo "ERROR: Persona con ID " . $nuevoPersona->getIdPersona() . " no encontrado.\n";
    }

    // --- PRUEBA DE UPDATE ---
    echo "\n--- Probando UpdatePersonaUseCase ---\n";
    if ($PersonaEncontrado) {
        $updateUseCase = new UpdatePersonaUseCase($PersonaRepository);
        $PersonaEncontrado->setNombre('Persona Actualizado S.A.');
        $PersonaEncontrado->setActivo(false); // Cambiar estado a inactivo

        $PersonaActualizado = $updateUseCase->execute($PersonaEncontrado);
        echo "Persona actualizado exitosamente a: " . $PersonaActualizado->getNombre() . " (Activo: " . ($PersonaActualizado->getActivo() ? 'Sí' : 'No') . ")\n";
    } else {
        echo "No se puede actualizar: Persona no encontrado.\n";
    }

    // --- PRUEBA DE LIST ALL (FIND ALL) ---
    echo "\n--- Probando ListPersonasUseCase (findAll) ---\n";
    $listUseCase = new ListPersonasUseCase($PersonaRepository);
    $allPersonaes = $listUseCase->execute();

    if (empty($allPersonaes)) {
        echo "No se encontraron Personaes.\n";
    } else {
        echo "Personaes en la base de datos:\n";
        foreach ($allPersonaes as $prov) {
            echo "- ID: " . $prov->getIdPersona() . ", Nombre: " . $prov->getNombre() . ", Activo: " . ($prov->getActivo() ? 'Sí' : 'No') . "\n";
        }
    }

    // --- PRUEBA DE DELETE ---
    echo "\n--- Probando DeletePersonaUseCase ---\n";
    if ($nuevoPersona && $nuevoPersona->getIdPersona() !== null) {
        $deleteUseCase = new DeletePersonaUseCase($PersonaRepository);
        $deleteUseCase->execute($nuevoPersona);
        echo "Persona con ID " . $nuevoPersona->getIdPersona() . " eliminado exitosamente.\n";

        // Verificar si fue eliminado
        $PersonaEliminado = $getByIdUseCase->execute($nuevoPersona->getIdPersona());
        if (!$PersonaEliminado) {
            echo "Verificación: Persona con ID " . $nuevoPersona->getIdPersona() . " no encontrado después de eliminar (Correcto).\n";
        } else {
            echo "ERROR: Persona con ID " . $nuevoPersona->getIdPersona() . " todavía existe después de eliminar.\n";
        }
    } else {
        echo "No se puede eliminar: No hay un Persona válido para eliminar.\n";
    }

} catch (PDOException $e) {
    echo "\nERROR DE BASE DE DATOS: " . $e->getMessage() . " (Código: " . $e->getCode() . ")\n";
    echo "Asegúrate de que:\n";
    echo "1. El servidor MySQL está funcionando.\n";
    echo "2. La base de datos 'mydatabase' exista y tenga la tabla 'Personas'.\n";
    echo "3. Las credenciales de usuario ('{$dbUser}' y contraseña) sean correctas y tengan permisos.\n";
} catch (Exception $e) {
    echo "\nERROR GENERAL INESPERADO: " . $e->getMessage() . " en " . $e->getFile() . " línea " . $e->getLine() . "\n";
}

echo "\n--- Fin de la Prueba ---\n";