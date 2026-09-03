<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'Centri di lavoro',
        'group' => 'Carpenteria',
        'icon' => 'heroicon-o-wrench-screwdriver',
        'sort' => 40,
    ],
    'fields' => [
        'code' => [
            'label' => 'Codice',
        ],
        'name' => [
            'label' => 'Nome',
        ],
        'hourly_rate' => [
            'label' => 'Costo orario €/h',
        ],
        'default_markup_percent' => [
            'label' => 'Ricarico di default %',
        ],
        'sort_order' => [
            'label' => 'Ordine',
        ],
        'is_internal' => [
            'label' => 'Interno',
        ],
        'is_active' => [
            'label' => 'Attivo',
        ],
    ],
];
