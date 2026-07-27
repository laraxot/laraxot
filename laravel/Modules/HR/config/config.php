<?php

declare(strict_types=1);

return [
    'name' => 'HR',
    'description' => 'Gestione risorse umane',
    'icon' => 'heroicon-o-square-3-stack-3d',

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
