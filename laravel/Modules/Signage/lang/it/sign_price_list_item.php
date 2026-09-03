<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'Voci di listino',
        'group' => 'Insegne',
        'icon' => 'heroicon-o-list-bullet',
        'sort' => 35,
    ],
    'actions' => [
        'create' => [
            'label' => 'create',
            'icon' => 'create',
            'tooltip' => 'create',
        ],
        'view' => [
            'label' => 'view',
            'icon' => 'view',
            'tooltip' => 'view',
        ],
        'edit' => [
            'label' => 'edit',
            'icon' => 'edit',
            'tooltip' => 'edit',
        ],
        'delete' => [
            'label' => 'delete',
            'icon' => 'delete',
            'tooltip' => 'delete',
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
        'layout' => [
            'label' => 'layout',
            'icon' => 'layout',
            'tooltip' => 'layout',
        ],
    ],
    'fields' => [
        'code' => [
            'label' => 'Codice',
        ],
        'name' => [
            'label' => 'Descrizione',
        ],
        'category_code' => [
            'label' => 'Categoria',
        ],
        'work_center_code' => [
            'label' => 'C. lavoro',
        ],
        'supplier_name' => [
            'label' => 'Fornitore',
        ],
        'unit_code' => [
            'label' => 'Unità',
        ],
        'unit_cost' => [
            'label' => 'Costo unitario',
        ],
        'is_active' => [
            'label' => 'Attivo',
        ],
        'value' => [
            'label' => 'value',
            'placeholder' => 'value',
            'helper_text' => 'value',
            'description' => 'value',
        ],
        'currency' => [
            'label' => 'Valuta',
        ],
        'notes' => [
            'label' => 'Note',
        ],
        'supplier_code' => [
            'label' => 'Codice fornitore',
        ],
    ],
];
