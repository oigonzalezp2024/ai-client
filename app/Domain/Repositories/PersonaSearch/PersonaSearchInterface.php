<?php

namespace App\Domain\Repositories\PersonaSearch;

use App\Domain\Entities\Persona;

interface PersonaSearchInterface
{
    public function findByCellphone(string $cellphone): ?Persona;
}
