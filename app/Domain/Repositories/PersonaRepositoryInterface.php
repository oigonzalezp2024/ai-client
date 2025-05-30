<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Persona;

interface PersonaRepositoryInterface
{
    public function findById(int $id): ?Persona;
    public function create(Persona $persona): Persona;
    public function update(Persona $persona): bool;
    public function delete(int $id): bool;
    public function findAll(): array;
}
