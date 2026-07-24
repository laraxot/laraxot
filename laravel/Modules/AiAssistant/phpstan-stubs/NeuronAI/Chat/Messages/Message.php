<?php

declare(strict_types=1);

namespace NeuronAI\Chat\Messages;

abstract class Message implements \JsonSerializable
{
    abstract public function getRole(): string;

    abstract public function getContent(): string;

    /** @return array<string, mixed> */
    abstract public function jsonSerialize(): array;
}
