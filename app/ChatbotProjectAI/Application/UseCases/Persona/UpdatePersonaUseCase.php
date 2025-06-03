<?php

namespace App\Chatbot\Application\UseCases\Persona;

use App\Chatbot\Domain\Entities\Persona;
use App\Chatbot\Domain\Repositories\PersonaRepositoryInterface;
use Exception; // Necesario para lanzar la excepción

class UpdatePersonaUseCase
{
    private PersonaRepositoryInterface $personaRepository;

    public function __construct(PersonaRepositoryInterface $personaRepository)
    {
        $this->personaRepository = $personaRepository;
    }

    public function execute(Persona $persona): Persona
    {
        // ¡CAMBIO CLAVE AQUÍ! Usar update() y manejar el retorno booleano
        $updated = $this->personaRepository->update($persona);

        if (!$updated) {
            throw new Exception("No se pudo actualizar la Persona.");
        }
        return $persona; // Retorna la entidad actualizada si todo fue bien
    }
}