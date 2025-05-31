<?php

namespace App\Application\UseCases\Pregunta;

use App\Domain\Entities\Pregunta; // Podría necesitarse para el tipo de parámetro
use App\Domain\Repositories\PreguntaRepositoryInterface;
use Exception; // Necesario para lanzar la excepción

class DeletePreguntaUseCase
{
    private PreguntaRepositoryInterface $preguntaRepository;

    public function __construct(PreguntaRepositoryInterface $preguntaRepository)
    {
        $this->preguntaRepository = $preguntaRepository;
    }

    public function execute(Pregunta $pregunta): void // El Use Case recibe la entidad completa
    {
        // ¡CAMBIO CLAVE AQUÍ! Pasar el ID al método delete() del repositorio y manejar el retorno booleano
        $deleted = $this->preguntaRepository->delete($pregunta->getIdPregunta());

        if (!$deleted) {
            throw new Exception("No se pudo eliminar la Pregunta.");
        }
    }
}