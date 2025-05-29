<?php

namespace App\Application\UseCases\Pregunta;

use App\Domain\Entities\Pregunta;
use App\Domain\Repositories\PreguntaRepositoryInterface;

class DeletePreguntaUseCase
{
    private PreguntaRepositoryInterface $preguntaRepository;

    public function __construct(PreguntaRepositoryInterface $preguntaRepository)
    {
        $this->preguntaRepository = $preguntaRepository;
    }

    public function execute(Pregunta $pregunta): void
    {
        $this->preguntaRepository->delete($pregunta);
    }
}