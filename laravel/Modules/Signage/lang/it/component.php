<?php

declare(strict_types=1);

return [
    'navigation' => ['label' => 'Componenti', 'group' => 'Insegne', 'icon' => 'heroicon-o-puzzle-piece', 'sort' => 25],
    'fields' => [
        'position' => ['label' => 'Posizione'],
        'work_center_code' => ['label' => 'Centro di lavoro'],
        'unit_code' => ['label' => 'Unità'],
        'quantity' => ['label' => 'Quantità'],
        'width_mm' => ['label' => 'Larghezza (mm)'],
        'height_mm' => ['label' => 'Altezza (mm)'],
        'notes' => ['label' => 'Note'],
        'supplierItem' => [
            'code' => ['label' => 'supplierItem.code'],
            'supplier_name' => ['label' => 'supplierItem.supplier_name'],
        ],
        'description' => ['label' => 'description'],
        'unit_cost' => ['label' => 'unit_cost'],
        'line_cost' => ['label' => 'line_cost'],
    ],
    'actions' => [
        'create' => ['label' => 'create', 'icon' => 'create', 'tooltip' => 'create'],
        'edit' => ['label' => 'edit', 'icon' => 'edit', 'tooltip' => 'edit'],
        'delete' => ['label' => 'delete', 'icon' => 'delete', 'tooltip' => 'delete'],
        'applyFilters' => ['label' => 'applyFilters', 'icon' => 'applyFilters', 'tooltip' => 'applyFilters'],
        'openFilters' => ['label' => 'openFilters', 'icon' => 'openFilters', 'tooltip' => 'openFilters'],
        'resetFilters' => ['label' => 'resetFilters', 'icon' => 'resetFilters', 'tooltip' => 'resetFilters'],
        'applyTableColumnManager' => ['label' => 'applyTableColumnManager', 'icon' => 'applyTableColumnManager', 'tooltip' => 'applyTableColumnManager'],
        'openColumnManager' => ['label' => 'openColumnManager', 'icon' => 'openColumnManager', 'tooltip' => 'openColumnManager'],
        'resetColumnManager' => ['label' => 'resetColumnManager', 'icon' => 'resetColumnManager', 'tooltip' => 'resetColumnManager'],
        'reorderRecords' => ['label' => 'reorderRecords', 'icon' => 'reorderRecords', 'tooltip' => 'reorderRecords'],
    ],
];
