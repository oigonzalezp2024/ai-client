<?php

namespace App\Application\UseCases\Persona;

use App\Domain\Entities\Persona; // Podría necesitarse para el tipo de parámetro
use App\Domain\Repositories\PersonaRepositoryInterface;
use Exception; // Necesario para lanzar la excepción

class DeletePersonaUseCase
{
    private PersonaRepositoryInterface $personaRepository;

    public function __construct(PersonaRepositoryInterface $personaRepository)
    {
        $this->personaRepository = $personaRepository;
    }

    public function execute(Persona $persona): void // El Use Case recibe la entidad completa
    {
        // ¡CAMBIO CLAVE AQUÍ! Pasar el ID al método delete() del repositorio y manejar el retorno booleano
        $deleted = $this->personaRepository->delete($persona->getIdPersona());

        if (!$deleted) {
            throw new Exception("No se pudo eliminar la Persona.");
        }
    }
}