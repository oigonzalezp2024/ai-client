<?php

declare(strict_types=1);

namespace App\ChatbotProjectAI\Infrastructure\Adapters;

use App\ChatbotProjectAI\Application\DTO\ProjectCreatedResponse;

// --- Clase: Formateo ---
// Definida en el namespace global.
class ProjectCreatedResponseFormat
{
    private ProjectCreatedResponse $project;

    public function __construct(ProjectCreatedResponse $project)
    {
        $this->project = $project;
    }

    public function response(): string
    {
        $data = [
            'timestamp' => $this->project->timestamp,
            'texto_entrada' => $this->project->textoEntrada,
            'respuesta_asistente' => $this->project->respuestaAsistente,
        ];

        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}
