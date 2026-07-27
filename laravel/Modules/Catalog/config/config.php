<?php

declare(strict_types=1);

return [
    'name' => 'Catalog',
    'description' => 'Catalogo prodotti e categorie',
    'icon' => 'heroicon-o-square-3-stack-3d',

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
