<?php

namespace App\Infrastructure\Persistence\MySQL;

use PDO;
use App\Domain\Entities\Persona;
use App\Domain\Repositories\PersonaRepositoryInterface;

class PersonaRepository implements PersonaRepositoryInterface
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function findById(int $id): ?Persona
    {
        $stmt = $this->connection->prepare("SELECT * FROM personas WHERE id_persona = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return Persona::fromArray($data);
    }

    // Nuevo método 'create' para inserciones
    public function create(Persona $persona): Persona
    {
        $sql = "INSERT INTO personas (`nombre`, `celular`, `fecha_registro`, `activo`) VALUES (:nombre, :celular, :fecha_registro, :activo)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':nombre', $persona->getNombre());
            $stmt->bindValue(':celular', $persona->getCelular());
            if ($persona->getFechaRegistro() === null) {
                $stmt->bindValue(':fecha_registro', null, \PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(':fecha_registro', $persona->getFechaRegistro());
            }
            $stmt->bindValue(':activo', (int)$persona->getActivo(), \PDO::PARAM_INT);
        $stmt->execute();
        $persona->setIdPersona((int)$this->connection->lastInsertId());
        return $persona;
    }

    // Nuevo método 'update' para actualizaciones, retorna booleano
    public function update(Persona $persona): bool
    {
        $sql = "UPDATE personas SET `nombre` = :nombre, `celular` = :celular, `fecha_registro` = :fecha_registro, `activo` = :activo WHERE id_persona = :id_persona";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':nombre', $persona->getNombre());
            $stmt->bindValue(':celular', $persona->getCelular());
            if ($persona->getFechaRegistro() === null) {
                $stmt->bindValue(':fecha_registro', null, \PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(':fecha_registro', $persona->getFechaRegistro());
            }
            $stmt->bindValue(':activo', (int)$persona->getActivo(), \PDO::PARAM_INT);
        $stmt->bindValue(':id_persona', $persona->getIdPersona(), \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0; // CRÍTICO: Devolver booleano
    }

    // Nuevo método 'delete', recibe un int $id y retorna booleano
    public function delete(int $id): bool // El ID es de tipo int
    {
        $stmt = $this->connection->prepare("DELETE FROM personas WHERE id_persona = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT); // bindParam con el ID recibido
        $stmt->execute();
        return $stmt->rowCount() > 0; // CRÍTICO: Devolver booleano
    }

    public function findAll(): array
    {
        $stmt = $this->connection->query("SELECT * FROM personas");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $entities = [];
        foreach ($results as $data) {
            $entities[] = Persona::fromArray($data);
        }
        return $entities;
    }
}
