<?php

declare(strict_types=1);

return [
    'name' => 'Catalog',
    'description' => 'Catalogo prodotti e categorie',
    'icon' => 'catalog-icon',

    'navigation' => [
        'enabled' => true,
        'sort' => 130,
    ],

    'routes' => [
        'enabled' => true,
        'middleware' => ['web', 'auth'],
    ],

    'providers' => [
        'Modules\\Catalog\\Providers\\CatalogServiceProvider',
    ],
];
