<?php

declare(strict_types=1);

return [
    'navigation' => ['label' => 'Catalogo insegne', 'group' => 'Insegne', 'icon' => 'heroicon-o-rectangle-stack', 'sort' => 20],
    'actions' => [
        'create' => ['label' => 'create', 'icon' => 'create', 'tooltip' => 'create'],
        'view' => ['label' => 'view', 'icon' => 'view', 'tooltip' => 'view'],
        'edit' => ['label' => 'edit', 'icon' => 'edit', 'tooltip' => 'edit'],
        'replicate' => ['label' => 'replicate', 'icon' => 'replicate', 'tooltip' => 'replicate'],
        'delete' => ['label' => 'delete', 'icon' => 'delete', 'tooltip' => 'delete'],
        'applyFilters' => ['label' => 'applyFilters', 'icon' => 'applyFilters', 'tooltip' => 'applyFilters'],
        'openFilters' => ['label' => 'openFilters', 'icon' => 'openFilters', 'tooltip' => 'openFilters'],
        'resetFilters' => ['label' => 'resetFilters', 'icon' => 'resetFilters', 'tooltip' => 'resetFilters'],
        'applyTableColumnManager' => ['label' => 'applyTableColumnManager', 'icon' => 'applyTableColumnManager', 'tooltip' => 'applyTableColumnManager'],
        'openColumnManager' => ['label' => 'openColumnManager', 'icon' => 'openColumnManager', 'tooltip' => 'openColumnManager'],
        'resetColumnManager' => ['label' => 'resetColumnManager', 'icon' => 'resetColumnManager', 'tooltip' => 'resetColumnManager'],
        'reorderRecords' => ['label' => 'reorderRecords', 'icon' => 'reorderRecords', 'tooltip' => 'reorderRecords'],
        'submit' => ['label' => 'submit', 'icon' => 'submit', 'tooltip' => 'submit'],
        'cancel' => ['label' => 'cancel', 'icon' => 'cancel', 'tooltip' => 'cancel'],
    ],
    'fields' => [
        'code' => ['label' => 'code'],
        'priceList' => [
            'name' => ['label' => 'priceList.name'],
        ],
        'name' => ['label' => 'name'],
        'letter_height_min_mm' => ['label' => 'letter_height_min_mm'],
        'led_temperature_code' => ['label' => 'led_temperature_code'],
        'components_count' => ['label' => 'components_count'],
        'manual_sale_price' => ['label' => 'manual_sale_price'],
        'is_active' => ['label' => 'is_active'],
        'sign_price_list_id' => ['label' => 'sign_price_list_id'],
        'value' => ['label' => 'value', 'placeholder' => 'value', 'helper_text' => 'value', 'description' => 'value'],
        'description' => ['label' => 'description'],
        'pricing' => ['label' => 'pricing'],
    ],
    'sections' => [
        'empty' => ['label' => 'empty', 'heading' => 'empty'],
        'Calcolo del prezzo' => ['label' => 'Calcolo del prezzo', 'heading' => 'Calcolo del prezzo'],
    ],
];
