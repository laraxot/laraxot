<?php

declare(strict_types=1);

return [
    'name' => 'Signature',
    'description' => 'Firma digitale documenti',
    'icon' => 'signature-icon',

    'navigation' => [
        'enabled' => true,
        'sort' => 190,
    ],

    'routes' => [
        'enabled' => true,
        'middleware' => ['web', 'auth'],
    ],

    'providers' => [
        'Modules\\Signature\\Providers\\SignatureServiceProvider',
    ],
];
