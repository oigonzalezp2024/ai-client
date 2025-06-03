<?php

namespace App\Chatbot\Infrastructure\Persistence\MySQL;

use PDO;
use App\Chatbot\Domain\Entities\Persona;
use App\Chatbot\Domain\Repositories\PersonaSearchInterface;
use App\Chatbot\Infrastructure\Persistence\MySQL\PersonaRepository;

class PersonaSearch extends PersonaRepository implements PersonaSearchInterface
{
    // La propiedad $connection aquí podría ser redundante si PersonaRepository ya la almacena.
    // Considera si necesitas almacenarla dos veces o si puedes accederla desde la clase padre.
    private PDO $connection; 
    
    public function __construct(PDO $connection)
    {
        parent::__construct($connection);
        $this->connection = $connection; 
    }

    public function findByCellphone(string $cellphone): ?Persona
    {
        // Asegúrate de que $this->connection (o la conexión a través de la clase padre) esté disponible aquí.
        // Si PersonaRepository hace que la conexión sea 'protected', podrías usarla directamente sin duplicarla.
        $sql = "SELECT `id_persona`, `nombre`, `celular`, `fecha_registro`, `activo` FROM personas WHERE `celular` = :celular";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindParam(':celular', $cellphone);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            return null;
        }
        return Persona::fromArray($data);
    }

}
