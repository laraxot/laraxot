<?php

declare(strict_types=1);

return [
    'navigation' => ['label' => 'delivery note.navigation', 'icon' => 'delivery note.navigation', 'group' => 'delivery note.navigation'],
    'actions' => [
        'create' => ['label' => 'create', 'icon' => 'create', 'tooltip' => 'create'],
        'view' => ['label' => 'view', 'icon' => 'view', 'tooltip' => 'view'],
        'edit' => ['label' => 'edit', 'icon' => 'edit', 'tooltip' => 'edit'],
        'delete' => ['label' => 'delete', 'icon' => 'delete', 'tooltip' => 'delete'],
        'restore' => ['label' => 'restore', 'icon' => 'restore', 'tooltip' => 'restore'],
        'forceDelete' => ['label' => 'forceDelete', 'icon' => 'forceDelete', 'tooltip' => 'forceDelete'],
        'applyFilters' => ['label' => 'applyFilters', 'icon' => 'applyFilters', 'tooltip' => 'applyFilters'],
        'openFilters' => ['label' => 'openFilters', 'icon' => 'openFilters', 'tooltip' => 'openFilters'],
        'resetFilters' => ['label' => 'resetFilters', 'icon' => 'resetFilters', 'tooltip' => 'resetFilters'],
        'applyTableColumnManager' => ['label' => 'applyTableColumnManager', 'icon' => 'applyTableColumnManager', 'tooltip' => 'applyTableColumnManager'],
        'openColumnManager' => ['label' => 'openColumnManager', 'icon' => 'openColumnManager', 'tooltip' => 'openColumnManager'],
        'resetColumnManager' => ['label' => 'resetColumnManager', 'icon' => 'resetColumnManager', 'tooltip' => 'resetColumnManager'],
        'reorderRecords' => ['label' => 'reorderRecords', 'icon' => 'reorderRecords', 'tooltip' => 'reorderRecords'],
        'il-mio-profilo' => ['label' => 'il-mio-profilo', 'icon' => 'il-mio-profilo', 'tooltip' => 'il-mio-profilo'],
        'profile' => ['label' => 'profile', 'icon' => 'profile', 'tooltip' => 'profile'],
        'logout' => ['label' => 'logout', 'icon' => 'logout', 'tooltip' => 'logout'],
        'save' => ['label' => 'save', 'icon' => 'save', 'tooltip' => 'save'],
        'cancel' => ['label' => 'cancel', 'icon' => 'cancel', 'tooltip' => 'cancel'],
    ],
    'fields' => [
        'number' => ['label' => 'number', 'placeholder' => 'number', 'helper_text' => 'number', 'description' => 'number'],
        'supplier' => [
            'legal_name' => ['label' => 'supplier.legal_name'],
        ],
        'received_on' => ['label' => 'received_on', 'placeholder' => 'received_on', 'helper_text' => 'received_on', 'description' => 'received_on'],
        'lines_count' => ['label' => 'lines_count'],
        'notes' => ['label' => 'notes', 'placeholder' => 'notes', 'helper_text' => 'notes', 'description' => 'notes'],
        'supplier_id' => ['label' => 'supplier_id', 'placeholder' => 'supplier_id', 'helper_text' => 'supplier_id', 'description' => 'supplier_id'],
        'value' => ['label' => 'value', 'placeholder' => 'value', 'helper_text' => 'value', 'description' => 'value'],
        'work_order_id' => ['label' => 'work_order_id', 'placeholder' => 'work_order_id', 'helper_text' => 'work_order_id', 'description' => 'work_order_id'],
    ],
    'sections' => [
        'empty' => ['label' => 'empty', 'heading' => 'empty'],
    ],
];
