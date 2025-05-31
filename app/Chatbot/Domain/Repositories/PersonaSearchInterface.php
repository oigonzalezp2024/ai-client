<?php

namespace App\Chatbot\Domain\Repositories;

use App\Chatbot\Domain\Entities\Persona;

interface PersonaSearchInterface
{
    public function findByCellphone(string $cellphone): ?Persona;
}
