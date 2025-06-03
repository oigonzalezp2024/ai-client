<?php

namespace App\Chatbot\Application\UseCases\Persona;

use App\Chatbot\Domain\Entities\Persona;
use App\Chatbot\Domain\Repositories\PersonaSearchInterface;

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