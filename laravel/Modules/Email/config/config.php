<?php

declare(strict_types=1);

return [
    'name' => 'Email',
    'description' => 'Gestione email e caselle di posta',
    'icon' => 'heroicon-o-square-3-stack-3d',

    'navigation' => [
        'enabled' => true,
        'sort' => 30,
    ],

    'routes' => [
        'enabled' => true,
        'middleware' => ['web', 'auth'],
    ],

    'providers' => [
        'Modules\\Email\\Providers\\EmailServiceProvider',
    ],
];
