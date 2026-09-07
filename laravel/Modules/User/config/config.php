<?php

declare(strict_types=1);

return [
    'name' => 'User',
    'description' => 'Modulo per la gestione degli utenti e autorizzazioni',
<<<<<<< HEAD
<<<<<<< HEAD
    'icon' => 'user-icon',
=======
    'icon' => 'heroicon-o-users',
>>>>>>> f548be94 (.)
=======
    'icon' => 'heroicon-o-users',
=======
    'icon' => 'user-icon',
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    'navigation' => [
        'enabled' => true,
        'sort' => 100,
    ],
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)
    'routes' => [
        'enabled' => true,
        'middleware' => ['web', 'auth'],
    ],
    'providers' => [
        'Modules\\User\\Providers\\UserServiceProvider',
    ],
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
];
