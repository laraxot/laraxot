<?php

declare(strict_types=1);

return [
    'navigation' => 'Fornitori',

    'supplier' => 'Fornitore',
    'suppliers' => 'Fornitori',
    'delivery_note' => 'DDT',
    'delivery_notes' => 'DDT',
    'delivery_note_lines' => 'Righe DDT',
    'supplier_invoice' => 'Fattura fornitore',
    'supplier_invoices' => 'Fatture fornitore',
    'supplier_invoice_lines' => 'Righe fattura fornitore',

    'fields' => [
        'legal_name' => 'Ragione sociale',
        'vat_number' => 'Partita IVA',
        'email' => 'Email',
        'phone' => 'Telefono',
        'is_active' => 'Attivo',
        'number' => 'Numero',
        'supplier' => 'Fornitore',
        'work_order' => 'Commessa',
        'received_on' => 'Data ricevimento',
        'issued_on' => 'Data emissione',
        'notes' => 'Note',
        'total' => 'Totale',
        'status' => 'Stato',
        'description' => 'Descrizione',
        'quantity' => 'Quantita\'',
        'unit_price' => 'Prezzo unitario',
        'line_total' => 'Totale riga',
        'delivery_note_line' => 'Riga DDT',
        'lines_count' => 'N. righe',
        'delivery_notes_count' => 'N. DDT',
        'invoices_count' => 'N. fatture',
    ],

    'statuses' => [
        'pending' => 'Da conciliare',
        'reconciled' => 'Conciliata',
        'disputed' => 'Contestata',
    ],

    'reconciliation' => [
        'action' => 'Concilia con i DDT',
        'title' => 'Conciliazione fattura fornitore',
        'submit' => 'Chiudi',
        'summary' => 'Riepilogo conciliazione',
        'clean_title' => 'Nessuna discrepanza',
        'clean_body' => 'Tutte le righe fatturate corrispondono a quanto consegnato.',
        'disputed_title' => 'Discrepanze rilevate',
        'disputed_body' => 'Sono state trovate differenze tra fatturato e consegnato: verificare prima di procedere.',
        'columns' => [
            'description' => 'Descrizione',
            'delivered_quantity' => 'Qta consegnata',
            'invoiced_quantity' => 'Qta fatturata',
            'delivered_unit_price' => 'Prezzo DDT',
            'invoiced_unit_price' => 'Prezzo fattura',
            'amount_difference' => 'Differenza',
            'type' => 'Tipo',
        ],
        'types' => [
            'not_delivered' => 'Non consegnato',
            'quantity_over' => 'Quantita\' eccedente',
            'price_mismatch' => 'Prezzo discordante',
            'not_invoiced' => 'Consegnato non fatturato',
        ],
        'explanations' => [
            'not_delivered' => 'Fatturato materiale che non risulta consegnato da nessun DDT.',
            'quantity_over' => 'Fatturata una quantita\' superiore a quella consegnata.',
            'price_mismatch' => 'Prezzo fatturato diverso da quello del DDT.',
            'not_invoiced' => 'Consegnato ma non ancora fatturato: atteso su una fattura successiva.',
        ],
    ],
];
