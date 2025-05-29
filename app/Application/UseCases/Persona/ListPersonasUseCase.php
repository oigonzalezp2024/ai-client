<?php

namespace App\Application\UseCases\Persona;

use App\Domain\Entities\Persona; // Necesario si el tipo de hint de array usa la clase de la entidad
use App\Domain\Repositories\PersonaRepositoryInterface;
// use Traversable; // Ya no es necesario, ya que devolvemos un array directamente

class ListPersonasUseCase
{
    private PersonaRepositoryInterface $personaRepository;

    public function __construct(PersonaRepositoryInterface $personaRepository)
    {
        $this->personaRepository = $personaRepository;
    }

    /**
     * @return Persona[]|array Retorna un array de objetos Persona.
     */
    public function execute(): array // <<< CAMBIO CRUCIAL: De Traversable a array
    {
        return $this->personaRepository->findAll();
    }
}