<?php

declare(strict_types=1);

return [
    'name' => 'WhatsApp',
    'description' => 'Integrazione WhatsApp Business',
    'icon' => 'whatsapp-icon',

    'navigation' => [
        'enabled' => true,
        'sort' => 100,
    ],

    'routes' => [
        'enabled' => true,
        'middleware' => ['web', 'auth'],
    ],

    'providers' => [
        'Modules\\WhatsApp\\Providers\\WhatsAppServiceProvider',
    ],
];
