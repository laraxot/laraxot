<?php

declare(strict_types=1);

return [
    'actions' => [
        'create' => [
            'label' => 'create',
            'icon' => 'create',
            'tooltip' => 'create',
        ],
        'view' => [
            'label' => 'view',
            'icon' => 'view',
            'tooltip' => 'view',
        ],
        'edit' => [
            'label' => 'edit',
            'icon' => 'edit',
            'tooltip' => 'edit',
        ],
        'delete' => [
            'label' => 'delete',
            'icon' => 'delete',
            'tooltip' => 'delete',
        ],
        'layout' => [
            'label' => 'layout',
            'icon' => 'layout',
            'tooltip' => 'layout',
        ],
        'postToStock' => [
            'label' => 'Registra a magazzino',
            'icon' => 'postToStock',
            'tooltip' => 'postToStock',
        ],
        'applyFilters' => [
            'label' => 'applyFilters',
            'icon' => 'applyFilters',
            'tooltip' => 'applyFilters',
        ],
        'openFilters' => [
            'label' => 'openFilters',
            'icon' => 'openFilters',
            'tooltip' => 'openFilters',
        ],
        'resetFilters' => [
            'label' => 'resetFilters',
            'icon' => 'resetFilters',
            'tooltip' => 'resetFilters',
        ],
        'applyTableColumnManager' => [
            'label' => 'applyTableColumnManager',
            'icon' => 'applyTableColumnManager',
            'tooltip' => 'applyTableColumnManager',
        ],
        'openColumnManager' => [
            'label' => 'openColumnManager',
            'icon' => 'openColumnManager',
            'tooltip' => 'openColumnManager',
        ],
        'resetColumnManager' => [
            'label' => 'resetColumnManager',
            'icon' => 'resetColumnManager',
            'tooltip' => 'resetColumnManager',
        ],
        'reorderRecords' => [
            'label' => 'reorderRecords',
            'icon' => 'reorderRecords',
            'tooltip' => 'reorderRecords',
        ],
        'createAnother' => [
            'label' => 'createAnother',
            'icon' => 'createAnother',
            'tooltip' => 'createAnother',
        ],
        'cancel' => [
            'label' => 'cancel',
            'icon' => 'cancel',
            'tooltip' => 'cancel',
        ],
        'add' => [
            'label' => 'add',
            'icon' => 'add',
            'tooltip' => 'add',
        ],
        'addBetween' => [
            'label' => 'addBetween',
            'icon' => 'addBetween',
            'tooltip' => 'addBetween',
        ],
        'clone' => [
            'label' => 'clone',
            'icon' => 'clone',
            'tooltip' => 'clone',
        ],
        'collapse' => [
            'label' => 'collapse',
            'icon' => 'collapse',
            'tooltip' => 'collapse',
        ],
        'collapseAll' => [
            'label' => 'collapseAll',
            'icon' => 'collapseAll',
            'tooltip' => 'collapseAll',
        ],
        'expand' => [
            'label' => 'expand',
            'icon' => 'expand',
            'tooltip' => 'expand',
        ],
        'expandAll' => [
            'label' => 'expandAll',
            'icon' => 'expandAll',
            'tooltip' => 'expandAll',
        ],
        'moveDown' => [
            'label' => 'moveDown',
            'icon' => 'moveDown',
            'tooltip' => 'moveDown',
        ],
        'moveUp' => [
            'label' => 'moveUp',
            'icon' => 'moveUp',
            'tooltip' => 'moveUp',
        ],
        'reorder' => [
            'label' => 'reorder',
            'icon' => 'reorder',
            'tooltip' => 'reorder',
        ],
        'submit' => [
            'label' => 'submit',
            'icon' => 'submit',
            'tooltip' => 'submit',
        ],
    ],
    'fields' => [
        'document_type_code' => [
            'label' => 'Tipo',
            'placeholder' => 'document_type_code',
            'helper_text' => 'document_type_code',
            'description' => 'document_type_code',
        ],
        'number' => [
            'label' => 'Numero',
            'placeholder' => 'number',
            'helper_text' => 'number',
            'description' => 'number',
        ],
        'document_date' => [
            'label' => 'Data',
            'placeholder' => 'document_date',
            'helper_text' => 'document_date',
            'description' => 'document_date',
        ],
        'customer' => [
            'business_name' => [
                'label' => 'customer.business_name',
            ],
        ],
        'status_code' => [
            'label' => 'Stato',
            'placeholder' => 'status_code',
            'helper_text' => 'status_code',
            'description' => 'status_code',
        ],
        'total_amount' => [
            'label' => 'Totale',
        ],
        'stock_posted_at' => [
            'label' => 'A magazzino',
        ],
        'customer_id' => [
            'label' => 'Cliente / Fornitore',
            'placeholder' => 'customer_id',
            'helper_text' => 'customer_id',
            'description' => 'customer_id',
        ],
        'reference' => [
            'label' => 'Riferimento / Causale',
            'placeholder' => 'reference',
            'helper_text' => 'reference',
            'description' => 'reference',
        ],
        'lines' => [
            'label' => 'lines',
            'placeholder' => 'lines',
            'helper_text' => 'lines',
            'description' => 'lines',
        ],
        'product_id' => [
            'label' => 'Prodotto',
            'placeholder' => 'product_id',
            'helper_text' => 'product_id',
            'description' => 'product_id',
        ],
        'description' => [
            'label' => 'Descrizione',
            'placeholder' => 'description',
            'helper_text' => 'description',
            'description' => 'description',
        ],
        'quantity' => [
            'label' => 'Quantita',
            'placeholder' => 'quantity',
            'helper_text' => 'quantity',
            'description' => 'quantity',
        ],
        'unit_of_measure' => [
            'label' => 'U.m.',
            'placeholder' => 'unit_of_measure',
            'helper_text' => 'unit_of_measure',
            'description' => 'unit_of_measure',
        ],
        'unit_price' => [
            'label' => 'Prezzo unit.',
            'placeholder' => 'unit_price',
            'helper_text' => 'unit_price',
            'description' => 'unit_price',
        ],
        'discount_percent' => [
            'label' => 'Sconto %',
            'placeholder' => 'discount_percent',
            'helper_text' => 'discount_percent',
            'description' => 'discount_percent',
        ],
        'lot_code' => [
            'label' => 'Lotto a magazzino',
            'placeholder' => 'lot_code',
            'helper_text' => 'lot_code',
            'description' => 'lot_code',
        ],
        'notes' => [
            'label' => 'Note',
            'placeholder' => 'notes',
            'helper_text' => 'notes',
            'description' => 'notes',
        ],
        'value' => [
            'label' => 'value',
            'placeholder' => 'value',
            'helper_text' => 'value',
            'description' => 'value',
        ],
        'customer.business_name' => [
            'label' => 'Cliente / Fornitore',
        ],
    ],
    'sections' => [
        'Testata' => [
            'label' => 'Testata',
            'heading' => 'Testata',
        ],
        'Righe' => [
            'label' => 'Righe',
            'heading' => 'Righe',
        ],
    ],
    'navigation' => [
        'label' => 'timber document.navigation',
    ],
];
