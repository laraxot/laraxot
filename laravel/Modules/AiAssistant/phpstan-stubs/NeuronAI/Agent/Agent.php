<?php

declare(strict_types=1);

namespace NeuronAI\Agent;

use NeuronAI\Chat\History\ChatHistoryInterface;
use NeuronAI\Chat\Messages\Message;
use NeuronAI\Chat\Messages\Stream\StreamHandler;
use NeuronAI\Providers\AIProviderInterface;

abstract class Agent
{
    abstract protected function provider(): AIProviderInterface;

    /** @return array<int, mixed> */
    abstract protected function tools(): array;

    abstract protected function instructions(): string;

    public function setChatHistory(ChatHistoryInterface $history): self
    {
        return $this;
    }

    public function toolMaxRuns(int $maxRuns): self
    {
        return $this;
    }

    public function stream(Message $message): StreamHandler
    {
        return new StreamHandler();
    }
}
