<?php

declare(strict_types=1);

namespace NeuronAI\Providers\OpenAI;

use NeuronAI\Providers\AIProviderInterface;

class OpenAI implements AIProviderInterface
{
    public function __construct(
        string $key,
        string $model = 'gpt-4o-mini',
    ) {}
}
