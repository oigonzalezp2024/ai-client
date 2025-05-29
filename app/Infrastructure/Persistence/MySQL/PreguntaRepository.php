<?php

namespace App\Infrastructure\Persistence\MySQL;

use PDO;
use App\Domain\Entities\Pregunta;
use App\Domain\Repositories\PreguntaRepositoryInterface;

class PreguntaRepository implements PreguntaRepositoryInterface
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function findById(int $id): ?Pregunta
    {
        $stmt = $this->connection->prepare("SELECT * FROM preguntas WHERE id_pregunta = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return Pregunta::fromArray($data);
    }

    public function save(Pregunta $pregunta): Pregunta
    {
        if ($pregunta->getIdPregunta() === null) {
            // Insertar
            $sql = "INSERT INTO preguntas (`su_pregunta`, `persona_id`) VALUES (:su_pregunta, :persona_id)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':su_pregunta', $pregunta->getSuPregunta());
            $stmt->bindValue(':persona_id', $pregunta->getPersonaId());
            $stmt->execute();
            $pregunta->setIdPregunta((int)$this->connection->lastInsertId());
        } else {
            // Actualizar
            $sql = "UPDATE preguntas SET `su_pregunta` = :su_pregunta, `persona_id` = :persona_id WHERE id_pregunta = :id_pregunta";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(':su_pregunta', $pregunta->getSuPregunta());
            $stmt->bindValue(':persona_id', $pregunta->getPersonaId());
            $stmt->bindValue(':id_pregunta', $pregunta->getIdPregunta(), \PDO::PARAM_INT); // Enlazar el ID para la cláusula WHERE como INT
            $stmt->execute();
        }
        return $pregunta;
    }

    public function delete(Pregunta $pregunta): void
    {
        $stmt = $this->connection->prepare("DELETE FROM preguntas WHERE id_pregunta = :id");
        $idToDelete = $pregunta->getIdPregunta();
        $stmt->bindParam(':id', $idToDelete, \PDO::PARAM_INT);
        $stmt->execute();
    }

    public function findAll(): array
    {
        $stmt = $this->connection->query("SELECT * FROM preguntas");
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $entities = [];
        foreach ($results as $data) {
            $entities[] = Pregunta::fromArray($data);
        }
        return $entities;
    }
}