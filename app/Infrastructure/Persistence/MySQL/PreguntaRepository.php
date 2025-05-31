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

    // Nuevo método 'create' para inserciones
    public function create(Pregunta $pregunta): Pregunta
    {
        $sql = "INSERT INTO preguntas (`su_pregunta`, `respuesta`, `persona_id`) VALUES (:su_pregunta, :respuesta, :persona_id)";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':su_pregunta', $pregunta->getSuPregunta());
            if ($pregunta->getRespuesta() === null) {
                $stmt->bindValue(':respuesta', null, \PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(':respuesta', $pregunta->getRespuesta());
            }
            $stmt->bindValue(':persona_id', $pregunta->getPersonaId());
        $stmt->execute();
        $pregunta->setIdPregunta((int)$this->connection->lastInsertId());
        return $pregunta;
    }

    // Nuevo método 'update' para actualizaciones, retorna booleano
    public function update(Pregunta $pregunta): bool
    {
        $sql = "UPDATE preguntas SET `su_pregunta` = :su_pregunta, `respuesta` = :respuesta, `persona_id` = :persona_id WHERE id_pregunta = :id_pregunta";
        $stmt = $this->connection->prepare($sql);
        $stmt->bindValue(':su_pregunta', $pregunta->getSuPregunta());
            if ($pregunta->getRespuesta() === null) {
                $stmt->bindValue(':respuesta', null, \PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(':respuesta', $pregunta->getRespuesta());
            }
            $stmt->bindValue(':persona_id', $pregunta->getPersonaId());
        $stmt->bindValue(':id_pregunta', $pregunta->getIdPregunta(), \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0; // CRÍTICO: Devolver booleano
    }

    // Nuevo método 'delete', recibe un int $id y retorna booleano
    public function delete(int $id): bool // El ID es de tipo int
    {
        $stmt = $this->connection->prepare("DELETE FROM preguntas WHERE id_pregunta = :id");
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT); // bindParam con el ID recibido
        $stmt->execute();
        return $stmt->rowCount() > 0; // CRÍTICO: Devolver booleano
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
