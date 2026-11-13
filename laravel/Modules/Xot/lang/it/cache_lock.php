<?php

declare(strict_types=1);

return [
    'navigation' => [
<<<<<<< HEAD
        'name' => 'Blocco cache',
=======
        'name' => 'cache lock',
>>>>>>> 77b1e2748 (first)
        'plural' => 'cache locks',
        'group' => [
            'name' => 'Admin',
        ],
<<<<<<< HEAD
        'label' => 'Lock Cache',
        'icon' => 'heroicon-o-shield-exclamation',
=======
        'label' => 'cache lock.navigation',
        'icon' => 'cache lock.navigation',
>>>>>>> 77b1e2748 (first)
        'sort' => 95,
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
    'label' => 'Cache Lock',
    'plural_label' => 'Cache Lock (Plurale)',
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
<<<<<<< HEAD
=======
        'key' => [
            'label' => 'key',
            'placeholder' => 'key',
            'helper_text' => 'key',
            'description' => 'key',
        ],
        'owner' => [
            'label' => 'owner',
            'placeholder' => 'owner',
            'helper_text' => 'owner',
            'description' => 'owner',
        ],
        'expiration' => [
            'label' => 'expiration',
            'placeholder' => 'expiration',
            'helper_text' => 'expiration',
            'description' => 'expiration',
        ],
>>>>>>> 77b1e2748 (first)
    ],
    'actions' => [
        'create' => [
            'label' => 'Crea Cache Lock',
            'icon' => 'create',
<<<<<<< HEAD
=======
            'tooltip' => 'create',
>>>>>>> 77b1e2748 (first)
        ],
        'edit' => [
            'label' => 'Modifica Cache Lock',
            'icon' => 'edit',
<<<<<<< HEAD
=======
            'tooltip' => 'edit',
>>>>>>> 77b1e2748 (first)
        ],
        'delete' => [
            'label' => 'Elimina Cache Lock',
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
