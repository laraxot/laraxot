<?php

declare(strict_types=1);

return [
    'name' => 'AiAssistant',
    'description' => 'Assistente AI per automazioni e proposte',
    'icon' => 'aiassistant-icon',

    'navigation' => [
        'enabled' => true,
        'sort' => 110,
    ],

    'routes' => [
        'enabled' => true,
        'middleware' => ['web', 'auth'],
    ],

    'providers' => [
        'Modules\\AiAssistant\\Providers\\AiAssistantServiceProvider',
    ],
];
