<?php

namespace App\Application\UseCases\Pregunta;

use App\Domain\Entities\Pregunta;
use App\Domain\Repositories\PreguntaRepositoryInterface;

class ListPreguntasUseCase
{
    private PreguntaRepositoryInterface $preguntaRepository;

    public function __construct(PreguntaRepositoryInterface $preguntaRepository)
    {
        $this->preguntaRepository = $preguntaRepository;
    }

    /**
     * @return Pregunta[]|array Retorna un array de objetos Pregunta.
     */
    public function execute(): array
    {
        return $this->preguntaRepository->findAll();
    }
}