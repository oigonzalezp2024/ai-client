<?php

namespace App\Application\UseCases\Persona;

use App\Domain\Entities\Persona;
use App\Domain\Repositories\PersonaRepositoryInterface;

class UpdatePersonaUseCase
{
    private PersonaRepositoryInterface $personaRepository;

    public function __construct(PersonaRepositoryInterface $personaRepository)
    {
        $this->personaRepository = $personaRepository;
    }

    public function execute(Persona $persona): Persona
    {
        return $this->personaRepository->save($persona);
    }
}