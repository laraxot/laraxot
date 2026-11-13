<?php

declare(strict_types=1);

return [
    'offline' => [
        'status' => [
            'syncing' => [
                'message' => 'Sincronización en curso...',
            ],
            'checking' => [
                'message' => 'Comprobando la conexión...',
            ],
        ],
        'actions' => [
            'continue' => [
                'label' => 'Continuar sin conexión',
            ],
            'retry' => [
                'label' => 'Reintentar conexión',
            ],
        ],
        'features' => [
            'sync' => [
                'label' => 'Sincronizar',
            ],
            'edit_ticket' => [
                'label' => 'Editar ticket',
            ],
            'view_ticket' => [
                'label' => 'Ver tickets',
            ],
            'create_ticket' => [
                'label' => 'Crear ticket',
            ],
            'title' => 'Funciones disponibles sin conexión:',
        ],
        'reassurance' => [
            'message' => 'No te preocupes, aún puedes utilizar algunas funciones de FixCity.',
        ],
        'intro' => [
            'message' => 'Parece que no tienes una conexión a internet activa.',
        ],
        'title' => [
            'label' => 'Conexión sin conexión',
        ],
        'page_title' => [
            'label' => 'Sin conexión - Plataforma FixCity',
        ],
    ],
];
