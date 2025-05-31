<?php

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;
use App\Infrastructure\Persistence\MySQL\DatabaseConnection;
use App\Infrastructure\Persistence\MySQL\PersonaRepository;
use App\Application\UseCases\Persona\CreatePersonaUseCase;
use App\Application\UseCases\Persona\ListPersonasUseCase;
use App\Application\UseCases\Persona\GetPersonaByIdUseCase;
use App\Application\UseCases\Persona\UpdatePersonaUseCase;
use App\Application\UseCases\Persona\DeletePersonaUseCase;
use App\Domain\Entities\Persona; // Asegúrate de que esta ruta sea correcta
use PDO;
use PDOException;
use Exception; // Necesario para los expects de excepciones

class PersonaUseCaseIntegrationTest extends TestCase
{
    private ?PDO $pdo = null; // Propiedad PDO puede ser nula
    private PersonaRepository $personaRepository;

    /**
     * Este método se ejecuta ANTES de cada método de test.
     * Aquí preparamos el entorno para cada test.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Obtener variables de entorno o usar valores por defecto para la DB de prueba
        $dbHost = $_ENV['DB_HOST'] ?? 'localhost';
        $dbName = $_ENV['DB_NAME'] ?? 'chatbot_test'; // ¡Usar una DB de test dedicada!
        $dbUser = $_ENV['DB_USER'] ?? 'root';
        $dbPassword = $_ENV['DB_PASSWORD'] ?? '';

        try {
            $dbConnection = new DatabaseConnection($dbHost, $dbName, $dbUser, $dbPassword);
            $this->pdo = $dbConnection->connect();
            // Aseguramos que la conexión se realice en modo de excepciones para PDO
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // 1. Deshabilitar la verificación de claves foráneas
            $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
            
            // 2. Limpiar las tablas en el ORDEN CORRECTO:
            //    Primero, la tabla que tiene la clave foránea (la "hija")
            $this->pdo->exec("TRUNCATE TABLE preguntas;"); 
            //    Luego, la tabla a la que se hace referencia (la "padre")
            $this->pdo->exec("TRUNCATE TABLE personas;"); 

            // 3. Habilitar la verificación de claves foráneas
            $this->pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

        } catch (PDOException $e) {
            $this->fail("Error de conexión a la base de datos de prueba: " . $e->getMessage() . 
                        "\nAsegúrate de que la DB '{$dbName}' exista y las credenciales sean correctas.");
        } catch (Exception $e) { // Capturar Exception general también
            $this->fail("Error inesperado en setUp: " . $e->getMessage());
        }

        // Instanciar el repositorio concreto con la conexión PDO
        $this->personaRepository = new PersonaRepository($this->pdo);
    }

    /**
     * Este método se ejecuta DESPUÉS de cada método de test.
     * Aquí limpiamos el entorno, como cerrar la conexión PDO.
     */
    protected function tearDown(): void
    {
        $this->pdo = null; // Liberar la conexión PDO
        parent::tearDown();
    }

    /**
     * Prueba la creación de una Persona a través del caso de uso.
     */
    public function testCreatePersonaUseCase(): void
    {
        $createUseCase = new CreatePersonaUseCase($this->personaRepository);

        $nombre = 'Nueva Persona de Prueba';
        $celular = '3001234567'; // Usamos 'celular' como nombre de columna real
        $fechaRegistro = '2025-05-29';
        $activo = true;

        // El método execute del CreatePersonaUseCase debe aceptar estos parámetros
        $nuevoPersona = $createUseCase->execute($nombre, $celular, $fechaRegistro, $activo);

        $this->assertNotNull($nuevoPersona, "La persona no debería ser nula después de la creación.");
        $this->assertNotNull($nuevoPersona->getIdPersona(), "El ID de la persona no debería ser nulo.");
        $this->assertEquals($nombre, $nuevoPersona->getNombre(), "El nombre no coincide.");
        $this->assertTrue($nuevoPersona->getActivo(), "La persona debería estar activa.");

        // Verificar directamente en la base de datos
        // Asegúrate de que los nombres de las columnas aquí coincidan con tu DB
        $stmt = $this->pdo->prepare("SELECT id_persona, nombre, celular, fecha_registro, activo FROM personas WHERE id_persona = ?");
        $stmt->execute([$nuevoPersona->getIdPersona()]);
        $dbPersona = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertIsArray($dbPersona, "La persona no fue encontrada en la base de datos.");
        $this->assertEquals($nombre, $dbPersona['nombre'], "El nombre en la DB no coincide.");
        $this->assertEquals($celular, $dbPersona['celular'], "El celular en la DB no coincide.");
        $this->assertEquals($activo, (bool)$dbPersona['activo'], "El estado 'activo' en la DB no coincide.");
    }

    /**
     * Prueba la obtención de una Persona por su ID.
     */
    public function testGetPersonaByIdUseCase(): void
    {
        // Primero, creamos una persona para poder buscarla
        $createUseCase = new CreatePersonaUseCase($this->personaRepository);
        $nombreOriginal = 'Persona para Buscar';
        $celularOriginal = '3109876543';
        $personaCreada = $createUseCase->execute($nombreOriginal, $celularOriginal, '2025-05-29', true);

        $this->assertNotNull($personaCreada->getIdPersona(), "Fallo al crear persona para la prueba de búsqueda.");

        // Ejecutamos el caso de uso para obtener la persona
        $getByIdUseCase = new GetPersonaByIdUseCase($this->personaRepository);
        $personaEncontrada = $getByIdUseCase->execute($personaCreada->getIdPersona());

        $this->assertNotNull($personaEncontrada, "La persona no debería ser nula al buscar por ID.");
        $this->assertEquals($personaCreada->getIdPersona(), $personaEncontrada->getIdPersona(), "El ID de la persona encontrada no coincide.");
        $this->assertEquals($nombreOriginal, $personaEncontrada->getNombre(), "El nombre de la persona encontrada no coincide.");
        $this->assertEquals($celularOriginal, $personaEncontrada->getCelular(), "El celular de la persona encontrada no coincide."); // Verificar celular
    }

    /**
     * Prueba la actualización de una Persona existente.
     */
    public function testUpdatePersonaUseCase(): void
    {
        // Creamos una persona para actualizar
        $createUseCase = new CreatePersonaUseCase($this->personaRepository);
        $personaOriginal = $createUseCase->execute('Persona a Actualizar', '3011112222', '2025-05-29', true);

        $this->assertNotNull($personaOriginal->getIdPersona(), "Fallo al crear persona para la prueba de actualización.");

        // Preparamos los nuevos datos
        $updateUseCase = new UpdatePersonaUseCase($this->personaRepository);
        $nuevoNombre = 'Persona Actualizada Test';
        $nuevoCelular = '3005554433'; // Nuevo valor para celular
        $nuevoActivo = false;

        $personaOriginal->setNombre($nuevoNombre);
        $personaOriginal->setCelular($nuevoCelular); // Asegúrate de que el método setCelular exista en Persona
        $personaOriginal->setActivo($nuevoActivo);

        // Ejecutamos el caso de uso de actualización
        $personaActualizada = $updateUseCase->execute($personaOriginal);

        $this->assertNotNull($personaActualizada, "La persona actualizada no debería ser nula.");
        $this->assertEquals($personaOriginal->getIdPersona(), $personaActualizada->getIdPersona(), "El ID de la persona actualizada no coincide.");
        $this->assertEquals($nuevoNombre, $personaActualizada->getNombre(), "El nombre de la persona actualizada no coincide.");
        $this->assertEquals($nuevoCelular, $personaActualizada->getCelular(), "El celular de la persona actualizada no coincide."); // Verificar celular
        $this->assertFalse($personaActualizada->getActivo(), "La persona debería estar inactiva después de la actualización.");

        // Verificamos que los cambios se reflejen en la base de datos
        $getByIdUseCase = new GetPersonaByIdUseCase($this->personaRepository);
        $personaEnDB = $getByIdUseCase->execute($personaActualizada->getIdPersona());
        $this->assertEquals($nuevoNombre, $personaEnDB->getNombre(), "El nombre en la DB no coincide después de la actualización.");
        $this->assertEquals($nuevoCelular, $personaEnDB->getCelular(), "El celular en la DB no coincide después de la actualización.");
        $this->assertFalse($personaEnDB->getActivo(), "El estado 'activo' en la DB no coincide después de la actualización.");
    }

    /**
     * Prueba la listado de todas las Personas.
     */
    public function testListPersonasUseCase(): void
    {
        // Creamos varias personas para el listado
        $createUseCase = new CreatePersonaUseCase($this->personaRepository);
        $createUseCase->execute('Persona Uno', '3001000001', '2025-05-29', true);
        $createUseCase->execute('Persona Dos', '3001000002', '2025-05-29', false);
        $createUseCase->execute('Persona Tres', '3001000003', '2025-05-29', true);

        // Ejecutamos el caso de uso de listado
        $listUseCase = new ListPersonasUseCase($this->personaRepository);
        $allPersonas = $listUseCase->execute();

        $this->assertIsArray($allPersonas, "El resultado debería ser un array.");
        $this->assertCount(3, $allPersonas, "Deberíamos encontrar 3 personas.");

        foreach ($allPersonas as $persona) {
            $this->assertInstanceOf(Persona::class, $persona, "Cada elemento debería ser una instancia de Persona.");
        }

        $nombres = array_map(fn($p) => $p->getNombre(), $allPersonas);
        $this->assertContains('Persona Uno', $nombres);
        $this->assertContains('Persona Dos', $nombres);
        $this->assertContains('Persona Tres', $nombres);
    }

    /**
     * Prueba la eliminación de una Persona.
     */
    public function testDeletePersonaUseCase(): void
    {
        // Creamos una persona para eliminar
        $createUseCase = new CreatePersonaUseCase($this->personaRepository);
        $personaAEliminar = $createUseCase->execute('Persona a Eliminar', '3009998877', '2025-05-29', true);

        $this->assertNotNull($personaAEliminar->getIdPersona(), "Fallo al crear persona para la prueba de eliminación.");

        // Ejecutamos el caso de uso de eliminación
        $deleteUseCase = new DeletePersonaUseCase($this->personaRepository);
        $deleteUseCase->execute($personaAEliminar);

        // Intentamos buscar la persona eliminada, debería ser nula
        $getByIdUseCase = new GetPersonaByIdUseCase($this->personaRepository);
        $personaEliminada = $getByIdUseCase->execute($personaAEliminar->getIdPersona());

        $this->assertNull($personaEliminada, "La persona debería ser nula después de ser eliminada.");

        // Verificamos que no exista en la base de datos
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM personas WHERE id_persona = ?");
        $stmt->execute([$personaAEliminar->getIdPersona()]);
        $this->assertEquals(0, $stmt->fetchColumn(), "La persona no debería existir en la base de datos.");
    }

    /**
     * Prueba que se lance una excepción al intentar actualizar una Persona inexistente.
     */
    public function testUpdateNonExistentPersonaThrowsException(): void
    {
        // Esperamos que se lance una Exception con este mensaje
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("No se pudo actualizar la Persona."); // Mensaje exacto que tu UseCase debe lanzar

        $updateUseCase = new UpdatePersonaUseCase($this->personaRepository);
        $personaInexistente = new Persona();
        $personaInexistente->setIdPersona(999999); // ID que seguramente no existe
        $personaInexistente->setNombre("Nombre Inexistente");
        $personaInexistente->setCelular("1234567890"); // Asegúrate de que el método setCelular exista en Persona
        $personaInexistente->setFechaRegistro("2024-01-01");
        $personaInexistente->setActivo(true);

        $updateUseCase->execute($personaInexistente);
    }

    /**
     * Prueba que se lance una excepción al intentar eliminar una Persona inexistente.
     */
    public function testDeleteNonExistentPersonaThrowsException(): void
    {
        // Esperamos que se lance una Exception con este mensaje
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("No se pudo eliminar la Persona."); // Mensaje exacto que tu UseCase debe lanzar

        $deleteUseCase = new DeletePersonaUseCase($this->personaRepository);
        $personaInexistente = new Persona();
        $personaInexistente->setIdPersona(888888); // ID que seguramente no existe
        $personaInexistente->setNombre("Nombre Inexistente");
        $personaInexistente->setCelular("0987654321"); // Asegúrate de que el método setCelular exista en Persona
        $personaInexistente->setFechaRegistro("2024-01-01");
        $personaInexistente->setActivo(true);

        $deleteUseCase->execute($personaInexistente);
    }
}
