<?php

namespace App\Chatbot\Domain\Repositories;

use App\Chatbot\Domain\Entities\Persona;

interface PersonaRepositoryInterface
{
    public function findById(int $id): ?Persona;
    public function create(Persona $persona): Persona;
    public function update(Persona $persona): bool;
    public function delete(int $id): bool;
    public function findAll(): array;
}
