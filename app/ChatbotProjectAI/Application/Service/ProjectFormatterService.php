<?php

namespace App\ChatbotProjectAI\Application\Service;

use App\ChatbotProjectAI\Application\DTO\ProjectCreatedResponse;
use App\ChatbotProjectAI\Infrastructure\Adapters\ProjectCreatedResponseFormat;

class ProjectFormatterService
{
    private ProjectCreatedResponse $responseDto;

    public function __construct(ProjectCreatedResponse $responseDto)
    {
        $this->responseDto = $responseDto;
    }

    public function getData(): array
    {
        $formatter = new ProjectCreatedResponseFormat($this->responseDto);
        $jsonString = $formatter->response();
        $data = json_decode($jsonString, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException("JSON inválido: " . json_last_error_msg());
        }

        return $data;
    }
}
