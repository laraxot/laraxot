<?php

declare(strict_types=1);

namespace NeuronAI\Chat\Messages;

class AssistantMessage extends Message
{
    private string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function getRole(): string
    {
        return 'assistant';
    }

    public function getContent(): string
    {
        return $this->content;
    }

    /** @return array<string, mixed> */
    public function jsonSerialize(): array
    {
        return ['role' => 'assistant', 'content' => $this->content];
    }
}
