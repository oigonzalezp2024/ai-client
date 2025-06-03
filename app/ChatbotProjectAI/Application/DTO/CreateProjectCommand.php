<?php

declare(strict_types=1);

namespace App\ChatbotProjectAI\Application\DTO;

class CreateProjectCommand
{
    public string $textoEntrada;

    public function __construct(string $textoEntrada = "")
    {
        $this->textoEntrada = $textoEntrada;
    }
}
