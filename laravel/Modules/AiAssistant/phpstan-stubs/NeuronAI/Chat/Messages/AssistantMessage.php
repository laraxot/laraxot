<?php

declare(strict_types=1);

namespace NeuronAI\Chat\Messages;

class AssistantMessage extends Message
{
    public function __construct(string $content) {}

    public function getRole(): string
    {
        return 'assistant';
    }

    public function getContent(): string
    {
        return '';
    }

    /** @return array<string, mixed> */
    public function jsonSerialize(): array
    {
        return [];
    }
}
