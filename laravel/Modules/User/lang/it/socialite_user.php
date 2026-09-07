<?php

declare(strict_types=1);

return [
    'fields' => [
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
    ],
];
