<?php

declare(strict_types=1);

return [
    'offline' => [
        'status' => [
            'syncing' => [
                'message' => 'Sincronizzazione in corso...',
            ],
            'checking' => [
                'message' => 'Verificando connessione...',
            ],
        ],
        'actions' => [
            'continue' => [
                'label' => 'Continua Offline',
            ],
            'retry' => [
                'label' => 'Riprova Connessione',
            ],
        ],
        'features' => [
            'sync' => [
                'label' => 'Sincronizza',
            ],
            'edit_ticket' => [
                'label' => 'Modifica Ticket',
            ],
            'view_ticket' => [
                'label' => 'Visualizza Ticket',
            ],
            'create_ticket' => [
                'label' => 'Crea Ticket',
            ],
            'title' => 'Funzionalità disponibili offline:',
        ],
        'reassurance' => [
            'message' => 'Non preoccuparti, puoi comunque utilizzare alcune funzionalità di FixCity.',
        ],
        'intro' => [
            'message' => 'Sembra che tu non abbia una connessione internet attiva.',
        ],
        'title' => [
            'label' => 'Connessione Offline',
        ],
        'page_title' => [
            'label' => 'Offline - FixCity Platform',
        ],
    ],
];
