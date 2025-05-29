<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Pregunta;

interface PreguntaRepositoryInterface
{
    public function findById(int $id): ?Pregunta;
    public function save(Pregunta $pregunta): Pregunta;
    public function delete(Pregunta $pregunta): void;
    public function findAll(): array;
}
