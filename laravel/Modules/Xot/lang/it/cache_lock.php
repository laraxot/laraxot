<?php

declare(strict_types=1);

return [
    'navigation' => [
<<<<<<< HEAD
        'name' => 'cache lock',
=======
        'name' => 'Blocco cache',
>>>>>>> laraxot/dev
        'plural' => 'cache locks',
        'group' => [
            'name' => 'Admin',
        ],
<<<<<<< HEAD
        'label' => 'cache lock.navigation',
        'icon' => 'cache lock.navigation',
=======
        'label' => 'Lock Cache',
        'icon' => 'heroicon-o-shield-exclamation',
>>>>>>> laraxot/dev
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
=======
>>>>>>> laraxot/dev
    ],
    'actions' => [
        'create' => [
            'label' => 'Crea Cache Lock',
            'icon' => 'create',
<<<<<<< HEAD
            'tooltip' => 'create',
=======
>>>>>>> laraxot/dev
        ],
        'edit' => [
            'label' => 'Modifica Cache Lock',
            'icon' => 'edit',
<<<<<<< HEAD
            'tooltip' => 'edit',
=======
>>>>>>> laraxot/dev
        ],
        'delete' => [
            'label' => 'Elimina Cache Lock',
            'icon' => 'delete',
<<<<<<< HEAD
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
=======
        ],
        'createAnother' => [
            'icon' => 'createAnother',
        ],
        'save' => [
            'icon' => 'save',
        ],
        'view' => [
            'icon' => 'view',
>>>>>>> laraxot/dev
        ],
    ],
];
