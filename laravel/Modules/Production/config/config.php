<?php

declare(strict_types=1);

return [
    'name' => 'Production',
    'description' => 'Gestione produzione e lavorazioni',
    'icon' => 'heroicon-o-square-3-stack-3d',

    'navigation' => [
        'enabled' => true,
        'sort' => 80,
    ],

    'routes' => [
        'enabled' => true,
        'middleware' => ['web', 'auth'],
    ],

    'providers' => [
        'Modules\\Production\\Providers\\ProductionServiceProvider',
    ],
];
