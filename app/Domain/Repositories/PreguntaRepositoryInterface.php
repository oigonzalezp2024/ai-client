<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Pregunta;

interface PreguntaRepositoryInterface
{
    public function findById(int $id): ?Pregunta;
    public function create(Pregunta $pregunta): Pregunta;
    public function update(Pregunta $pregunta): bool;
    public function delete(int $id): bool;
    public function findAll(): array;
}
