<?php

declare(strict_types=1);

return [
    'offline' => [
        'status' => [
            'syncing' => [
                'message' => 'Synchronisierung läuft...',
            ],
            'checking' => [
                'message' => 'Verbindung wird geprüft...',
            ],
        ],
        'actions' => [
            'continue' => [
                'label' => 'Offline fortfahren',
            ],
            'retry' => [
                'label' => 'Verbindung erneut versuchen',
            ],
        ],
        'features' => [
            'sync' => [
                'label' => 'Synchronisieren',
            ],
            'edit_ticket' => [
                'label' => 'Ticket bearbeiten',
            ],
            'view_ticket' => [
                'label' => 'Tickets anzeigen',
            ],
            'create_ticket' => [
                'label' => 'Ticket erstellen',
            ],
            'title' => 'Offline verfügbare Funktionen:',
        ],
        'reassurance' => [
            'message' => 'Keine Sorge, Sie können einige FixCity-Funktionen trotzdem nutzen.',
        ],
        'intro' => [
            'message' => 'Es scheint, dass keine aktive Internetverbindung besteht.',
        ],
        'title' => [
            'label' => 'Offline-Verbindung',
        ],
        'page_title' => [
            'label' => 'Offline - FixCity-Plattform',
        ],
    ],
];
