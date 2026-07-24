<?php

declare(strict_types=1);

return [
    'api' => [
        'enabled' => env('METEL_API_ENABLED', false),
        'base_url' => env('METEL_API_BASE_URL', ''),
        'api_key' => env('METEL_API_KEY', ''),
        'articles_endpoint' => env('METEL_API_ARTICLES_ENDPOINT', '/articles'),
        'items_path' => env('METEL_API_ITEMS_PATH', 'items'),
        'timeout' => env('METEL_API_TIMEOUT', 60),
        'field_map' => [
            'metel_code' => 'code',
            'gross_price' => 'grossPrice',
            'net_price' => 'netPrice',
            'description' => 'description',
            'unit_of_measure' => 'unit',
            'packaging' => 'packaging',
            'barcode' => 'ean',
            'etim_code' => 'etim',
        ],
    ],

    'fixed_layout' => [],

    'csv_map' => [],

    'fixed_price_divisor' => 100,
];
