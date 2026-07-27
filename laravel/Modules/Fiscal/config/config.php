<?php

declare(strict_types=1);

return [
    'name' => 'Fiscal',
    'description' => 'Gestione fiscale e documenti tributari',
    'icon' => 'fiscal-icon',

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
