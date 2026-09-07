<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'Utente Team',
        'plural' => 'Utenti Team',
        'label' => 'Utenti Team',
<<<<<<< HEAD
<<<<<<< HEAD
        'group' => ['name' => 'Teams', 'description' => 'Gestione degli utenti associati ai team'],
=======
=======
>>>>>>> 87273113 (.)
        'group' => [
            'name' => 'Teams',
            'description' => 'Gestione degli utenti associati ai team',
        ],
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
        'group' => ['name' => 'Teams', 'description' => 'Gestione degli utenti associati ai team'],
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        'sort' => 65,
        'icon' => 'heroicon-o-user-group',
    ],
    'label' => 'Team User',
    'plural_label' => 'Team User (Plurale)',
    'fields' => [
<<<<<<< HEAD
<<<<<<< HEAD
        'id' => ['label' => 'Identificativo', 'tooltip' => 'Identificativo univoco del record', 'helper_text' => '', 'description' => ''],
        'created_at' => ['label' => 'Data Creazione', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'updated_at' => ['label' => 'Ultima Modifica', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'team' => [
            'name' => ['label' => 'team.name'],
        ],
        'user' => [
            'name' => ['label' => 'user.name'],
        ],
        'role' => ['label' => 'role'],
    ],
    'actions' => [
        'create' => ['label' => 'Crea Team User', 'icon' => 'create', 'tooltip' => 'create'],
        'edit' => ['label' => 'Modifica Team User', 'icon' => 'edit', 'tooltip' => 'edit'],
        'delete' => ['label' => 'Elimina Team User', 'icon' => 'delete', 'tooltip' => 'delete'],
        'createAnother' => ['label' => 'createAnother', 'icon' => 'createAnother', 'tooltip' => 'createAnother'],
        'save' => ['label' => 'save', 'icon' => 'save', 'tooltip' => 'save'],
        'view' => ['label' => 'view', 'icon' => 'view', 'tooltip' => 'view'],
    ],
    'sections' => [
        'empty' => ['label' => '', 'heading' => ''],
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
            'label' => 'Crea Team User',
        ],
        'edit' => [
            'label' => 'Modifica Team User',
        ],
        'delete' => [
            'label' => 'Elimina Team User',
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
        'create' => ['label' => 'Crea Team User', 'icon' => 'create', 'tooltip' => 'create'],
        'edit' => ['label' => 'Modifica Team User', 'icon' => 'edit', 'tooltip' => 'edit'],
        'delete' => ['label' => 'Elimina Team User', 'icon' => 'delete', 'tooltip' => 'delete'],
        'createAnother' => ['label' => 'createAnother', 'icon' => 'createAnother', 'tooltip' => 'createAnother'],
        'save' => ['label' => 'save', 'icon' => 'save', 'tooltip' => 'save'],
        'view' => ['label' => 'view', 'icon' => 'view', 'tooltip' => 'view'],
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    ],
];
