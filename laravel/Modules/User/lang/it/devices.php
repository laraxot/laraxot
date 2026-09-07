<?php

declare(strict_types=1);

return [
    'fields' => [
        'login_at' => ['label' => 'login_at', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'logout_at' => ['label' => 'logout_at', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'id' => ['label' => 'id'],
        'uuid' => ['label' => 'uuid'],
        'user_id' => ['label' => 'user_id'],
        'device' => ['label' => 'device'],
        'platform' => ['label' => 'platform'],
        'browser' => ['label' => 'browser'],
        'ip' => ['label' => 'ip'],
        'is_desktop' => ['label' => 'is_desktop'],
        'is_mobile' => ['label' => 'is_mobile'],
        'is_phone' => ['label' => 'is_phone'],
        'is_robot' => ['label' => 'is_robot'],
        'is_tablet' => ['label' => 'is_tablet'],
        'created_at' => ['label' => 'created_at'],
        'updated_at' => ['label' => 'updated_at'],
        'updated_by' => ['label' => 'updated_by'],
        'created_by' => ['label' => 'created_by'],
    ],
    'navigation' => [
        'name' => 'Devices',
        'plural' => 'Devices',
        'group' => ['name' => 'General', 'description' => 'General Settings'],
        'label' => 'Devices',
        'sort' => 1,
        'icon' => 'heroicon-o-collection',
    ],
    'label' => 'Devices',
    'plural_label' => 'Devices (Plurale)',
    'actions' => [
        'create' => ['label' => 'Crea Devices', 'icon' => 'create', 'tooltip' => 'create'],
        'edit' => ['label' => 'Modifica Devices'],
        'delete' => ['label' => 'Elimina Devices'],
    ],
];
