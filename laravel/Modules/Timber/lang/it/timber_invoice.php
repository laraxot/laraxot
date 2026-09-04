<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'timber invoice.navigation',
    ],
    'fields' => [
        'kind' => [
            'label' => 'Tipo',
        ],
        'number' => [
            'label' => 'Numero',
        ],
        'invoice_date' => [
            'label' => 'Data',
        ],
        'customer_id' => [
            'label' => 'Cliente',
        ],
        'payment_method_code' => [
            'label' => 'Metodo pagamento',
        ],
        'payment_terms' => [
            'label' => 'Termini pagamento',
        ],
        'product_id' => [
            'label' => 'Prodotto',
        ],
        'description' => [
            'label' => 'Descrizione',
        ],
        'quantity' => [
            'label' => 'Quantita',
        ],
        'unit_of_measure' => [
            'label' => 'U.m.',
        ],
        'unit_price' => [
            'label' => 'Prezzo unit.',
        ],
        'discount_percent' => [
            'label' => 'Sconto %',
        ],
        'vat_rate' => [
            'label' => 'IVA %',
        ],
        'lot_code' => [
            'label' => 'Lotto',
        ],
        'notes' => [
            'label' => 'Note',
        ],
        'customer.business_name' => [
            'label' => 'Cliente',
        ],
        'status_code' => [
            'label' => 'Stato',
        ],
        'taxable_amount' => [
            'label' => 'Imponibile',
        ],
        'vat_amount' => [
            'label' => 'IVA',
        ],
        'total_amount' => [
            'label' => 'Totale',
        ],
    ],
    'actions' => [
        'downloadPdf' => [
            'label' => 'Anteprima PDF',
        ],
        'sendEmail' => [
            'label' => 'Invia email',
        ],
        'whatsApp' => [
            'label' => 'WhatsApp',
        ],
        'prepareEInvoice' => [
            'label' => 'Genera fattura elettronica',
        ],
        'issue' => [
            'label' => 'Emetti',
        ],
    ],
];
