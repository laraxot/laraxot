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
        'exportCsv' => [
            'label' => 'Esporta CSV',
            'icon' => 'exportCsv',
            'tooltip' => 'exportCsv',
        ],
        'importCsv' => [
            'label' => 'Importa CSV',
            'icon' => 'importCsv',
            'tooltip' => 'importCsv',
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
    ],
    'fields' => [
        'name' => [
            'label' => 'Nome',
            'placeholder' => 'name',
            'helper_text' => 'name',
            'description' => 'name',
        ],
        'code' => [
            'label' => 'Codice',
            'placeholder' => 'code',
            'helper_text' => 'code',
            'description' => 'code',
        ],
        'items_count' => [
            'label' => 'Prezzi',
        ],
        'versions_count' => [
            'label' => 'Versioni',
        ],
        'is_active' => [
            'label' => 'Attivo',
            'placeholder' => 'is_active',
            'helper_text' => 'is_active',
            'description' => 'is_active',
        ],
        'file' => [
            'label' => 'File CSV (codice prodotto; prezzo)',
            'placeholder' => 'file',
            'helper_text' => 'file',
            'description' => 'file',
        ],
        'items' => [
            'label' => 'items',
            'placeholder' => 'items',
            'helper_text' => 'items',
            'description' => 'items',
        ],
        'product_id' => [
            'label' => 'Prodotto',
            'placeholder' => 'product_id',
            'helper_text' => 'product_id',
            'description' => 'product_id',
        ],
        'net_price' => [
            'label' => 'Prezzo netto',
            'placeholder' => 'net_price',
            'helper_text' => 'net_price',
            'description' => 'net_price',
        ],
    ],
    'sections' => [
        'empty' => [
            'label' => 'empty',
            'heading' => 'empty',
        ],
        'Prezzi prodotti' => [
            'label' => 'Prezzi prodotti',
            'heading' => 'Prezzi prodotti',
        ],
    ],
    'navigation' => [
        'label' => 'timber price list.navigation',
    ],
];
