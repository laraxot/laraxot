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
        'createAnother' => ['label' => 'createAnother', 'icon' => 'createAnother', 'tooltip' => 'createAnother'],
        'cancel' => ['label' => 'cancel', 'icon' => 'cancel', 'tooltip' => 'cancel'],
    ],
    'fields' => [
        'movement_date' => ['label' => 'movement_date', 'placeholder' => 'movement_date', 'helper_text' => 'movement_date', 'description' => 'movement_date'],
        'product' => [
            'code' => ['label' => 'product.code'],
            'description' => ['label' => 'product.description'],
        ],
        'customer' => [
            'business_name' => ['label' => 'customer.business_name'],
        ],
        'document_reference' => ['label' => 'document_reference', 'placeholder' => 'document_reference', 'helper_text' => 'document_reference', 'description' => 'document_reference'],
        'loaded' => ['label' => 'loaded'],
        'lot_code' => ['label' => 'lot_code', 'placeholder' => 'lot_code', 'helper_text' => 'lot_code', 'description' => 'lot_code'],
        'unloaded' => ['label' => 'unloaded'],
        'category' => ['label' => 'category'],
        'direction_code' => ['label' => 'direction_code', 'placeholder' => 'direction_code', 'helper_text' => 'direction_code', 'description' => 'direction_code'],
        'product_id' => ['label' => 'product_id', 'placeholder' => 'product_id', 'helper_text' => 'product_id', 'description' => 'product_id'],
        'customer_id' => ['label' => 'customer_id', 'placeholder' => 'customer_id', 'helper_text' => 'customer_id', 'description' => 'customer_id'],
        'period' => ['label' => 'period'],
        'from' => ['label' => 'from', 'placeholder' => 'from', 'helper_text' => 'from', 'description' => 'from'],
        'until' => ['label' => 'until', 'placeholder' => 'until', 'helper_text' => 'until', 'description' => 'until'],
        'quantity' => ['label' => 'quantity', 'placeholder' => 'quantity', 'helper_text' => 'quantity', 'description' => 'quantity'],
        'unit_of_measure' => ['label' => 'unit_of_measure', 'placeholder' => 'unit_of_measure', 'helper_text' => 'unit_of_measure', 'description' => 'unit_of_measure'],
        'document_type_code' => ['label' => 'document_type_code', 'placeholder' => 'document_type_code', 'helper_text' => 'document_type_code', 'description' => 'document_type_code'],
        'unit_cost' => ['label' => 'unit_cost', 'placeholder' => 'unit_cost', 'helper_text' => 'unit_cost', 'description' => 'unit_cost'],
        'notes' => ['label' => 'notes', 'placeholder' => 'notes', 'helper_text' => 'notes', 'description' => 'notes'],
        'value' => ['label' => 'value', 'placeholder' => 'value', 'helper_text' => 'value', 'description' => 'value'],
    ],
    'sections' => [
        'empty' => ['label' => 'empty', 'heading' => 'empty'],
    ],
];
