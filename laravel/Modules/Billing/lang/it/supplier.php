<?php

declare(strict_types=1);

return [
    'navigation' => ['label' => 'supplier.navigation', 'icon' => 'supplier.navigation'],
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
        'legal_name' => ['label' => 'legal_name', 'placeholder' => 'legal_name', 'helper_text' => 'legal_name', 'description' => 'legal_name'],
        'vat_number' => ['label' => 'vat_number', 'placeholder' => 'vat_number', 'helper_text' => 'vat_number', 'description' => 'vat_number'],
        'email' => ['label' => 'email', 'placeholder' => 'email', 'helper_text' => 'email', 'description' => 'email'],
        'phone' => ['label' => 'phone', 'placeholder' => 'phone', 'helper_text' => 'phone', 'description' => 'phone'],
        'delivery_notes_count' => ['label' => 'delivery_notes_count'],
        'invoices_count' => ['label' => 'invoices_count'],
        'is_active' => ['label' => 'is_active', 'placeholder' => 'is_active', 'helper_text' => 'is_active', 'description' => 'is_active'],
        'value' => ['label' => 'value', 'placeholder' => 'value', 'helper_text' => 'value', 'description' => 'value'],
    ],
    'sections' => [
        'empty' => ['label' => 'empty', 'heading' => 'empty'],
    ],
];
