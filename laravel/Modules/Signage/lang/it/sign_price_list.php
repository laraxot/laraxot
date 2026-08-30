<?php

declare(strict_types=1);

return [
    'navigation' => ['label' => 'Listini', 'group' => 'Insegne', 'icon' => 'heroicon-o-currency-euro', 'sort' => 30],
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
    'fields' => [
        'code' => ['label' => 'code'],
        'name' => ['label' => 'name'],
        'brand' => ['label' => 'brand'],
        'items_count' => ['label' => 'items_count'],
        'default_markup_percent' => ['label' => 'default_markup_percent'],
        'is_active' => ['label' => 'is_active'],
    ],
];
