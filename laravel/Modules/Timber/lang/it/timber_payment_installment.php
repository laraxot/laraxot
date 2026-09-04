<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'timber payment installment.navigation',
    ],
    'fields' => [
        'invoice_id' => [
            'label' => 'Fattura',
        ],
        'customer_id' => [
            'label' => 'Cliente',
        ],
        'direction' => [
            'label' => 'Tipo',
        ],
        'due_date' => [
            'label' => 'Scadenza',
        ],
        'amount' => [
            'label' => 'Importo',
        ],
        'payment_method_code' => [
            'label' => 'Metodo',
        ],
        'status_code' => [
            'label' => 'Stato',
        ],
        'notes' => [
            'label' => 'Note',
        ],
        'invoice.number' => [
            'label' => 'Documento',
        ],
        'customer.business_name' => [
            'label' => 'Cliente',
        ],
        'overdue' => [
            'label' => 'Solo scadute da saldare',
        ],
        'riba_to_issue' => [
            'label' => 'RIBA da emettere',
        ],
    ],
    'actions' => [
        'markPaid' => [
            'label' => 'Segna pagato',
        ],
        'sendReminders' => [
            'label' => 'Invia solleciti',
        ],
    ],
];
