<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'Env',
        'plural' => 'Env',
        'group' => ['name' => 'Admin'],
        'label' => 'env.navigation',
    ],
    'pages' => [
        'health_check_results' => [
            'buttons' => ['refresh' => 'Refresh'],
            'heading' => 'Application Health',
            'navigation' => ['group' => 'Settings', 'label' => 'Application Health'],
            'notifications' => ['check_results' => 'Check results from'],
        ],
    ],
    'label' => 'Env',
    'plural_label' => 'Env (Plurale)',
    'fields' => [
        'id' => ['label' => 'Identificativo', 'tooltip' => 'Identificativo univoco del record', 'helper_text' => '', 'description' => ''],
        'created_at' => ['label' => 'Data Creazione', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'updated_at' => ['label' => 'Ultima Modifica', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'app_url' => ['label' => 'app_url', 'placeholder' => 'app_url', 'helper_text' => 'app_url', 'description' => 'app_url'],
        'debugbar_enabled' => ['label' => 'debugbar_enabled', 'placeholder' => 'debugbar_enabled', 'helper_text' => 'debugbar_enabled', 'description' => 'debugbar_enabled'],
        'google_maps_api_key' => ['label' => 'google_maps_api_key', 'placeholder' => 'google_maps_api_key', 'helper_text' => 'google_maps_api_key', 'description' => 'google_maps_api_key'],
        'telegram_bot_token' => ['label' => 'telegram_bot_token', 'placeholder' => 'telegram_bot_token', 'helper_text' => 'telegram_bot_token', 'description' => 'telegram_bot_token'],
    ],
    'actions' => [
        'create' => ['label' => 'Crea Env'],
        'edit' => ['label' => 'Modifica Env'],
        'delete' => ['label' => 'Elimina Env'],
        'save' => ['label' => 'save', 'icon' => 'save', 'tooltip' => 'save'],
    ],
    'title' => 'env',
];
