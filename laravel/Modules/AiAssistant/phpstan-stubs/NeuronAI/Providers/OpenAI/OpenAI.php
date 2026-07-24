<?php

declare(strict_types=1);

namespace NeuronAI\Providers\OpenAI;

use NeuronAI\Providers\AIProviderInterface;

class OpenAI implements AIProviderInterface
{
    private string $key;

    private string $model;

    public function __construct(
        string $key,
        string $model = 'gpt-4o-mini',
    ) {
        $this->key = $key;
        $this->model = $model;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getModel(): string
    {
        return $this->model;
    }
}
