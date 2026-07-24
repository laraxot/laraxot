<?php

declare(strict_types=1);

namespace NeuronAI\Providers;

class OpenAILike implements AIProviderInterface
{
    private string $baseUri;

    private string $key;

    private string $model;

    public function __construct(
        string $baseUri,
        string $key,
        string $model,
    ) {
        $this->baseUri = $baseUri;
        $this->key = $key;
        $this->model = $model;
    }

    public function getBaseUri(): string
    {
        return $this->baseUri;
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
