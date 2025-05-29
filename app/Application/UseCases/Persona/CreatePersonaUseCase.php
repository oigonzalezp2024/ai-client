<?php

namespace App\Application\UseCases\Persona;

use App\Domain\Entities\Persona;
use App\Domain\Repositories\PersonaRepositoryInterface;

class CreatePersonaUseCase
{
    private PersonaRepositoryInterface $personaRepository;

    public function __construct(PersonaRepositoryInterface $personaRepository)
    {
        $this->personaRepository = $personaRepository;
    }

    public function execute(string $nombre, string $celular, ?string $fechaRegistro, bool $activo): Persona
    {
        $persona = new Persona();
        $persona->setNombre($nombre);
        $persona->setCelular($celular);
        $persona->setFechaRegistro($fechaRegistro);
        $persona->setActivo($activo);
        
        return $this->personaRepository->save($persona);
    }
}