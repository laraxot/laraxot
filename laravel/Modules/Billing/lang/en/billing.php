<?php

declare(strict_types=1);

return [
    'navigation' => 'Billing',
    'invoice' => 'Invoice',
    'invoices' => 'Invoices',

    'fields' => [
        'number' => 'Number',
        'customer' => 'Customer',
        'work_order' => 'Work order',
        'work_orders' => 'Work orders',
        'issued_on' => 'Issued on',
        'total' => 'Total',
        'notes' => 'Notes',
        'email' => 'Email',
        'phone' => 'Phone',
        'vat_number' => 'VAT number',
        'tax_code' => 'Tax code',
        'einvoice_sent_at' => 'e-Invoice sent at',
        'einvoice_external_id' => 'e-Invoice external ID',
    ],

    'sections' => [
        'customer' => 'Customer',
        'supplier' => 'Supplier',
        'work_orders_linked' => 'Linked work orders',
    ],

    'actions' => [
        'download_pdf' => 'Download PDF',
        'send_einvoice' => 'Send electronic invoice',
    ],

    'notifications' => [
        'einvoice_sent_title' => 'Electronic invoice sent',
        'einvoice_sent_body' => 'Provider :provider, external ID :external_id.',
        'einvoice_failed_title' => 'Electronic invoice sending failed',
    ],

    'pre_invoice' => [
        'description' => 'Description',
        'amount' => 'Amount',
        'source' => 'Source',
        'total' => 'Total',
        'empty' => 'No lines to invoice.',
        'undecided_title' => 'Undecided quote extras',
        'undecided_body' => 'There are quote extras not yet decided: issuing now means giving them away by omission.',
        'sources' => [
            'labor' => 'Labor',
            'callout' => 'Callout fee',
            'material' => 'Materials',
            'extra' => 'Quote extra',
        ],
    ],
];
