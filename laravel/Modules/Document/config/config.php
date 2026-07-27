<?php

declare(strict_types=1);

return [
    'name' => 'Document',
    'description' => 'Gestione documenti e allegati',
    'icon' => 'document-icon',

    'navigation' => [
        'enabled' => true,
        'sort' => 150,
    ],

    'routes' => [
        'enabled' => true,
        'middleware' => ['web', 'auth'],
    ],

    'providers' => [
        'Modules\\Document\\Providers\\DocumentServiceProvider',
    ],
];
