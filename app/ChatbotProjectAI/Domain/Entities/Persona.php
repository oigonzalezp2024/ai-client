<?php

namespace App\Chatbot\Domain\Entities;

use InvalidArgumentException;

class Persona
{
private ?int $idPersona;
    private string $nombre;
    private string $celular;
    private ?string $fechaRegistro;
    private bool $activo;

    public function __construct()
    {
$this->idPersona = null;
        $this->nombre = '';
        $this->celular = '';
        $this->fechaRegistro = null;
        $this->activo = false;
    }

public function getIdPersona(): ?int
    {
        return $this->idPersona;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getCelular(): string
    {
        return $this->celular;
    }

    public function getFechaRegistro(): ?string
    {
        return $this->fechaRegistro;
    }

    public function getActivo(): bool
    {
        return $this->activo;
    }

public function setIdPersona(?int $idPersona): void
    {
        $this->idPersona = $idPersona;
    }

    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }

    public function setCelular(string $celular): void
    {
        $this->celular = $celular;
    }

    public function setFechaRegistro(?string $fechaRegistro): void
    {
        $this->fechaRegistro = $fechaRegistro;
    }

    public function setActivo(bool $activo): void
    {
        $this->activo = $activo;
    }

    /**
     * Crea una instancia de Persona a partir de un array de datos (ej. desde una base de datos).
     *
     * @param array $data Array asociativo con los datos de la entidad.
     * @return self Una nueva instancia de Persona.
     * @throws InvalidArgumentException Si faltan campos requeridos o los tipos no coinciden.
     */
    public static function fromArray(array $data): self
    {
$entity = new self();
        $entity->setIdPersona(isset($data['id_persona']) ? (int)$data['id_persona'] : null);
        $entity->setNombre(isset($data['nombre']) ? (string)$data['nombre'] : '');
        $entity->setCelular(isset($data['celular']) ? (string)$data['celular'] : '');
        $entity->setFechaRegistro(isset($data['fecha_registro']) ? (string)$data['fecha_registro'] : null);
        $entity->setActivo(isset($data['activo']) ? (bool)$data['activo'] : false);
        return $entity;
    }

}