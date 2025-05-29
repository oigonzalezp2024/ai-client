<?php

namespace App\Application\UseCases\Pregunta;

use App\Domain\Entities\Pregunta;
use App\Domain\Repositories\PreguntaRepositoryInterface;

class CreatePreguntaUseCase
{
    private PreguntaRepositoryInterface $preguntaRepository;

    public function __construct(PreguntaRepositoryInterface $preguntaRepository)
    {
        $this->preguntaRepository = $preguntaRepository;
    }

    public function execute(string $suPregunta, int $personaId): Pregunta
    {
        $pregunta = new Pregunta();
        $pregunta->setSuPregunta($suPregunta);
        $pregunta->setPersonaId($personaId);
        
        return $this->preguntaRepository->save($pregunta);
    }
}