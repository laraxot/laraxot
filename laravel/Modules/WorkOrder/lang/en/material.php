<?php

declare(strict_types=1);

return [
    'actions' => [
        'create' => ['label' => 'Add material', 'icon' => 'heroicon-o-plus', 'tooltip' => 'Add a line to the work order bill of materials'],
        'edit' => ['label' => 'Edit', 'icon' => 'heroicon-o-pencil-square', 'tooltip' => 'Edit the material line'],
        'delete' => ['label' => 'Delete', 'icon' => 'heroicon-o-trash', 'tooltip' => 'Delete the material line'],
    ],
    'fields' => [
        'description' => [
            'label' => 'Description',
            'placeholder' => 'e.g. Multilayer pipe 20x2 - 3 m',
            'helper_text' => 'Free text: most workshop materials are not in the catalogue.',
        ],
        'product_id' => [
            'label' => 'Catalogue product',
            'placeholder' => 'Search a product',
            'helper_text' => 'Optional: link the line to the catalogue for costs and stock.',
        ],
        'quantity' => ['label' => 'Quantity', 'placeholder' => '0.000'],
        'unit_code' => ['label' => 'Unit', 'placeholder' => 'pcs, m, kg…'],
        'unit_cost' => ['label' => 'Unit cost', 'placeholder' => '0.0000'],
        'line_total' => [
            'label' => 'Line total',
            'helper_text' => 'Computed from quantity × unit cost.',
        ],
        'source_code' => [
            'label' => 'Source',
            'helper_text' => 'Where the line comes from: manual entry or copied from a quotation.',
        ],
        'notes' => ['label' => 'Notes', 'placeholder' => 'Line notes'],
    ],
    'sources' => [
        'manual' => 'Manual',
        'quotation' => 'From quotation',
    ],
];
