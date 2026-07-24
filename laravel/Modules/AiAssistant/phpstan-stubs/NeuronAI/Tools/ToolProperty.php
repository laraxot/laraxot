<?php

declare(strict_types=1);

namespace NeuronAI\Tools;

class ToolProperty
{
    /**
     * @param  list<string>|null  $enum
     */
    public static function make(
        string $name,
        PropertyType $type,
        string $description,
        bool $required = false,
        ?array $enum = null,
    ): self {
        return new self();
    }
}
