<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'Regole di stima',
        'group' => 'Insegne',
        'icon' => 'heroicon-o-variable',
        'sort' => 40,
    ],
    'fields' => [
        'role_code' => [
            'label' => 'Ruolo',
        ],
        'strategy_code' => [
            'label' => 'Strategia',
        ],
        'unit_code' => [
            'label' => 'Unità',
        ],
        'factor' => [
            'label' => 'Fattore',
        ],
        'base_value' => [
            'label' => 'Valore base',
        ],
        'min_per_piece' => [
            'label' => 'Minimo per pezzo',
        ],
        'is_active' => [
            'label' => 'Attivo',
        ],
        'notes' => [
            'label' => 'Note',
        ],
    ],
];
