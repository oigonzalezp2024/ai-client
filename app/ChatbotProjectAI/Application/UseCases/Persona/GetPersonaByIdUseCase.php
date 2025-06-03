<?php

namespace App\Chatbot\Application\UseCases\Persona;

use App\Chatbot\Domain\Entities\Persona;
use App\Chatbot\Domain\Repositories\PersonaRepositoryInterface;

class GetPersonaByIdUseCase
{
    private PersonaRepositoryInterface $personaRepository;

    public function __construct(PersonaRepositoryInterface $personaRepository)
    {
        $this->personaRepository = $personaRepository;
    }

    public function execute(int $id): ?Persona
    {
        return $this->personaRepository->findById($id);
    }
}