<?php

declare(strict_types=1);

return [
    'name' => 'EnergyBroker',
    'description' => 'Broker energetico: import, matching, commissioni agenti',
    'icon' => 'heroicon-o-square-3-stack-3d',

    'navigation' => [
        'enabled' => true,
        'sort' => 40,
    ],

    'routes' => [
        'enabled' => true,
        'middleware' => ['web', 'auth'],
    ],

    'providers' => [
        'Modules\\EnergyBroker\\Providers\\EnergyBrokerServiceProvider',
    ],
];
