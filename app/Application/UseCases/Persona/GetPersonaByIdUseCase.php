<?php

namespace App\Application\UseCases\Persona;

use App\Domain\Entities\Persona;
use App\Domain\Repositories\PersonaRepositoryInterface;

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