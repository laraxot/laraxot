<?php

declare(strict_types=1);

/**
 * Metel API / listino — defaults only (no env() here: Larastan forbids env outside root config/).
 * Override METEL_* via CatalogServiceProvider::applyMetelEnvOverrides().
 *
 * Key di accesso runtime: `config('metel.*')` (alias registrato nello SP; XotBase
 * carica anche `catalog.metel` dal glob config/).
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

    /*
    | Fixed-width "listino METEL" — [start, length] 0-based (tracciato ufficiale).
    */
    'fixed_layout' => [
        'brand_code' => [0, 3],
        'metel_code' => [3, 16],
        'description' => [57, 50],
        'unit_of_measure' => [107, 3],
        'packaging' => [110, 6],
        'gross_price' => [116, 11],
        'barcode' => [127, 13],
    ],

    'fixed_price_divisor' => 100,

    /*
    | CSV/Excel export wholesaler — indici colonna 0-based.
    */
    'csv_map' => [
        'metel_code' => 0,
        'description' => 1,
        'unit_of_measure' => 2,
        'packaging' => 3,
        'gross_price' => 4,
        'barcode' => 5,
        'etim_code' => 6,
    ],
];
