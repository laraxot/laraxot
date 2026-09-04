<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'timber e invoice.navigation',
    ],
    'actions' => [
        'transmit' => [
            'label' => 'Segna inviata a SdI',
        ],
        'downloadXml' => [
            'label' => 'Scarica XML',
        ],
    ],
    'fields' => [
        'invoice.number' => [
            'label' => 'Fattura',
        ],
        'invoice.customer.business_name' => [
            'label' => 'Cliente',
        ],
        'direction' => [
            'label' => 'Flusso',
        ],
        'sdi_status' => [
            'label' => 'Stato SdI',
        ],
        'file_name' => [
            'label' => 'File',
        ],
        'transmitted_at' => [
            'label' => 'Trasmessa il',
        ],
    ],
];
