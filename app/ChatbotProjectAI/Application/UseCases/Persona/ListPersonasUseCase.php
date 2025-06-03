<?php

namespace App\Chatbot\Application\UseCases\Persona;

use App\Chatbot\Domain\Entities\Persona;
use App\Chatbot\Domain\Repositories\PersonaRepositoryInterface;

class ListPersonasUseCase
{
    private PersonaRepositoryInterface $personaRepository;

    public function __construct(PersonaRepositoryInterface $personaRepository)
    {
        $this->personaRepository = $personaRepository;
    }

    /**
     * @return Persona[]|array Retorna un array de objetos Persona.
     */
    public function execute(): array
    {
        return $this->personaRepository->findAll();
    }
}