<?php

declare(strict_types=1);

return [
    'name' => 'HR',
    'description' => 'Gestione risorse umane',
    'icon' => 'hr-icon',

    'navigation' => [
        'enabled' => true,
        'sort' => 160,
    ],

    'routes' => [
        'enabled' => true,
        'middleware' => ['web', 'auth'],
    ],

    'providers' => [
        'Modules\\HR\\Providers\\HrServiceProvider',
    ],
];
