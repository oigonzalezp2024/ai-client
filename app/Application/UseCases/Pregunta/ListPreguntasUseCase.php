<?php

namespace App\Application\UseCases\Pregunta;

use App\Domain\Entities\Pregunta; // Necesario si el tipo de hint de array usa la clase de la entidad
use App\Domain\Repositories\PreguntaRepositoryInterface;
// use Traversable; // Ya no es necesario, ya que devolvemos un array directamente

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
    public function execute(): array // <<< CAMBIO CRUCIAL: De Traversable a array
    {
        return $this->preguntaRepository->findAll();
    }
}