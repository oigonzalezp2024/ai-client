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

    public function save(Persona $persona): Persona
    {
        if ($persona->getIdPersona() === null) {
            // Insertar
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
        } else {
            // Actualizar
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
            $stmt->bindValue(':id_persona', $persona->getIdPersona(), \PDO::PARAM_INT); // Enlazar el ID para la cláusula WHERE como INT
            $stmt->execute();
        }
        return $persona;
    }

    public function delete(Persona $persona): void
    {
        $stmt = $this->connection->prepare("DELETE FROM personas WHERE id_persona = :id");
        $idToDelete = $persona->getIdPersona();
        $stmt->bindParam(':id', $idToDelete, \PDO::PARAM_INT);
        $stmt->execute();
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