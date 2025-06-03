<?php
namespace App\ChatbotProjectAI\Application\Service;

use App\ChatbotProjectAI\Application\DTO\CreateProjectCommand;
use App\ChatbotProjectAI\Application\DTO\ProjectCreatedResponse;
use App\ChatbotProjectAI\Application\Orchestrator\FeedbackImplement;

class AIProjectService
{
    private FeedbackImplement $feedbackProcessor;

    public function __construct(FeedbackImplement $feedbackProcessor)
    {
        $this->feedbackProcessor = $feedbackProcessor;
    }

    public function create(CreateProjectCommand $command, string $apiKey, string $baseUri, string $model): ProjectCreatedResponse
    {
        $this->feedbackProcessor->input($command);
        $this->feedbackProcessor->run($apiKey, $baseUri, $model);
        return $this->feedbackProcessor->output();
    }
}
