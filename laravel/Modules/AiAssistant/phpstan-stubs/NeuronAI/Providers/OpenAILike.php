<?php

declare(strict_types=1);

namespace NeuronAI\Providers;

class OpenAILike implements AIProviderInterface
{
    public function __construct(
        string $baseUri,
        string $key,
        string $model,
    ) {}
}
