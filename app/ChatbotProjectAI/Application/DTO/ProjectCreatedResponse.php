<?php

declare(strict_types=1);

namespace App\ChatbotProjectAI\Application\DTO;

class ProjectCreatedResponse
{
    public string $timestamp;
    public string $textoEntrada;
    public string $respuestaAsistente;

    public function __construct(string $timestamp="", string $textoEntrada="", string $respuestaAsistente="")
    {
        $this->timestamp = $timestamp;
        $this->textoEntrada = $textoEntrada;
        $this->respuestaAsistente = $respuestaAsistente;
    }
}
