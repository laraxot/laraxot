<?php

declare(strict_types=1);

return [
    'navigation' => 'Fatturazione',
    'invoice' => 'Fattura',
    'invoices' => 'Fatture',

    'fields' => [
        'number' => 'Numero',
        'customer' => 'Cliente',
        'work_order' => 'Commessa',
        'work_orders' => 'Commesse',
        'issued_on' => 'Data emissione',
        'total' => 'Totale',
        'notes' => 'Note',
        'email' => 'Email',
        'phone' => 'Telefono',
        'vat_number' => 'Partita IVA',
        'tax_code' => 'Codice fiscale',
        'einvoice_sent_at' => 'Inviata SDI il',
        'einvoice_external_id' => 'ID esterno SDI',
    ],

    'sections' => [
        'customer' => 'Cliente',
        'supplier' => 'Fornitore',
        'work_orders_linked' => 'Commesse collegate',
    ],

    'actions' => [
        'download_pdf' => 'Scarica PDF',
        'send_einvoice' => 'Invia fattura elettronica',
    ],

    'notifications' => [
        'einvoice_sent_title' => 'Fattura elettronica inviata',
        'einvoice_sent_body' => 'Provider :provider, ID esterno :external_id.',
        'einvoice_failed_title' => 'Invio fattura elettronica fallito',
    ],

    'pre_invoice' => [
        'description' => 'Descrizione',
        'amount' => 'Importo',
        'source' => 'Origine',
        'total' => 'Totale',
        'empty' => 'Nessuna voce da fatturare.',
        'undecided_title' => 'Extra preventivo da decidere',
        'undecided_body' => 'Ci sono extra preventivo non ancora decisi: emettere adesso significa regalarli per omissione.',
        'sources' => [
            'labor' => 'Manodopera',
            'callout' => 'Diritto di chiamata',
            'material' => 'Materiali',
            'extra' => 'Extra preventivo',
        ],
    ],
];
