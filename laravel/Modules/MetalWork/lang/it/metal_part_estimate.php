<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'Preventivi pezzo',
        'group' => 'Carpenteria',
        'icon' => 'heroicon-o-calculator',
        'sort' => 30,
    ],
    'fields' => [
        'part_code' => [
            'label' => 'Codice disegno',
        ],
        'name' => [
            'label' => 'Nome',
        ],
        'quantity' => [
            'label' => 'Q.tà',
        ],
        'gross_weight_kg' => [
            'label' => 'Peso con sfrido kg',
        ],
        'total_cost' => [
            'label' => 'Costo totale',
        ],
        'sale_price' => [
            'label' => 'Prezzo di vendita',
        ],
        'rawMaterial.code' => [
            'label' => 'Materia prima',
        ],
        'quotation.code' => [
            'label' => 'Preventivo',
        ],
        'created_at' => [
            'label' => 'Creato il',
        ],
    ],
];
