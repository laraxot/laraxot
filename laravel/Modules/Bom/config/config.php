<?php

declare(strict_types=1);

return [
    'name' => 'Bom',
    'description' => 'Distinta base e componenti prodotto',
    'icon' => 'heroicon-o-square-3-stack-3d',

    'navigation' => [
        'enabled' => true,
        'sort' => 10,
    ],

    'routes' => [
        'enabled' => true,
        'middleware' => ['web', 'auth'],
    ],

    'providers' => [
        'Modules\\Bom\\Providers\\BomServiceProvider',
    ],
];
