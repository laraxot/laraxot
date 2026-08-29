<?php

declare(strict_types=1);

return [
    'actions' => [
        'create' => ['label' => 'create', 'icon' => 'create', 'tooltip' => 'create'],
        'view' => ['label' => 'view', 'icon' => 'view', 'tooltip' => 'view'],
        'edit' => ['label' => 'edit', 'icon' => 'edit', 'tooltip' => 'edit'],
        'delete' => ['label' => 'delete', 'icon' => 'delete', 'tooltip' => 'delete'],
        'layout' => ['label' => 'layout', 'icon' => 'layout', 'tooltip' => 'layout'],
        'restore' => ['label' => 'restore', 'icon' => 'restore', 'tooltip' => 'restore'],
        'forceDelete' => ['label' => 'forceDelete', 'icon' => 'forceDelete', 'tooltip' => 'forceDelete'],
        'applyFilters' => ['label' => 'applyFilters', 'icon' => 'applyFilters', 'tooltip' => 'applyFilters'],
        'openFilters' => ['label' => 'openFilters', 'icon' => 'openFilters', 'tooltip' => 'openFilters'],
        'resetFilters' => ['label' => 'resetFilters', 'icon' => 'resetFilters', 'tooltip' => 'resetFilters'],
        'applyTableColumnManager' => ['label' => 'applyTableColumnManager', 'icon' => 'applyTableColumnManager', 'tooltip' => 'applyTableColumnManager'],
        'openColumnManager' => ['label' => 'openColumnManager', 'icon' => 'openColumnManager', 'tooltip' => 'openColumnManager'],
        'resetColumnManager' => ['label' => 'resetColumnManager', 'icon' => 'resetColumnManager', 'tooltip' => 'resetColumnManager'],
        'reorderRecords' => ['label' => 'reorderRecords', 'icon' => 'reorderRecords', 'tooltip' => 'reorderRecords'],
        'createAnother' => ['label' => 'createAnother', 'icon' => 'createAnother', 'tooltip' => 'createAnother'],
        'cancel' => ['label' => 'cancel', 'icon' => 'cancel', 'tooltip' => 'cancel'],
    ],
    'fields' => [
        'legal_name' => ['label' => 'legal_name', 'placeholder' => 'legal_name', 'helper_text' => 'legal_name', 'description' => 'legal_name'],
        'vehicle_plate' => ['label' => 'vehicle_plate', 'placeholder' => 'vehicle_plate', 'helper_text' => 'vehicle_plate', 'description' => 'vehicle_plate'],
        'is_active' => ['label' => 'is_active', 'placeholder' => 'is_active', 'helper_text' => 'is_active', 'description' => 'is_active'],
        'updated_at' => ['label' => 'updated_at'],
        'vat_number' => ['label' => 'vat_number', 'placeholder' => 'vat_number', 'helper_text' => 'vat_number', 'description' => 'vat_number'],
        'phone' => ['label' => 'phone', 'placeholder' => 'phone', 'helper_text' => 'phone', 'description' => 'phone'],
        'email' => ['label' => 'email', 'placeholder' => 'email', 'helper_text' => 'email', 'description' => 'email'],
        'notes' => ['label' => 'notes', 'placeholder' => 'notes', 'helper_text' => 'notes', 'description' => 'notes'],
    ],
    'sections' => [
        'empty' => ['label' => 'empty', 'heading' => 'empty'],
    ],
];
