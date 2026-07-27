<?php

declare(strict_types=1);

return [
    'name' => 'PublicProcurement',
    'description' => 'Gare di appalto pubbliche',
    'icon' => 'heroicon-o-square-3-stack-3d',

    'navigation' => [
        'enabled' => true,
        'sort' => 90,
    ],

    'routes' => [
        'enabled' => true,
        'middleware' => ['web', 'auth'],
    ],

    'providers' => [
        'Modules\\PublicProcurement\\Providers\\PublicProcurementServiceProvider',
    ],
];
