<?php

declare(strict_types=1);

return [
    'fields' => [
<<<<<<< HEAD
<<<<<<< HEAD
        'provider' => ['label' => 'provider', 'tooltip' => '', 'helper_text' => '', 'description' => '', 'placeholder' => 'provider'],
        'provider_id' => ['label' => 'provider_id', 'tooltip' => '', 'helper_text' => '', 'description' => '', 'placeholder' => 'provider_id'],
        'name' => ['label' => 'name', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'email' => ['label' => 'email', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'avatar' => ['label' => 'avatar', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'create' => ['label' => 'create', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'attach' => ['label' => 'attach', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'layout' => ['label' => 'layout', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'view' => ['label' => 'view', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'edit' => ['label' => 'edit', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'detach' => ['label' => 'detach', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'toggleColumns' => ['label' => 'toggleColumns', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'user_id' => ['label' => 'user_id', 'placeholder' => 'user_id', 'helper_text' => 'user_id', 'description' => 'user_id'],
        'provider_token' => ['label' => 'provider_token', 'placeholder' => 'provider_token', 'helper_text' => 'provider_token', 'description' => 'provider_token'],
        'provider_refresh_token' => ['label' => 'provider_refresh_token', 'placeholder' => 'provider_refresh_token', 'helper_text' => 'provider_refresh_token', 'description' => 'provider_refresh_token'],
        'provider_avatar' => ['label' => 'provider_avatar', 'placeholder' => 'provider_avatar', 'helper_text' => 'provider_avatar', 'description' => 'provider_avatar'],
        'user' => [
            'name' => ['label' => 'user.name'],
        ],
        'token' => ['label' => 'token'],
        'created_at' => ['label' => 'created_at'],
        'updated_at' => ['label' => 'updated_at'],
    ],
    'navigation' => ['sort' => 89, 'icon' => 'heroicon-o-user', 'group' => 'Authentication', 'label' => 'Social Authentications'],
    'label' => 'Socialite User',
    'plural_label' => 'Socialite User (Plurale)',
    'actions' => [
        'create' => ['label' => 'Crea Socialite User', 'icon' => 'create', 'tooltip' => 'create'],
        'edit' => ['label' => 'Modifica Socialite User', 'icon' => 'edit', 'tooltip' => 'edit'],
        'delete' => ['label' => 'Elimina Socialite User', 'icon' => 'delete', 'tooltip' => 'delete'],
        'save' => ['label' => 'save', 'icon' => 'save', 'tooltip' => 'save'],
        'view' => ['label' => 'view', 'icon' => 'view', 'tooltip' => 'view'],
    ],
    'sections' => [
        'User Information' => ['label' => 'User Information', 'heading' => 'User Information'],
        'Contact Information' => ['label' => 'Contact Information', 'heading' => 'Contact Information'],
        'Tokens' => ['label' => 'Tokens', 'heading' => 'Tokens'],
        'Timestamps' => ['label' => 'Timestamps', 'heading' => 'Timestamps'],
=======
=======
>>>>>>> 87273113 (.)
        'provider' => [
            'label' => 'provider',
        ],
        'provider_id' => [
            'label' => 'provider_id',
        ],
        'name' => [
            'label' => 'name',
        ],
        'email' => [
            'label' => 'email',
        ],
        'avatar' => [
            'label' => 'avatar',
        ],
        'create' => [
            'label' => 'create',
        ],
        'attach' => [
            'label' => 'attach',
        ],
        'layout' => [
            'label' => 'layout',
        ],
        'view' => [
            'label' => 'view',
        ],
        'edit' => [
            'label' => 'edit',
        ],
        'detach' => [
            'label' => 'detach',
        ],
        'toggleColumns' => [
            'label' => 'toggleColumns',
        ],
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        'provider' => ['label' => 'provider', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'provider_id' => ['label' => 'provider_id', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'name' => ['label' => 'name', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'email' => ['label' => 'email', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'avatar' => ['label' => 'avatar', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'create' => ['label' => 'create', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'attach' => ['label' => 'attach', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'layout' => ['label' => 'layout', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'view' => ['label' => 'view', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'edit' => ['label' => 'edit', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'detach' => ['label' => 'detach', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'toggleColumns' => ['label' => 'toggleColumns', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'provider_avatar' => ['label' => 'provider_avatar'],
        'created_at' => ['label' => 'created_at'],
    ],
    'navigation' => ['sort' => 89, 'icon' => 'heroicon-o-user', 'group' => 'Authentication', 'label' => 'Social Authentications'],
    'label' => 'Socialite User',
    'plural_label' => 'Socialite User (Plurale)',
    'actions' => [
        'create' => ['label' => 'Crea Socialite User', 'icon' => 'create', 'tooltip' => 'create'],
        'edit' => ['label' => 'Modifica Socialite User', 'icon' => 'edit', 'tooltip' => 'edit'],
        'delete' => ['label' => 'Elimina Socialite User', 'icon' => 'delete', 'tooltip' => 'delete'],
        'save' => ['label' => 'save', 'icon' => 'save', 'tooltip' => 'save'],
        'view' => ['label' => 'view', 'icon' => 'view', 'tooltip' => 'view'],
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    ],
];
