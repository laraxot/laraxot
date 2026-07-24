<?php

declare(strict_types=1);

namespace NeuronAI\Chat\Messages;

class UserMessage extends Message
{
    public function __construct(string $content) {}

    public function getRole(): string
    {
        return 'user';
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
