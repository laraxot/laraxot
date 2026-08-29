<?php

declare(strict_types=1);

use Illuminate\Support\Env;

return [
    'name' => 'Timber',
    'description' => 'Fatturazione e documenti fiscali Timber',
    'icon' => 'timber-icon',
    'navigation' => [
        'enabled' => true,
        'sort' => 150,
    ],

    // Tipi di documento fiscale gestiti dall'aggregato TimberInvoice.
    'invoice_kinds' => [
        'preventivo' => 'Preventivo',
        'fattura_proforma' => 'Fattura pro-forma',
        'fattura' => 'Fattura',
        'vendita_banco' => 'Vendita al banco',
        'corrispettivo' => 'Corrispettivo',
    ],

    // Documenti fiscali che scaricano il magazzino Timber quando emessi.
    'invoice_stock_directions' => [
        'vendita_banco' => 'unload',
        'corrispettivo' => 'unload',
    ],

    'invoice_number_prefixes' => [
        'preventivo' => 'PRV',
        'fattura_proforma' => 'PRO',
        'fattura' => 'FT',
        'vendita_banco' => 'VB',
        'corrispettivo' => 'COR',
    ],

    'invoice_statuses' => [
        'draft' => 'Bozza',
        'issued' => 'Emessa',
        'paid' => 'Pagata',
        'cancelled' => 'Annullata',
    ],

    // Tipi documento che concorrono al registro IVA vendite (esclusi preventivi/pro-forma).
    'vat_register_kinds' => ['fattura', 'vendita_banco', 'corrispettivo'],

    // Tipi documento di magazzino (app/Timber) che concorrono al registro IVA acquisti.
    'vat_purchase_document_types' => ['arrivo_merce'],

    'vat_rates' => [
        '22' => '22%',
        '10' => '10%',
        '4' => '4%',
        '0' => 'Esente / non imponibile',
    ],

    'payment_methods' => [
        'riba' => 'RIBA',
        'bonifico' => 'Bonifico',
        'contanti' => 'Contanti',
        'assegno' => 'Assegno',
        'rid' => 'RID / SDD',
    ],

    'payment_statuses' => [
        'da_saldare' => 'Da saldare',
        'sollecitato' => 'Sollecitato',
        'saldato' => 'Saldato',
    ],

    // Dati del cedente/prestatore per la fattura elettronica (configurabili per installazione).
    'company' => [
        'vat_number' => Env::get('TIMBER_BILLING_VAT', '01234567890'),
        'tax_code' => Env::get('TIMBER_BILLING_TAX_CODE', '01234567890'),
        'name' => Env::get('TIMBER_BILLING_NAME', 'ECO-TRON SNC'),
        'tax_regime' => Env::get('TIMBER_BILLING_REGIME', 'RF01'),
        'address' => Env::get('TIMBER_BILLING_ADDRESS', 'Via delle Segherie 1'),
        'postal_code' => Env::get('TIMBER_BILLING_CAP', '38100'),
        'city' => Env::get('TIMBER_BILLING_CITY', 'Trento'),
        'province' => Env::get('TIMBER_BILLING_PROVINCE', 'TN'),
        'country' => Env::get('TIMBER_BILLING_COUNTRY', 'IT'),
    ],

    'sdi_statuses' => [
        'da_inviare' => 'Da inviare',
        'inviata' => 'Inviata',
        'consegnata' => 'Consegnata',
        'scartata' => 'Scartata',
        'non_consegnata' => 'Non consegnata',
    ],
];
