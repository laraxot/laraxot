<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'Utente Tenant',
        'plural' => 'Utenti Tenant',
        'label' => 'Utenti Tenant',
<<<<<<< HEAD
<<<<<<< HEAD
        'group' => ['name' => 'Tenants', 'description' => 'Gestione degli utenti associati ai tenant'],
=======
=======
>>>>>>> 87273113 (.)
        'group' => [
            'name' => 'Tenants',
            'description' => 'Gestione degli utenti associati ai tenant',
        ],
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
        'group' => ['name' => 'Tenants', 'description' => 'Gestione degli utenti associati ai tenant'],
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        'sort' => 87,
        'icon' => 'heroicon-o-building-office',
    ],
    'label' => 'Tenant User',
    'plural_label' => 'Tenant User (Plurale)',
    'fields' => [
<<<<<<< HEAD
<<<<<<< HEAD
        'id' => ['label' => 'Identificativo', 'tooltip' => 'Identificativo univoco del record', 'helper_text' => '', 'description' => ''],
        'created_at' => ['label' => 'Data Creazione', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'updated_at' => ['label' => 'Ultima Modifica', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'tenant_id' => ['label' => 'tenant_id', 'placeholder' => 'tenant_id', 'helper_text' => 'tenant_id', 'description' => 'tenant_id'],
        'user_id' => ['label' => 'user_id', 'placeholder' => 'user_id', 'helper_text' => 'user_id', 'description' => 'user_id'],
        'role' => ['label' => 'role', 'placeholder' => 'role', 'helper_text' => 'role', 'description' => 'role'],
        'tenant' => [
            'name' => ['label' => 'tenant.name'],
        ],
        'user' => [
            'name' => ['label' => 'user.name'],
        ],
    ],
    'actions' => [
        'create' => ['label' => 'Crea Tenant User', 'icon' => 'create', 'tooltip' => 'create'],
        'edit' => ['label' => 'Modifica Tenant User', 'icon' => 'edit', 'tooltip' => 'edit'],
        'delete' => ['label' => 'Elimina Tenant User', 'icon' => 'delete', 'tooltip' => 'delete'],
        'createAnother' => ['label' => 'createAnother', 'icon' => 'createAnother', 'tooltip' => 'createAnother'],
        'save' => ['label' => 'save', 'icon' => 'save', 'tooltip' => 'save'],
        'view' => ['label' => 'view', 'icon' => 'view', 'tooltip' => 'view'],
    ],
    'sections' => [
        'Tenant User Information' => ['label' => 'Tenant User Information', 'heading' => 'Tenant User Information'],
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
            'label' => 'Crea Tenant User',
        ],
        'edit' => [
            'label' => 'Modifica Tenant User',
        ],
        'delete' => [
            'label' => 'Elimina Tenant User',
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
        'create' => ['label' => 'Crea Tenant User', 'icon' => 'create', 'tooltip' => 'create'],
        'edit' => ['label' => 'Modifica Tenant User', 'icon' => 'edit', 'tooltip' => 'edit'],
        'delete' => ['label' => 'Elimina Tenant User', 'icon' => 'delete', 'tooltip' => 'delete'],
        'createAnother' => ['label' => 'createAnother', 'icon' => 'createAnother', 'tooltip' => 'createAnother'],
        'save' => ['label' => 'save', 'icon' => 'save', 'tooltip' => 'save'],
        'view' => ['label' => 'view', 'icon' => 'view', 'tooltip' => 'view'],
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    ],
];
