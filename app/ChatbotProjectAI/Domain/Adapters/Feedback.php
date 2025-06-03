<?php

declare(strict_types=1);

namespace App\ChatbotProjectAI\Domain\Adapters;

use App\ChatbotProjectAI\Application\DTO\CreateProjectCommand;
use App\ChatbotProjectAI\Application\DTO\ProjectCreatedResponse;

interface Feedback
{
    public function input(CreateProjectCommand $command): Void;
    public function run(): Void;
    public function output(): ProjectCreatedResponse;
}
