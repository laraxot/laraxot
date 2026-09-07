<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'Reset Password',
        'plural' => 'Reset Password',
        'label' => 'Reset Password',
<<<<<<< HEAD
<<<<<<< HEAD
        'group' => ['name' => 'Sicurezza', 'description' => 'Gestione dei reset password e recupero credenziali'],
=======
=======
>>>>>>> 87273113 (.)
        'group' => [
            'name' => 'Sicurezza',
            'description' => 'Gestione dei reset password e recupero credenziali',
        ],
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
        'group' => ['name' => 'Sicurezza', 'description' => 'Gestione dei reset password e recupero credenziali'],
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        'sort' => 4,
        'icon' => 'heroicon-o-key',
    ],
    'label' => 'Password Reset',
    'plural_label' => 'Password Reset (Plurale)',
    'fields' => [
<<<<<<< HEAD
<<<<<<< HEAD
        'id' => ['label' => 'Identificativo', 'tooltip' => 'Identificativo univoco del record', 'helper_text' => '', 'description' => ''],
        'created_at' => ['label' => 'Data Creazione', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'updated_at' => ['label' => 'Ultima Modifica', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'email' => ['label' => 'email', 'placeholder' => 'email', 'helper_text' => 'email', 'description' => 'email'],
        'token' => ['label' => 'token', 'placeholder' => 'token', 'helper_text' => 'token', 'description' => 'token'],
    ],
    'actions' => [
        'create' => ['label' => 'Crea Password Reset', 'icon' => 'create', 'tooltip' => 'create'],
        'edit' => ['label' => 'Modifica Password Reset', 'icon' => 'edit', 'tooltip' => 'edit'],
        'delete' => ['label' => 'Elimina Password Reset', 'icon' => 'delete', 'tooltip' => 'delete'],
        'view' => ['label' => 'view', 'icon' => 'view', 'tooltip' => 'view'],
    ],
    'sections' => [
        'Password Reset Information' => ['label' => 'Password Reset Information', 'heading' => 'Password Reset Information'],
        'Timestamps' => ['label' => 'Timestamps', 'heading' => 'Timestamps'],
=======
=======
>>>>>>> 87273113 (.)
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
    'actions' => [
        'create' => [
            'label' => 'Crea Password Reset',
        ],
        'edit' => [
            'label' => 'Modifica Password Reset',
        ],
        'delete' => [
            'label' => 'Elimina Password Reset',
        ],
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
        'id' => ['label' => 'Identificativo', 'tooltip' => 'Identificativo univoco del record', 'helper_text' => '', 'description' => ''],
        'created_at' => ['label' => 'Data Creazione', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'updated_at' => ['label' => 'Ultima Modifica', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
    ],
    'actions' => [
        'create' => ['label' => 'Crea Password Reset', 'icon' => 'create', 'tooltip' => 'create'],
        'edit' => ['label' => 'Modifica Password Reset', 'icon' => 'edit', 'tooltip' => 'edit'],
        'delete' => ['label' => 'Elimina Password Reset', 'icon' => 'delete', 'tooltip' => 'delete'],
        'view' => ['label' => 'view', 'icon' => 'view', 'tooltip' => 'view'],
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    ],
];
