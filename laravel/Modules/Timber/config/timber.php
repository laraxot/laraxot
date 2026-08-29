<?php

declare(strict_types=1);

use Illuminate\Support\Env;

return [
    'wood_species' => [
        'abete' => 'Abete',
        'faggio' => 'Faggio',
        'quercia' => 'Quercia',
        'pioppo' => 'Pioppo',
        'larice' => 'Larice',
        'castagno' => 'Castagno',
    ],

    'units_of_measure' => [
        'kg' => 'Kg',
        'mc' => 'Metri cubi',
        'quintali' => 'Quintali',
        'unita' => 'Unita',
    ],

    // Unita di misura del prezzo di acquisto (di solito a quintale, a volte al kg).
    'purchase_price_units' => [
        'quintale' => 'euro / quintale',
        'kg' => 'euro / kg',
    ],

    'area_codes' => [
        'piazzale' => 'Piazzale',
        'area_stagionatura' => 'Area stagionatura',
        'essiccatoio' => 'Essiccatoio',
        'zona_taglio' => 'Zona taglio',
        'deposito_semilavorati' => 'Deposito semilavorati',
        'magazzino_prodotto_finito' => 'Magazzino prodotto finito',
        'area_scarti' => 'Area scarti',
    ],

    'processing_step_types' => [
        'taglio' => 'Taglio',
        'spaccatura' => 'Spaccatura',
        'selezione' => 'Selezione',
        'confezionamento' => 'Confezionamento',
        'pallettizzazione' => 'Pallettizzazione',
    ],

    // Mappa area -> tappa: spostando un semilavorato in queste aree il codice
    // tracciabile viene arricchito e l'etichetta ristampata.
    'area_stage_markers' => [
        'essiccatoio' => 'essiccazione',
        'area_stagionatura' => 'stagionatura',
    ],

    // Segmento di codice aggiunto al codice tracciabile del lotto per ogni tappa.
    'stage_code_prefixes' => [
        'taglio' => 'TAG',
        'spaccatura' => 'SPA',
        'selezione' => 'SEL',
        'confezionamento' => 'CNF',
        'pallettizzazione' => 'PAL',
        'essiccazione' => 'ESS',
        'stagionatura' => 'PIAZ',
    ],

    'package_types' => [
        'bancale' => 'Bancale',
        'sacco' => 'Sacco',
        'mc' => 'Metro cubo',
        'quintale' => 'Quintale',
        'unita' => 'Unita di vendita',
    ],

    'product_types' => [
        'lot_managed' => 'Art. con magazzino (lotti)',
        'stock_managed' => 'Art. con magazzino',
        'service' => 'Servizio',
    ],

    'price_lists' => [
        'ben_b2c' => 'Ben B2C',
        'ben_b2b' => 'Ben B2B',
        'smoke_b2c' => 'Smoke B2C',
        'smoke_b2b' => 'Smoke B2B',
        'sboli_b2b' => 'Sboli B2B',
    ],

    'vat_rates' => [
        '22' => '22%',
        '10' => '10%',
        '4' => '4%',
        '0' => 'Esente / non imponibile',
    ],

    'stock_movement_directions' => [
        'load' => 'Carico',
        'unload' => 'Scarico',
        'adjustment' => 'Rettifica',
    ],

    'document_types' => [
        'ordine_cliente' => 'Ordine cliente',
        'ordine_fornitore' => 'Ordine fornitore',
        'ddt' => 'Documento di trasporto',
        'arrivo_merce' => 'Arrivo merce',
    ],

    'document_statuses' => [
        'draft' => 'Bozza',
        'confirmed' => 'Confermato',
        'delivered' => 'Evaso',
        'cancelled' => 'Annullato',
    ],

    // Which stock movement a document generates when posted to the warehouse.
    // Document types not listed here have no direct stock impact (orders / commitments).
    'document_stock_directions' => [
        'ddt' => 'unload',
        'arrivo_merce' => 'load',
    ],

    'document_number_prefixes' => [
        'ordine_cliente' => 'OC',
        'ordine_fornitore' => 'OF',
        'ddt' => 'DDT',
        'arrivo_merce' => 'AM',
    ],

    'material_statuses' => [
        'da_lavorare' => 'Da lavorare',
        'in_lavorazione' => 'In lavorazione',
        'in_essiccazione' => 'In essiccazione',
        'pronto_da_confezionare' => 'Pronto da confezionare',
        'pronto_per_vendita' => 'Pronto per vendita',
        'prenotato' => 'Prenotato',
        'scarto' => 'Scarto di lavorazione',
    ],

    'operational_parameters' => [
        'standard_shrinkage_percent' => [
            'label' => 'Calo standard di lavorazione',
            'value' => 8,
            'unit' => '%',
        ],
        'standard_drying_days' => [
            'label' => 'Giorni standard di essiccazione',
            'value' => 45,
            'unit' => 'giorni',
        ],
        'material_stuck_alert_days' => [
            'label' => 'Giorni di giacenza ferma prima dell\'alert',
            'value' => 30,
            'unit' => 'giorni',
        ],
        'target_humidity_percent' => [
            'label' => 'Umidita target di fine essiccazione',
            'value' => 18,
            'unit' => '%',
        ],
    ],

    'easyfatt' => [
        'enabled' => Env::get('TIMBER_EASYFATT_ENABLED', false),
        'connection' => Env::get('TIMBER_EASYFATT_CONNECTION'),
        'customers_table' => Env::get('TIMBER_EASYFATT_CUSTOMERS_TABLE'),
        'products_table' => Env::get('TIMBER_EASYFATT_PRODUCTS_TABLE'),
        'columns' => [
            'customer_code' => Env::get('TIMBER_EASYFATT_MAP_CUSTOMER_CODE', 'customer_code'),
            'customer_name' => Env::get('TIMBER_EASYFATT_MAP_CUSTOMER_NAME', 'customer_name'),
            'customer_vat_number' => Env::get('TIMBER_EASYFATT_MAP_CUSTOMER_VAT', 'vat_number'),
            'product_code' => Env::get('TIMBER_EASYFATT_MAP_PRODUCT_CODE', 'product_code'),
            'product_name' => Env::get('TIMBER_EASYFATT_MAP_PRODUCT_NAME', 'product_name'),
            'product_unit_price' => Env::get('TIMBER_EASYFATT_MAP_PRODUCT_PRICE', 'unit_price'),
        ],
        'fallback_customers' => [
            [
                'customer_code' => 'CL-0001',
                'customer_name' => 'Falegnameria Rossi Srl',
                'vat_number' => 'IT01234567890',
            ],
            [
                'customer_code' => 'CL-0002',
                'customer_name' => 'Legnami Bianchi & Figli Snc',
                'vat_number' => 'IT09876543210',
            ],
        ],
        'fallback_products' => [
            [
                'product_code' => 'PF-0001',
                'product_name' => 'Legna da ardere abete - bancale',
                'unit_price' => 95.00,
            ],
            [
                'product_code' => 'PF-0002',
                'product_name' => 'Tavole faggio essiccato - mc',
                'unit_price' => 310.00,
            ],
        ],
    ],

    'documentation' => [
        'index' => 'docs/timber/README.md',
        'documents' => [
            'docs/timber/01-visione-e-perimetro.md',
            'docs/timber/02-flussi-operativi.md',
            'docs/timber/03-ui-schermate.md',
            'docs/timber/04-modello-dati-e-regole.md',
            'docs/timber/05-regole-business.md',
            'docs/timber/06-accettazione-e-governance.md',
        ],
    ],
];
