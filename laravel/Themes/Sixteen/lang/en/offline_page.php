<?php

declare(strict_types=1);

return [
    'offline' => [
        'status' => [
            'syncing' => [
                'message' => 'Synchronisation in progress...',
            ],
            'checking' => [
                'message' => 'Checking connection...',
            ],
        ],
        'actions' => [
            'continue' => [
                'label' => 'Continue Offline',
            ],
            'retry' => [
                'label' => 'Retry Connection',
            ],
        ],
        'features' => [
            'sync' => [
                'label' => 'Sync',
            ],
            'edit_ticket' => [
                'label' => 'Edit Ticket',
            ],
            'view_ticket' => [
                'label' => 'View Tickets',
            ],
            'create_ticket' => [
                'label' => 'Create Ticket',
            ],
            'title' => 'Features available offline:',
        ],
        'reassurance' => [
            'message' => 'Don\'t worry, you can still use some FixCity features.',
        ],
        'intro' => [
            'message' => 'It looks like you don\'t have an active internet connection.',
        ],
        'title' => [
            'label' => 'Offline Connection',
        ],
        'page_title' => [
            'label' => 'Offline - FixCity Platform',
        ],
    ],
];
