<?php

declare(strict_types=1);

return [
    'name' => 'Vehicle',
    'description' => 'Anagrafica veicoli aziendali',
    'icon' => 'heroicon-o-square-3-stack-3d',

    'navigation' => [
        'enabled' => true,
        'sort' => 200,
    ],

    'routes' => [
        'enabled' => true,
        'middleware' => ['web', 'auth'],
    ],

    'providers' => [
        'Modules\\Vehicle\\Providers\\VehicleServiceProvider',
    ],
];
