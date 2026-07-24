<?php

declare(strict_types=1);

namespace NeuronAI\Chat\Messages;

class UserMessage extends Message
{
    private string $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function getRole(): string
    {
        return 'user';
    }

    public function getContent(): string
    {
        return $this->content;
    }

    /** @return array<string, mixed> */
    public function jsonSerialize(): array
    {
        return ['role' => 'user', 'content' => $this->content];
    }
}
