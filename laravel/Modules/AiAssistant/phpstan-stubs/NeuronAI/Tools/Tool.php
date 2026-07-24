<?php

declare(strict_types=1);

namespace NeuronAI\Tools;

class Tool implements ToolInterface
{
    public static function make(string $name, string $description): self
    {
        return new self();
    }

    public function addProperty(ToolProperty $property): self
    {
        return $this;
    }

    public function setCallable(callable $callable): self
    {
        return $this;
    }
}
