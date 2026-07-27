<?php

declare(strict_types=1);

return [
    'name' => 'Intervention',
    'description' => 'Interventi tecnici sul campo',
    'icon' => 'heroicon-o-square-3-stack-3d',

    'navigation' => [
        'enabled' => true,
        'sort' => 170,
    ],

    'routes' => [
        'enabled' => true,
        'middleware' => ['web', 'auth'],
    ],

    'providers' => [
        'Modules\\Intervention\\Providers\\InterventionServiceProvider',
    ],
];
