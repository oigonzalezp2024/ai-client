<?php

namespace App\Domain\Entities;

use InvalidArgumentException;

class Pregunta
{
private ?int $idPregunta;
    private string $suPregunta;
    private ?string $respuesta;
    private int $personaId;

    public function __construct()
    {
$this->idPregunta = null;
        $this->suPregunta = '';
        $this->respuesta = null;
        $this->personaId = 0;
    }

public function getIdPregunta(): ?int
    {
        return $this->idPregunta;
    }

    public function getSuPregunta(): string
    {
        return $this->suPregunta;
    }

    public function getRespuesta(): ?string
    {
        return $this->respuesta;
    }

    public function getPersonaId(): int
    {
        return $this->personaId;
    }

public function setIdPregunta(?int $idPregunta): void
    {
        $this->idPregunta = $idPregunta;
    }

    public function setSuPregunta(string $suPregunta): void
    {
        $this->suPregunta = $suPregunta;
    }

    public function setRespuesta(?string $respuesta): void
    {
        $this->respuesta = $respuesta;
    }

    public function setPersonaId(int $personaId): void
    {
        $this->personaId = $personaId;
    }

    /**
     * Crea una instancia de Pregunta a partir de un array de datos (ej. desde una base de datos).
     *
     * @param array $data Array asociativo con los datos de la entidad.
     * @return self Una nueva instancia de Pregunta.
     * @throws InvalidArgumentException Si faltan campos requeridos o los tipos no coinciden.
     */
    public static function fromArray(array $data): self
    {
$entity = new self();
        $entity->setIdPregunta(isset($data['id_pregunta']) ? (int)$data['id_pregunta'] : null);
        $entity->setSuPregunta(isset($data['su_pregunta']) ? (string)$data['su_pregunta'] : '');
        $entity->setRespuesta(isset($data['respuesta']) ? (string)$data['respuesta'] : null);
        $entity->setPersonaId(isset($data['persona_id']) ? (int)$data['persona_id'] : 0);
        return $entity;
    }

}