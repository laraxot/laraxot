<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
return [
    'navigation' => [
        'label' => 'Social Providers',
        'plural_label' => 'Social Providers',
        'group' => 'User Management',
        'icon' => 'heroicon-o-share',
        'sort' => 93,
    ],
    'label' => 'Social Provider',
    'plural_label' => 'Social Providers',
    'fields' => [
        'name' => [
            'label' => 'Name',
            'placeholder' => 'Enter provider name',
            'helper_text' => 'Identifying name for the social provider',
        ],
        'env_guide' => [
            'label' => 'Environment setup guide',
            'content' => '1) Create OAuth credentials in Google Cloud Console. 2) Add GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET to the server .env file. 3) Run: php artisan config:clear && php artisan cache:clear && php artisan optimize:clear. 4) Return here and enable the provider.',
        ],
        'client_id' => [
            'label' => 'Client ID',
            'placeholder' => 'Enter client ID',
        ],
        'client_secret' => [
            'label' => 'Client Secret',
            'placeholder' => 'Enter client secret',
        ],
        'redirect' => [
            'label' => 'Redirect URL',
            'placeholder' => 'Enter redirect URL',
        ],
        'scopes' => [
            'label' => 'Scopes',
            'helper_text' => 'OAuth scopes',
        ],
        'parameters' => [
            'label' => 'Parameters',
            'helper_text' => 'Additional URL parameters',
        ],
        'stateless' => [
            'label' => 'Stateless',
        ],
        'active' => [
            'label' => 'Active',
        ],
        'socialite' => [
            'label' => 'Socialite',
        ],
        'svg' => [
            'label' => 'Icon SVG',
            'placeholder' => '<svg>...</svg>',
        ],
    ],
    'actions' => [
        'create' => [
            'label' => 'Create',
        ],
    ],
    'messages' => [
        'created' => 'Provider created successfully',
        'updated' => 'Provider updated successfully',
        'deleted' => 'Provider deleted successfully',
=======
=======
>>>>>>> 87273113 (.)

return [
    'resources' => 'Risorse',
    'pages' => 'Pagine',
    'widgets' => 'Widgets',
    'navigation' => [
        'name' => 'Social Provider',
        'plural' => 'Social Providers',
        'group' => [
            'name' => 'Gestione Utenti',
            'description' => 'Gestione dei provider social',
        ],
        'label' => 'social provider',
        'sort' => '93',
        'icon' => 'user-user-social',
    ],
    'fields' => [
        'name' => 'Nome',
        'guard_name' => 'Guard',
        'permissions' => 'Permessi',
        'updated_at' => 'Aggiornato il',
        'first_name' => 'Nome',
        'last_name' => 'Cognome',
        'select_all' => [
            'name' => 'Seleziona Tutti',
            'message' => '',
        ],
        'applyFilters' => [
            'label' => 'applyFilters',
        ],
    ],
    'actions' => [
        'import' => [
            'fields' => [
                'import_file' => 'Seleziona un file XLS o CSV da caricare',
            ],
        ],
        'export' => [
            'filename_prefix' => 'Aree al',
            'columns' => [
                'name' => 'Nome area',
                'parent_name' => 'Nome area livello superiore',
            ],
        ],
        'create' => [
            'label' => 'create',
        ],
    ],
    'plural' => [
        'model' => [
            'label' => 'social provider.plural.model',
        ],
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
return [
    'navigation' => [
        'label' => 'Social Providers',
        'plural_label' => 'Social Providers',
        'group' => 'User Management',
        'icon' => 'heroicon-o-share',
        'sort' => 93,
    ],
    'label' => 'Social Provider',
    'plural_label' => 'Social Providers',
    'fields' => [
        'name' => [
            'label' => 'Name',
            'placeholder' => 'Enter provider name',
            'helper_text' => 'Identifying name for the social provider',
        ],
        'env_guide' => [
            'label' => 'Environment setup guide',
            'content' => '1) Create OAuth credentials in Google Cloud Console. 2) Add GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET to the server .env file. 3) Run: php artisan config:clear && php artisan cache:clear && php artisan optimize:clear. 4) Return here and enable the provider.',
        ],
        'client_id' => [
            'label' => 'Client ID',
            'placeholder' => 'Enter client ID',
        ],
        'client_secret' => [
            'label' => 'Client Secret',
            'placeholder' => 'Enter client secret',
        ],
        'redirect' => [
            'label' => 'Redirect URL',
            'placeholder' => 'Enter redirect URL',
        ],
        'scopes' => [
            'label' => 'Scopes',
            'helper_text' => 'OAuth scopes',
        ],
        'parameters' => [
            'label' => 'Parameters',
            'helper_text' => 'Additional URL parameters',
        ],
        'stateless' => [
            'label' => 'Stateless',
        ],
        'active' => [
            'label' => 'Active',
        ],
        'socialite' => [
            'label' => 'Socialite',
        ],
        'svg' => [
            'label' => 'Icon SVG',
            'placeholder' => '<svg>...</svg>',
        ],
    ],
    'actions' => [
        'create' => [
            'label' => 'Create',
        ],
    ],
    'messages' => [
        'created' => 'Provider created successfully',
        'updated' => 'Provider updated successfully',
        'deleted' => 'Provider deleted successfully',
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    ],
];
