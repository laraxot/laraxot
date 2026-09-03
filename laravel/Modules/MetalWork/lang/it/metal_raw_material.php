<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'Materie prime',
        'group' => 'Carpenteria',
        'icon' => 'heroicon-o-squares-2x2',
        'sort' => 35,
    ],
    'fields' => [
        'code' => [
            'label' => 'Codice',
        ],
        'description' => [
            'label' => 'Descrizione',
        ],
        'family_code' => [
            'label' => 'Famiglia',
        ],
        'unit_code' => [
            'label' => 'UM',
        ],
        'steel_grade' => [
            'label' => 'Qualità acciaio',
        ],
        'supplier_name' => [
            'label' => 'Fornitore',
        ],
        'thickness_mm' => [
            'label' => 'Spessore mm',
        ],
        'width_mm' => [
            'label' => 'Larghezza mm',
        ],
        'height_mm' => [
            'label' => 'Altezza mm',
        ],
        'length_mm' => [
            'label' => 'Lunghezza mm',
        ],
        'density_kg_dm3' => [
            'label' => 'Densità kg/dm³',
        ],
        'weight_per_meter_kg' => [
            'label' => 'Peso al metro kg',
        ],
        'is_active' => [
            'label' => 'Attivo',
        ],
        'current_price' => [
            'label' => 'Prezzo corrente',
        ],
        'supplier_prices_count' => [
            'label' => 'Storico prezzi',
        ],
    ],
];
