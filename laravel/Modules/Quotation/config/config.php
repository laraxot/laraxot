<?php

declare(strict_types=1);

return [
    'name' => 'Quotation',
    'description' => 'Preventivi e offerte commerciali',
    'icon' => 'quotation-icon',

    'navigation' => [
        'enabled' => true,
        'sort' => 180,
    ],

    'routes' => [
        'enabled' => true,
        'middleware' => ['web', 'auth'],
    ],

    'providers' => [
        'Modules\\Quotation\\Providers\\QuotationServiceProvider',
    ],
];
