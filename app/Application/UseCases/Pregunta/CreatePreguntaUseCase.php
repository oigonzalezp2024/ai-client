<?php

namespace App\Application\UseCases\Pregunta;

use App\Domain\Entities\Pregunta;
use App\Domain\Repositories\PreguntaRepositoryInterface;
// Puedes necesitar importar una clase de excepción si el repositorio puede lanzarla,
// o si el caso de uso la lanza por sí mismo.
// use Exception; 

class CreatePreguntaUseCase
{
    private PreguntaRepositoryInterface $preguntaRepository;

    public function __construct(PreguntaRepositoryInterface $preguntaRepository)
    {
        $this->preguntaRepository = $preguntaRepository;
    }

    public function execute(string $suPregunta, ?string $respuesta, int $personaId): Pregunta
    {
        $pregunta = new Pregunta();
        $pregunta->setSuPregunta($suPregunta);
        $pregunta->setRespuesta($respuesta);
        $pregunta->setPersonaId($personaId);
        
        // ¡CAMBIO CLAVE AQUÍ! Usar create()
        return $this->preguntaRepository->create($pregunta);
    }
}