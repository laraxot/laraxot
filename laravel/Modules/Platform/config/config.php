<?php

declare(strict_types=1);

return [
    'name' => 'Platform',
    'description' => 'Configurazioni e funzionalita di piattaforma',
    'icon' => 'heroicon-o-square-3-stack-3d',

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
