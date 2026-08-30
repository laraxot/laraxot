<?php

declare(strict_types=1);

return [
    'navigation' => ['label' => 'supplier invoice.navigation', 'icon' => 'supplier invoice.navigation'],
    'actions' => [
        'create' => ['label' => 'create', 'icon' => 'create', 'tooltip' => 'create'],
        'reconcileWithDeliveryNotes' => ['label' => 'reconcileWithDeliveryNotes', 'icon' => 'reconcileWithDeliveryNotes', 'tooltip' => 'reconcileWithDeliveryNotes'],
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
        'issued_on' => ['label' => 'issued_on', 'placeholder' => 'issued_on', 'helper_text' => 'issued_on', 'description' => 'issued_on'],
        'total' => ['label' => 'total', 'placeholder' => 'total', 'helper_text' => 'total', 'description' => 'total'],
        'status_code' => ['label' => 'status_code', 'placeholder' => 'status_code', 'helper_text' => 'status_code', 'description' => 'status_code'],
        'lines_count' => ['label' => 'lines_count'],
        'supplier_id' => ['label' => 'supplier_id', 'placeholder' => 'supplier_id', 'helper_text' => 'supplier_id', 'description' => 'supplier_id'],
        'value' => ['label' => 'value', 'placeholder' => 'value', 'helper_text' => 'value', 'description' => 'value'],
        'notes' => ['label' => 'notes', 'placeholder' => 'notes', 'helper_text' => 'notes', 'description' => 'notes'],
    ],
    'sections' => [
        'empty' => ['label' => 'empty', 'heading' => 'empty'],
    ],
];
