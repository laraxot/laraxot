<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'Stati commessa',
        'group' => 'Commesse',
        'icon' => 'heroicon-o-flag',
        'sort' => 90,
    ],
    'actions' => [
        'create' => [
            'label' => 'Nuovo stato',
            'icon' => 'heroicon-o-plus',
            'tooltip' => 'Definisce un nuovo stato di avanzamento',
        ],
        'edit' => [
            'label' => 'Modifica',
            'icon' => 'heroicon-o-pencil-square',
            'tooltip' => 'Modifica lo stato',
        ],
        'delete' => [
            'label' => 'Elimina',
            'icon' => 'heroicon-o-trash',
            'tooltip' => 'Elimina lo stato',
        ],
        'layout' => [
            'label' => 'layout',
            'icon' => 'layout',
            'tooltip' => 'layout',
        ],
        'applyFilters' => [
            'label' => 'applyFilters',
            'icon' => 'applyFilters',
            'tooltip' => 'applyFilters',
        ],
        'openFilters' => [
            'label' => 'openFilters',
            'icon' => 'openFilters',
            'tooltip' => 'openFilters',
        ],
        'resetFilters' => [
            'label' => 'resetFilters',
            'icon' => 'resetFilters',
            'tooltip' => 'resetFilters',
        ],
        'applyTableColumnManager' => [
            'label' => 'applyTableColumnManager',
            'icon' => 'applyTableColumnManager',
            'tooltip' => 'applyTableColumnManager',
        ],
        'openColumnManager' => [
            'label' => 'openColumnManager',
            'icon' => 'openColumnManager',
            'tooltip' => 'openColumnManager',
        ],
        'resetColumnManager' => [
            'label' => 'resetColumnManager',
            'icon' => 'resetColumnManager',
            'tooltip' => 'resetColumnManager',
        ],
        'reorderRecords' => [
            'label' => 'reorderRecords',
            'icon' => 'reorderRecords',
            'tooltip' => 'reorderRecords',
        ],
    ],
    'fields' => [
        'code' => [
            'label' => 'Codice',
            'placeholder' => 'es. in_progress',
            'helper_text' => 'Identificativo tecnico usato dal codice: non cambiarlo a cuor leggero.',
        ],
        'name' => [
            'label' => 'Titolo',
            'placeholder' => 'es. In lavorazione',
        ],
        'color' => [
            'label' => 'Colore',
            'helper_text' => 'Colore del badge nelle liste.',
        ],
        'sort_order' => [
            'label' => 'Ordinamento',
            'helper_text' => 'Posizione nel flusso: dal primo stato all\'ultimo.',
        ],
        'is_terminal' => [
            'label' => 'Stato finale',
            'helper_text' => 'Marca la commessa come chiusa: niente più avanzamenti.',
        ],
        'work_orders_count' => [
            'label' => 'Commesse',
        ],
    ],
];
