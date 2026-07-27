<?php

declare(strict_types=1);

return [
    'name' => 'Customer',
    'description' => 'Anagrafica clienti: cliente, sedi, referenti, ruoli referente, rivenditori',
    'icon' => 'customer-icon',

    'navigation' => [
        'enabled' => true,
        'sort' => 140,
    ],

    'routes' => [
        'enabled' => true,
        'middleware' => ['web', 'auth'],
    ],

    'providers' => [
        'Modules\\Customer\\Providers\\CustomerServiceProvider',
    ],
];
