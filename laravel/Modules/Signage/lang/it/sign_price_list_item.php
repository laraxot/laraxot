<?php

declare(strict_types=1);

return [
    'navigation' => ['label' => 'Voci di listino', 'group' => 'Insegne', 'icon' => 'heroicon-o-list-bullet', 'sort' => 35],
    'actions' => [
        'create' => ['label' => 'create', 'icon' => 'create', 'tooltip' => 'create'],
        'view' => ['label' => 'view', 'icon' => 'view', 'tooltip' => 'view'],
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
    'fields' => [
        'code' => ['label' => 'code'],
        'name' => ['label' => 'name'],
        'category_code' => ['label' => 'category_code'],
        'work_center_code' => ['label' => 'work_center_code'],
        'supplier_name' => ['label' => 'supplier_name'],
        'unit_code' => ['label' => 'unit_code'],
        'unit_cost' => ['label' => 'unit_cost'],
        'is_active' => ['label' => 'is_active'],
        'value' => ['label' => 'value', 'placeholder' => 'value', 'helper_text' => 'value', 'description' => 'value'],
    ],
];
