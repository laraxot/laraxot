<?php

declare(strict_types=1);

return [
<<<<<<< HEAD
<<<<<<< HEAD
    'navigation' => ['label' => 'Provider SSO', 'group' => 'Authentication', 'icon' => 'heroicon-o-identification', 'sort' => 3],
    'label' => 'Provider SSO',
    'plural_label' => 'Provider SSO',
    'fields' => [
        'id' => ['label' => 'Identificativo', 'tooltip' => 'Identificativo univoco del record', 'helper_text' => '', 'description' => ''],
        'created_at' => ['label' => 'Data Creazione', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'updated_at' => ['label' => 'Ultima Modifica', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'name' => ['label' => 'name', 'placeholder' => 'name', 'helper_text' => 'name', 'description' => 'name'],
        'display_name' => ['label' => 'display_name', 'placeholder' => 'display_name', 'helper_text' => 'display_name', 'description' => 'display_name'],
        'type' => ['label' => 'type', 'placeholder' => 'type', 'helper_text' => 'type', 'description' => 'type'],
        'entity_id' => ['label' => 'entity_id', 'placeholder' => 'entity_id', 'helper_text' => 'entity_id', 'description' => 'entity_id'],
        'client_id' => ['label' => 'client_id', 'placeholder' => 'client_id', 'helper_text' => 'client_id', 'description' => 'client_id'],
        'client_secret' => ['label' => 'client_secret', 'placeholder' => 'client_secret', 'helper_text' => 'client_secret', 'description' => 'client_secret'],
        'redirect_url' => ['label' => 'redirect_url', 'placeholder' => 'redirect_url', 'helper_text' => 'redirect_url', 'description' => 'redirect_url'],
        'metadata_url' => ['label' => 'metadata_url', 'placeholder' => 'metadata_url', 'helper_text' => 'metadata_url', 'description' => 'metadata_url'],
        'scopes' => ['label' => 'scopes', 'placeholder' => 'scopes', 'helper_text' => 'scopes', 'description' => 'scopes'],
        'settings' => ['label' => 'settings', 'placeholder' => 'settings', 'helper_text' => 'settings', 'description' => 'settings'],
        'domain_whitelist' => ['label' => 'domain_whitelist', 'placeholder' => 'domain_whitelist', 'helper_text' => 'domain_whitelist', 'description' => 'domain_whitelist'],
        'role_mapping' => ['label' => 'role_mapping', 'placeholder' => 'role_mapping', 'helper_text' => 'role_mapping', 'description' => 'role_mapping'],
        'is_active' => ['label' => 'is_active', 'placeholder' => 'is_active', 'helper_text' => 'is_active', 'description' => 'is_active'],
    ],
    'actions' => [
        'create' => ['label' => 'Crea Sso Provider', 'icon' => 'create', 'tooltip' => 'create'],
        'edit' => ['label' => 'Modifica Sso Provider', 'icon' => 'edit', 'tooltip' => 'edit'],
        'delete' => ['label' => 'Elimina Sso Provider', 'icon' => 'delete', 'tooltip' => 'delete'],
        'createAnother' => ['label' => 'createAnother', 'icon' => 'createAnother', 'tooltip' => 'createAnother'],
        'save' => ['label' => 'save', 'icon' => 'save', 'tooltip' => 'save'],
        'view' => ['label' => 'view', 'icon' => 'view', 'tooltip' => 'view'],
    ],
    'sections' => [
        'empty' => ['label' => '', 'heading' => ''],
=======
=======
>>>>>>> 87273113 (.)
    'navigation' => [
        'label' => 'Provider SSO',
        'group' => 'Authentication',
        'icon' => 'heroicon-o-identification',
        'sort' => 3,
    ],
    'label' => 'Provider SSO',
    'plural_label' => 'Provider SSO',
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
            'label' => 'Crea Sso Provider',
        ],
        'edit' => [
            'label' => 'Modifica Sso Provider',
        ],
        'delete' => [
            'label' => 'Elimina Sso Provider',
        ],
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
    'navigation' => ['label' => 'Provider SSO', 'group' => 'Authentication', 'icon' => 'heroicon-o-identification', 'sort' => 3],
    'label' => 'Provider SSO',
    'plural_label' => 'Provider SSO',
    'fields' => [
        'id' => ['label' => 'Identificativo', 'tooltip' => 'Identificativo univoco del record', 'helper_text' => '', 'description' => ''],
        'created_at' => ['label' => 'Data Creazione', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'updated_at' => ['label' => 'Ultima Modifica', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'name' => ['label' => 'name'],
        'display_name' => ['label' => 'display_name'],
        'type' => ['label' => 'type'],
        'is_active' => ['label' => 'is_active'],
    ],
    'actions' => [
        'create' => ['label' => 'Crea Sso Provider', 'icon' => 'create', 'tooltip' => 'create'],
        'edit' => ['label' => 'Modifica Sso Provider', 'icon' => 'edit', 'tooltip' => 'edit'],
        'delete' => ['label' => 'Elimina Sso Provider', 'icon' => 'delete', 'tooltip' => 'delete'],
        'createAnother' => ['label' => 'createAnother', 'icon' => 'createAnother', 'tooltip' => 'createAnother'],
        'save' => ['label' => 'save', 'icon' => 'save', 'tooltip' => 'save'],
        'view' => ['label' => 'view', 'icon' => 'view', 'tooltip' => 'view'],
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    ],
];
