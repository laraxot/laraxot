<?php

declare(strict_types=1);

return [
    'name' => 'Platform',
    'description' => 'Configurazioni e funzionalita di piattaforma',
    'icon' => 'platform-icon',

    'navigation' => [
        'enabled' => true,
        'sort' => 70,
    ],

    'routes' => [
        'enabled' => true,
        'middleware' => ['web', 'auth'],
    ],

    'providers' => [
        'Modules\\Platform\\Providers\\PlatformServiceProvider',
    ],
];
