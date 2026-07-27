<?php

declare(strict_types=1);

return [
    'name' => 'Inventory',
    'description' => 'Gestione magazzino e movimenti inventario',
    'icon' => 'inventory-icon',

    'navigation' => [
        'enabled' => true,
        'sort' => 60,
    ],

    'routes' => [
        'enabled' => true,
        'middleware' => ['web', 'auth'],
    ],

    'providers' => [
        'Modules\\Inventory\\Providers\\InventoryServiceProvider',
    ],
];
