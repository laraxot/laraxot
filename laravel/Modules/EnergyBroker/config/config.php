<?php

declare(strict_types=1);

return [
    'name' => 'EnergyBroker',
    'description' => 'Broker energetico: import, matching, commissioni agenti',
    'icon' => 'energybroker-icon',

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
