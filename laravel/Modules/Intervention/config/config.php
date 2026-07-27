<?php

declare(strict_types=1);

return [
    'name' => 'Intervention',
    'description' => 'Interventi tecnici sul campo',
    'icon' => 'intervention-icon',

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
