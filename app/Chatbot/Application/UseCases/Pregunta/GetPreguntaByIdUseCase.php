<?php

namespace App\Chatbot\Application\UseCases\Pregunta;

use App\Chatbot\Domain\Entities\Pregunta;
use App\Chatbot\Domain\Repositories\PreguntaRepositoryInterface;

class GetPreguntaByIdUseCase
{
    private PreguntaRepositoryInterface $preguntaRepository;

    public function __construct(PreguntaRepositoryInterface $preguntaRepository)
    {
        $this->preguntaRepository = $preguntaRepository;
    }

    public function execute(int $id): ?Pregunta
    {
        return $this->preguntaRepository->findById($id);
    }
}