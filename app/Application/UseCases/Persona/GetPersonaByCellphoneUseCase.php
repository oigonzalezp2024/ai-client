<?php

namespace App\Application\UseCases\Persona;

use App\Domain\Entities\Persona;
use App\Domain\Repositories\PersonaSearch\PersonaSearchInterface;

class GetPersonaByCellphoneUseCase
{
    private PersonaSearchInterface $personaRepository;

    public function __construct(PersonaSearchInterface $personaRepository)
    {
        $this->personaRepository = $personaRepository;
    }

    public function execute(string $cellphone): ?Persona
    {
        return $this->personaRepository->findByCellphone($cellphone);
    }
}