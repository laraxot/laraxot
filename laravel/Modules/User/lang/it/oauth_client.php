<?php

declare(strict_types=1);

return [
<<<<<<< HEAD
<<<<<<< HEAD
    'navigation' => ['label' => 'Client OAuth', 'plural_label' => 'Client OAuth', 'group' => 'OAuth', 'icon' => 'heroicon-o-key', 'sort' => 89],
    'label' => 'Client OAuth',
    'plural_label' => 'Client OAuth',
    'fields' => [
        'name' => ['label' => 'Nome', 'tooltip' => 'Nome del client', 'placeholder' => 'Inserisci il nome', 'helper_text' => 'Nome identificativo del client', 'description' => 'Nome del client OAuth'],
        'redirect' => ['label' => 'Redirect URI', 'tooltip' => 'URI di redirect', 'placeholder' => 'https://esempio.it/callback', 'helper_text' => 'URI dove sarà reindirizzato dopo l\'autenticazione', 'description' => 'URI di redirect per OAuth'],
        'secret' => ['label' => 'Secret', 'tooltip' => 'Secret del client', 'placeholder' => 'Inserisci il secret', 'helper_text' => 'Secret per l\'autenticazione', 'description' => 'Secret del client'],
        'password_client' => ['label' => 'Client Password', 'tooltip' => 'Tipo client password', 'helper_text' => 'Indica se è un client di tipo password', 'description' => 'Flag per client di tipo password', 'placeholder' => 'password_client'],
        'redirect_callback' => ['label' => 'Redirect Callback', 'tooltip' => 'URI di callback', 'placeholder' => 'https://esempio.it/callback', 'helper_text' => 'URI per il callback', 'description' => 'URI di callback'],
        'user_id' => ['label' => 'user_id', 'placeholder' => 'user_id', 'helper_text' => 'user_id', 'description' => 'user_id'],
        'provider' => ['label' => 'provider', 'placeholder' => 'provider', 'helper_text' => 'provider', 'description' => 'provider'],
        'owner' => [
            'name' => ['label' => 'owner.name'],
        ],
        'created_at' => ['label' => 'created_at'],
        'updated_at' => ['label' => 'updated_at'],
        'personal_access_client' => ['label' => 'personal_access_client', 'placeholder' => 'personal_access_client', 'helper_text' => 'personal_access_client', 'description' => 'personal_access_client'],
    ],
    'actions' => [
        'create' => ['label' => 'Crea Client', 'tooltip' => 'Crea un nuovo client', 'helper_text' => 'Crea un nuovo client OAuth', 'description' => 'Azione per creare', 'icon' => 'create'],
        'edit' => ['label' => 'Modifica Client', 'tooltip' => 'Modifica il client', 'helper_text' => 'Modifica il client esistente', 'description' => 'Azione per modificare', 'icon' => 'edit'],
        'delete' => ['label' => 'Elimina Client', 'tooltip' => 'Elimina il client', 'helper_text' => 'Elimina il client', 'description' => 'Azione per eliminare', 'icon' => 'delete'],
        'logout' => ['label' => 'Logout', 'tooltip' => 'Disconnettiti', 'helper_text' => 'Esci dall\'account', 'description' => 'Azione di logout', 'icon' => 'heroicon-o-arrow-right-on-rectangle'],
        'create_personal_access_client' => ['label' => 'create_personal_access_client', 'icon' => 'create_personal_access_client', 'tooltip' => 'create_personal_access_client'],
        'create_password_grant_client' => ['label' => 'create_password_grant_client', 'icon' => 'create_password_grant_client', 'tooltip' => 'create_password_grant_client'],
        'create_client_credentials_client' => ['label' => 'create_client_credentials_client', 'icon' => 'create_client_credentials_client', 'tooltip' => 'create_client_credentials_client'],
        'createAnother' => ['label' => 'createAnother', 'icon' => 'createAnother', 'tooltip' => 'createAnother'],
        'save' => ['label' => 'save', 'icon' => 'save', 'tooltip' => 'save'],
        'view' => ['label' => 'view', 'icon' => 'view', 'tooltip' => 'view'],
    ],
    'messages' => ['created' => 'Client creato con successo', 'updated' => 'Client aggiornato con successo', 'deleted' => 'Client eliminato con successo'],
=======
=======
>>>>>>> 87273113 (.)
    'navigation' => [
        'label' => 'Client OAuth',
        'plural_label' => 'Client OAuth',
        'group' => 'OAuth',
        'icon' => 'heroicon-o-key',
        'sort' => 89,
    ],
    'label' => 'Client OAuth',
    'plural_label' => 'Client OAuth',
    'fields' => [
        'name' => [
            'label' => 'Nome',
            'tooltip' => 'Nome del client',
            'placeholder' => 'Inserisci il nome',
            'helper_text' => 'Nome identificativo del client',
            'description' => 'Nome del client OAuth',
        ],
        'redirect' => [
            'label' => 'Redirect URI',
            'tooltip' => 'URI di redirect',
            'placeholder' => 'https://esempio.it/callback',
            'helper_text' => 'URI dove sarà reindirizzato dopo l\'autenticazione',
            'description' => 'URI di redirect per OAuth',
        ],
        'secret' => [
            'label' => 'Secret',
            'tooltip' => 'Secret del client',
            'placeholder' => 'Inserisci il secret',
            'helper_text' => 'Secret per l\'autenticazione',
            'description' => 'Secret del client',
        ],
        'password_client' => [
            'label' => 'Client Password',
            'tooltip' => 'Tipo client password',
            'helper_text' => 'Indica se è un client di tipo password',
            'description' => 'Flag per client di tipo password',
        ],
        'redirect_callback' => [
            'label' => 'Redirect Callback',
            'tooltip' => 'URI di callback',
            'placeholder' => 'https://esempio.it/callback',
            'helper_text' => 'URI per il callback',
            'description' => 'URI di callback',
        ],
    ],
    'actions' => [
        'create' => [
            'label' => 'Crea Client',
            'tooltip' => 'Crea un nuovo client',
            'helper_text' => 'Crea un nuovo client OAuth',
            'description' => 'Azione per creare',
        ],
        'edit' => [
            'label' => 'Modifica Client',
            'tooltip' => 'Modifica il client',
            'helper_text' => 'Modifica il client esistente',
            'description' => 'Azione per modificare',
        ],
        'delete' => [
            'label' => 'Elimina Client',
            'tooltip' => 'Elimina il client',
            'helper_text' => 'Elimina il client',
            'description' => 'Azione per eliminare',
        ],
        'logout' => [
            'label' => 'Logout',
            'tooltip' => 'Disconnettiti',
            'helper_text' => 'Esci dall\'account',
            'description' => 'Azione di logout',
            'icon' => 'heroicon-o-arrow-right-on-rectangle',
        ],
    ],
    'messages' => [
        'created' => 'Client creato con successo',
        'updated' => 'Client aggiornato con successo',
        'deleted' => 'Client eliminato con successo',
    ],
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
    'navigation' => ['label' => 'Client OAuth', 'plural_label' => 'Client OAuth', 'group' => 'OAuth', 'icon' => 'heroicon-o-key', 'sort' => 89],
    'label' => 'Client OAuth',
    'plural_label' => 'Client OAuth',
    'fields' => [
        'name' => ['label' => 'Nome', 'tooltip' => 'Nome del client', 'placeholder' => 'Inserisci il nome', 'helper_text' => 'Nome identificativo del client', 'description' => 'Nome del client OAuth'],
        'redirect' => ['label' => 'Redirect URI', 'tooltip' => 'URI di redirect', 'placeholder' => 'https://esempio.it/callback', 'helper_text' => 'URI dove sarà reindirizzato dopo l\'autenticazione', 'description' => 'URI di redirect per OAuth'],
        'secret' => ['label' => 'Secret', 'tooltip' => 'Secret del client', 'placeholder' => 'Inserisci il secret', 'helper_text' => 'Secret per l\'autenticazione', 'description' => 'Secret del client'],
        'password_client' => ['label' => 'Client Password', 'tooltip' => 'Tipo client password', 'helper_text' => 'Indica se è un client di tipo password', 'description' => 'Flag per client di tipo password'],
        'redirect_callback' => ['label' => 'Redirect Callback', 'tooltip' => 'URI di callback', 'placeholder' => 'https://esempio.it/callback', 'helper_text' => 'URI per il callback', 'description' => 'URI di callback'],
        'provider' => ['placeholder' => 'provider'],
    ],
    'actions' => [
        'create' => ['label' => 'Crea Client', 'tooltip' => 'Crea un nuovo client', 'helper_text' => 'Crea un nuovo client OAuth', 'description' => 'Azione per creare', 'icon' => 'create'],
        'edit' => ['label' => 'Modifica Client', 'tooltip' => 'Modifica il client', 'helper_text' => 'Modifica il client esistente', 'description' => 'Azione per modificare', 'icon' => 'edit'],
        'delete' => ['label' => 'Elimina Client', 'tooltip' => 'Elimina il client', 'helper_text' => 'Elimina il client', 'description' => 'Azione per eliminare', 'icon' => 'delete'],
        'logout' => ['label' => 'Logout', 'tooltip' => 'Disconnettiti', 'helper_text' => 'Esci dall\'account', 'description' => 'Azione di logout', 'icon' => 'heroicon-o-arrow-right-on-rectangle'],
        'createAnother' => ['label' => 'createAnother', 'icon' => 'createAnother', 'tooltip' => 'createAnother'],
        'save' => ['label' => 'save', 'icon' => 'save', 'tooltip' => 'save'],
        'view' => ['label' => 'view', 'icon' => 'view', 'tooltip' => 'view'],
        'create_personal_access_client' => ['label' => 'create_personal_access_client', 'icon' => 'create_personal_access_client', 'tooltip' => 'create_personal_access_client'],
        'create_password_grant_client' => ['label' => 'create_password_grant_client', 'icon' => 'create_password_grant_client', 'tooltip' => 'create_password_grant_client'],
        'create_client_credentials_client' => ['label' => 'create_client_credentials_client', 'icon' => 'create_client_credentials_client', 'tooltip' => 'create_client_credentials_client'],
    ],
    'messages' => ['created' => 'Client creato con successo', 'updated' => 'Client aggiornato con successo', 'deleted' => 'Client eliminato con successo'],
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
];
