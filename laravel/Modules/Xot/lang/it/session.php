<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'sessione',
        'plural' => 'sessioni',
        'group' => [
            'name' => 'Admin',
        ],
<<<<<<< HEAD
        'label' => 'Sessione',
        'icon' => 'heroicon-o-user',
=======
        'label' => 'session.navigation',
        'icon' => 'session.navigation',
>>>>>>> 77b1e2748 (first)
        'sort' => 21,
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
    'label' => 'Session',
    'plural_label' => 'Session (Plurale)',
    'fields' => [
        'id' => [
            'label' => 'Identificativo',
            'tooltip' => 'Identificativo univoco del record',
            'helper_text' => '',
            'description' => '',
<<<<<<< HEAD
=======
            'placeholder' => 'id',
>>>>>>> 77b1e2748 (first)
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
<<<<<<< HEAD
=======
        'user_id' => [
            'label' => 'user_id',
            'placeholder' => 'user_id',
            'helper_text' => 'user_id',
            'description' => 'user_id',
        ],
        'ip_address' => [
            'label' => 'ip_address',
            'placeholder' => 'ip_address',
            'helper_text' => 'ip_address',
            'description' => 'ip_address',
        ],
        'user_agent' => [
            'label' => 'user_agent',
            'placeholder' => 'user_agent',
            'helper_text' => 'user_agent',
            'description' => 'user_agent',
        ],
        'payload' => [
            'label' => 'payload',
            'placeholder' => 'payload',
            'helper_text' => 'payload',
            'description' => 'payload',
        ],
        'last_activity' => [
            'label' => 'last_activity',
            'placeholder' => 'last_activity',
            'helper_text' => 'last_activity',
            'description' => 'last_activity',
        ],
>>>>>>> 77b1e2748 (first)
    ],
    'actions' => [
        'create' => [
            'label' => 'Crea Session',
            'icon' => 'create',
<<<<<<< HEAD
=======
            'tooltip' => 'create',
>>>>>>> 77b1e2748 (first)
        ],
        'edit' => [
            'label' => 'Modifica Session',
            'icon' => 'edit',
<<<<<<< HEAD
=======
            'tooltip' => 'edit',
>>>>>>> 77b1e2748 (first)
        ],
        'delete' => [
            'label' => 'Elimina Session',
            'icon' => 'delete',
<<<<<<< HEAD
        ],
        'createAnother' => [
            'icon' => 'createAnother',
        ],
        'save' => [
            'icon' => 'save',
        ],
        'view' => [
            'icon' => 'view',
=======
            'tooltip' => 'delete',
        ],
        'createAnother' => [
            'label' => 'createAnother',
            'icon' => 'createAnother',
            'tooltip' => 'createAnother',
        ],
        'save' => [
            'label' => 'save',
            'icon' => 'save',
            'tooltip' => 'save',
        ],
        'view' => [
            'label' => 'view',
            'icon' => 'view',
            'tooltip' => 'view',
>>>>>>> 77b1e2748 (first)
        ],
    ],
];
