<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'Salute',
        'plural' => 'Salute',
        'group' => [
            'name' => 'Admin',
        ],
    ],
    'pages' => [
        'health_check_results' => [
            'buttons' => [
                'refresh' => 'Refresh',
            ],
            'heading' => 'Application Health',
            'navigation' => [
                'group' => 'Settings',
                'label' => 'Application Health',
            ],
            'notifications' => [
                'check_results' => 'Check results from',
            ],
        ],
    ],
    'actions' => [
        'refresh' => [
<<<<<<< HEAD
            'label' => 'refresh',
            'tooltip' => 'refresh',
            'icon' => 'refresh',
        ],
        'logout' => [
            'tooltip' => 'logout',
        ],
        'save' => ['label' => 'save', 'icon' => 'save', 'tooltip' => 'save'],
=======
            'label' => 'Aggiorna',
            'tooltip' => '',
            'icon' => 'refresh',
        ],
        'logout' => [
            'tooltip' => '',
            'icon' => 'logout',
        ],
        'save' => [
            'label' => 'Salva',
            'icon' => 'save',
            'tooltip' => '',
        ],
>>>>>>> laraxot/dev
    ],
    'label' => 'Health',
    'plural_label' => 'Health (Plurale)',
    'fields' => [
        'id' => [
            'label' => 'Identificativo',
            'tooltip' => 'Identificativo univoco del record',
            'helper_text' => '',
            'description' => '',
        ],
        'created_at' => [
            'label' => 'Data Creazione',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'updated_at' => [
            'label' => 'Ultima Modifica',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
    ],
<<<<<<< HEAD
=======
    'page' => [
        'last_check' => [
            'label' => 'Ultimo controllo',
        ],
    ],
>>>>>>> laraxot/dev
];
