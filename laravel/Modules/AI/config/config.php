<?php

declare(strict_types=1);

return [
    'name' => 'AI',
    'description' => 'Integrazione AI e fine-tuning',
    'icon' => 'ai-icon',
    'navigation' => [
        'enabled' => true,
        'sort' => 1,
    ],
    'fine_tuning_url' => config('services.openai.fine_tuning_url', 'http://localhost:8000/api/fine-tuning'),
];
