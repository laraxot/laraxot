<?php

declare(strict_types=1);

return [
    'actions' => [
        'create' => ['label' => 'create', 'icon' => 'create', 'tooltip' => 'create'],
        'view' => ['label' => 'view', 'icon' => 'view', 'tooltip' => 'view'],
        'edit' => ['label' => 'edit', 'icon' => 'edit', 'tooltip' => 'edit'],
        'delete' => ['label' => 'delete', 'icon' => 'delete', 'tooltip' => 'delete'],
        'layout' => ['label' => 'layout', 'icon' => 'layout', 'tooltip' => 'layout'],
        'applyFilters' => ['label' => 'applyFilters', 'icon' => 'applyFilters', 'tooltip' => 'applyFilters'],
        'openFilters' => ['label' => 'openFilters', 'icon' => 'openFilters', 'tooltip' => 'openFilters'],
        'resetFilters' => ['label' => 'resetFilters', 'icon' => 'resetFilters', 'tooltip' => 'resetFilters'],
        'applyTableColumnManager' => ['label' => 'applyTableColumnManager', 'icon' => 'applyTableColumnManager', 'tooltip' => 'applyTableColumnManager'],
        'openColumnManager' => ['label' => 'openColumnManager', 'icon' => 'openColumnManager', 'tooltip' => 'openColumnManager'],
        'resetColumnManager' => ['label' => 'resetColumnManager', 'icon' => 'resetColumnManager', 'tooltip' => 'resetColumnManager'],
        'reorderRecords' => ['label' => 'reorderRecords', 'icon' => 'reorderRecords', 'tooltip' => 'reorderRecords'],
    ],
    'fields' => [
        'name' => ['label' => 'name'],
        'code' => ['label' => 'code'],
        'sort_order' => ['label' => 'sort_order'],
        'is_active' => ['label' => 'is_active'],
        'value' => ['label' => 'value', 'placeholder' => 'value', 'helper_text' => 'value', 'description' => 'value'],
    ],
];
