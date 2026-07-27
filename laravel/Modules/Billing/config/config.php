<?php

declare(strict_types=1);

return [
    'name' => 'Billing',
    'description' => 'Fatturazione e documenti contabili',
    'icon' => 'heroicon-o-square-3-stack-3d',

    'navigation' => [
        'enabled' => true,
        'sort' => 120,
    ],

    'routes' => [
        'enabled' => true,
        'middleware' => ['web', 'auth'],
    ],

    'providers' => [
        'Modules\\Billing\\Providers\\BillingServiceProvider',
    ],
];
