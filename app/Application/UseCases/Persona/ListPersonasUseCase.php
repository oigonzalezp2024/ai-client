<?php

namespace App\Application\UseCases\Persona;

use App\Domain\Entities\Persona;
use App\Domain\Repositories\PersonaRepositoryInterface;

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