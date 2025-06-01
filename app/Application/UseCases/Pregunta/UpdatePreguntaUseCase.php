<?php

namespace App\Application\UseCases\Pregunta;

use App\Domain\Entities\Pregunta;
use App\Domain\Repositories\PreguntaRepositoryInterface;
use Exception; // Necesario para lanzar la excepción

class UpdatePreguntaUseCase
{
    private PreguntaRepositoryInterface $preguntaRepository;

    public function __construct(PreguntaRepositoryInterface $preguntaRepository)
    {
        $this->preguntaRepository = $preguntaRepository;
    }

    public function execute(Pregunta $pregunta): Pregunta
    {
        // ¡CAMBIO CLAVE AQUÍ! Usar update() y manejar el retorno booleano
        $updated = $this->preguntaRepository->update($pregunta);

        if (!$updated) {
            throw new Exception("No se pudo actualizar la Pregunta.");
        }
        return $pregunta; // Retorna la entidad actualizada si todo fue bien
    }
}