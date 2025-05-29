<?php

namespace App\Application\UseCases\Pregunta;

use App\Domain\Entities\Pregunta;
use App\Domain\Repositories\PreguntaRepositoryInterface;

class UpdatePreguntaUseCase
{
    private PreguntaRepositoryInterface $preguntaRepository;

    public function __construct(PreguntaRepositoryInterface $preguntaRepository)
    {
        $this->preguntaRepository = $preguntaRepository;
    }

    public function execute(Pregunta $pregunta): Pregunta
    {
        return $this->preguntaRepository->save($pregunta);
    }
}