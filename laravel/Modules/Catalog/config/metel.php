<?php

declare(strict_types=1);

/**
 * Metel API — defaults only (no env() here: Larastan forbids env outside root config/).
 * Override via published config or CatalogServiceProvider merge from .env keys METEL_*.
 */
return [
    'api' => [
        'enabled' => false,
        'base_url' => '',
        'api_key' => '',
        'articles_endpoint' => '/articles',
        'items_path' => 'items',
        'timeout' => 60,
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
