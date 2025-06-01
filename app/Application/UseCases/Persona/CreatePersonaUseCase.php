<?php

namespace App\Application\UseCases\Persona;

use App\Domain\Entities\Persona;
use App\Domain\Repositories\PersonaRepositoryInterface;
// Puedes necesitar importar una clase de excepción si el repositorio puede lanzarla,
// o si el caso de uso la lanza por sí mismo.
// use Exception; 

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
        
        // ¡CAMBIO CLAVE AQUÍ! Usar create()
        return $this->personaRepository->create($persona);
    }
}