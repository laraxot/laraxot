<?php

declare(strict_types=1);

return [
    'name' => 'Fiscal',
    'description' => 'Gestione fiscale e documenti tributari',
    'icon' => 'heroicon-o-square-3-stack-3d',

    'navigation' => [
        'enabled' => true,
        'sort' => 50,
    ],

    'routes' => [
        'enabled' => true,
        'middleware' => ['web', 'auth'],
    ],

    'providers' => [
        'Modules\\Fiscal\\Providers\\FiscalServiceProvider',
    ],
];
