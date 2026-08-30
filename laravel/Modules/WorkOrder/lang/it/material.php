<?php

declare(strict_types=1);

return [
    'actions' => [
        'create' => ['label' => 'Aggiungi materiale', 'icon' => 'heroicon-o-plus', 'tooltip' => 'Aggiunge una riga alla distinta della commessa'],
        'edit' => ['label' => 'Modifica', 'icon' => 'heroicon-o-pencil-square', 'tooltip' => 'Modifica la riga di distinta'],
        'delete' => ['label' => 'Elimina', 'icon' => 'heroicon-o-trash', 'tooltip' => 'Elimina la riga di distinta'],
        'detach' => ['label' => 'detach', 'icon' => 'detach', 'tooltip' => 'detach'],
        'applyFilters' => ['label' => 'applyFilters', 'icon' => 'applyFilters', 'tooltip' => 'applyFilters'],
        'openFilters' => ['label' => 'openFilters', 'icon' => 'openFilters', 'tooltip' => 'openFilters'],
        'resetFilters' => ['label' => 'resetFilters', 'icon' => 'resetFilters', 'tooltip' => 'resetFilters'],
        'applyTableColumnManager' => ['label' => 'applyTableColumnManager', 'icon' => 'applyTableColumnManager', 'tooltip' => 'applyTableColumnManager'],
        'openColumnManager' => ['label' => 'openColumnManager', 'icon' => 'openColumnManager', 'tooltip' => 'openColumnManager'],
        'resetColumnManager' => ['label' => 'resetColumnManager', 'icon' => 'resetColumnManager', 'tooltip' => 'resetColumnManager'],
        'reorderRecords' => ['label' => 'reorderRecords', 'icon' => 'reorderRecords', 'tooltip' => 'reorderRecords'],
        'submit' => ['label' => 'submit', 'icon' => 'submit', 'tooltip' => 'submit'],
        'createAnother' => ['label' => 'createAnother', 'icon' => 'createAnother', 'tooltip' => 'createAnother'],
        'cancel' => ['label' => 'cancel', 'icon' => 'cancel', 'tooltip' => 'cancel'],
    ],
    'fields' => [
        'description' => ['label' => 'Descrizione', 'placeholder' => 'Es. Tubo multistrato 20x2 - 3 m', 'helper_text' => 'Testo libero: gran parte dei materiali di officina non sta a catalogo.', 'description' => 'description'],
        'product_id' => ['label' => 'Prodotto a catalogo', 'placeholder' => 'Cerca un prodotto', 'helper_text' => 'Facoltativo: collega la riga al catalogo per costi e giacenze.', 'description' => 'product_id'],
        'quantity' => ['label' => 'Quantità', 'placeholder' => '0,000', 'helper_text' => 'quantity', 'description' => 'quantity'],
        'unit_code' => ['label' => 'Unità', 'placeholder' => 'pz, m, kg…', 'helper_text' => 'unit_code', 'description' => 'unit_code'],
        'unit_cost' => ['label' => 'Costo unitario', 'placeholder' => '0,0000', 'helper_text' => 'unit_cost', 'description' => 'unit_cost'],
        'line_total' => ['label' => 'Totale riga', 'helper_text' => 'Calcolato da quantità × costo unitario.'],
        'source_code' => ['label' => 'Origine', 'helper_text' => 'Da dove arriva la riga: inserimento manuale o copia dal preventivo.'],
        'notes' => ['label' => 'Note', 'placeholder' => 'Annotazioni sulla riga', 'helper_text' => 'notes', 'description' => 'notes'],
    ],
    'sources' => ['manual' => 'Manuale', 'quotation' => 'Da preventivo'],
];
