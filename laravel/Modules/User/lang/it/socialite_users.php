<?php

declare(strict_types=1);

return [
    'fields' => [
        'provider' => ['label' => 'provider', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'provider_id' => ['label' => 'provider_id', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'name' => ['label' => 'name', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'email' => ['label' => 'email', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'avatar' => ['label' => 'avatar', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'id' => ['label' => 'id'],
        'created_at' => ['label' => 'created_at'],
        'user_id' => ['label' => 'user_id'],
        'nickname' => ['label' => 'nickname'],
        'token' => ['label' => 'token'],
        'refresh_token' => ['label' => 'refresh_token'],
        'updated_at' => ['label' => 'updated_at'],
    ],
    'navigation' => [
        'name' => 'Socialite Users',
        'plural' => 'Socialite Users',
        'group' => ['name' => 'General', 'description' => 'General Settings'],
        'label' => 'Socialite Users',
        'sort' => 1,
        'icon' => 'heroicon-o-collection',
    ],
    'label' => 'Socialite Users',
    'plural_label' => 'Socialite Users (Plurale)',
    'actions' => [
        'create' => ['label' => 'Crea Socialite Users', 'icon' => 'create', 'tooltip' => 'create'],
        'edit' => ['label' => 'Modifica Socialite Users'],
        'delete' => ['label' => 'Elimina Socialite Users'],
    ],
];
