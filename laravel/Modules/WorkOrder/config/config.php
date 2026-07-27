<?php

declare(strict_types=1);

return [
    'name' => 'WorkOrder',
    'description' => 'Commesse e ordini di lavoro',
    'icon' => 'heroicon-o-square-3-stack-3d',

    'navigation' => [
        'enabled' => true,
        'sort' => 210,
    ],

    'routes' => [
        'enabled' => true,
        'middleware' => ['web', 'auth'],
    ],

    'providers' => [
        'Modules\\WorkOrder\\Providers\\WorkOrderServiceProvider',
    ],
];
