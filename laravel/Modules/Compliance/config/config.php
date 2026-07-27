<?php

declare(strict_types=1);

return [
    'name' => 'Compliance',
    'description' => 'Conformita normativa e controlli',
    'icon' => 'heroicon-o-square-3-stack-3d',

    'navigation' => [
        'enabled' => true,
        'sort' => 20,
    ],

    'routes' => [
        'enabled' => true,
        'middleware' => ['web', 'auth'],
    ],

    'providers' => [
        'Modules\\Compliance\\Providers\\ComplianceServiceProvider',
    ],
];
