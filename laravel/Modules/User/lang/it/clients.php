<?php

declare(strict_types=1);

return [
    'fields' => [
        'name' => ['label' => 'name', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'id' => ['label' => 'id'],
        'user_id' => ['label' => 'user_id'],
        'provider' => ['label' => 'provider'],
        'redirect' => ['label' => 'redirect'],
        'personal_access_client' => ['label' => 'personal_access_client'],
        'password_client' => ['label' => 'password_client'],
        'revoked' => ['label' => 'revoked'],
        'created_at' => ['label' => 'created_at'],
        'updated_at' => ['label' => 'updated_at'],
    ],
    'navigation' => [
        'name' => 'Clients',
        'plural' => 'Clients',
        'group' => ['name' => 'General', 'description' => 'General Settings'],
        'label' => 'Clients',
        'sort' => 1,
        'icon' => 'heroicon-o-collection',
    ],
    'label' => 'Clients',
    'plural_label' => 'Clients (Plurale)',
    'actions' => [
        'create' => ['label' => 'Crea Clients', 'icon' => 'create', 'tooltip' => 'create'],
        'edit' => ['label' => 'Modifica Clients'],
        'delete' => ['label' => 'Elimina Clients'],
    ],
];
