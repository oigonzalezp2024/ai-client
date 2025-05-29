<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Persona;

interface PersonaRepositoryInterface
{
    public function findById(int $id): ?Persona;
    public function save(Persona $persona): Persona;
    public function delete(Persona $persona): void;
    public function findAll(): array;
}
