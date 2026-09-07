<?php

declare(strict_types=1);

return [
<<<<<<< HEAD
<<<<<<< HEAD
    'navigation' => ['label' => 'Team Invitations', 'group' => 'Teams', 'icon' => 'heroicon-o-envelope', 'sort' => 34],
    'label' => 'Team Invitation',
    'plural_label' => 'Team Invitation (Plurale)',
    'fields' => [
        'id' => ['label' => 'Identificativo', 'tooltip' => 'Identificativo univoco del record', 'helper_text' => '', 'description' => ''],
        'created_at' => ['label' => 'Data Creazione', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'updated_at' => ['label' => 'Ultima Modifica', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'team_id' => ['label' => 'team_id', 'placeholder' => 'team_id', 'helper_text' => 'team_id', 'description' => 'team_id'],
        'email' => ['label' => 'email', 'placeholder' => 'email', 'helper_text' => 'email', 'description' => 'email'],
        'role' => ['label' => 'role', 'placeholder' => 'role', 'helper_text' => 'role', 'description' => 'role'],
        'expires_at' => ['label' => 'expires_at'],
    ],
    'actions' => [
        'create' => ['label' => 'Crea Team Invitation', 'icon' => 'create', 'tooltip' => 'create'],
        'edit' => ['label' => 'Modifica Team Invitation', 'icon' => 'edit', 'tooltip' => 'edit'],
        'delete' => ['label' => 'Elimina Team Invitation', 'icon' => 'delete', 'tooltip' => 'delete'],
        'view' => ['label' => 'view', 'icon' => 'view', 'tooltip' => 'view'],
=======
=======
>>>>>>> 87273113 (.)
    'navigation' => [
        'label' => 'Team Invitations',
        'group' => 'Teams',
        'icon' => 'heroicon-o-envelope',
        'sort' => 34,
    ],
    'label' => 'Team Invitation',
    'plural_label' => 'Team Invitation (Plurale)',
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
    'actions' => [
        'create' => [
            'label' => 'Crea Team Invitation',
        ],
        'edit' => [
            'label' => 'Modifica Team Invitation',
        ],
        'delete' => [
            'label' => 'Elimina Team Invitation',
        ],
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
    'navigation' => ['label' => 'Team Invitations', 'group' => 'Teams', 'icon' => 'heroicon-o-envelope', 'sort' => 34],
    'label' => 'Team Invitation',
    'plural_label' => 'Team Invitation (Plurale)',
    'fields' => [
        'id' => ['label' => 'Identificativo', 'tooltip' => 'Identificativo univoco del record', 'helper_text' => '', 'description' => ''],
        'created_at' => ['label' => 'Data Creazione', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'updated_at' => ['label' => 'Ultima Modifica', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'email' => ['label' => 'email'],
        'role' => ['label' => 'role'],
        'expires_at' => ['label' => 'expires_at'],
    ],
    'actions' => [
        'create' => ['label' => 'Crea Team Invitation', 'icon' => 'create', 'tooltip' => 'create'],
        'edit' => ['label' => 'Modifica Team Invitation', 'icon' => 'edit', 'tooltip' => 'edit'],
        'delete' => ['label' => 'Elimina Team Invitation', 'icon' => 'delete', 'tooltip' => 'delete'],
        'view' => ['label' => 'view', 'icon' => 'view', 'tooltip' => 'view'],
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    ],
];
