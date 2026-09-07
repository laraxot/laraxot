<?php

declare(strict_types=1);

return [
    'fields' => [
        'id' => ['label' => 'id', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'name' => ['label' => 'name', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'team_id' => ['label' => 'team_id', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'guard_name' => ['label' => 'guard_name'],
        'display_name' => ['label' => 'display_name'],
        'description' => ['label' => 'description'],
        'created_at' => ['label' => 'created_at'],
        'updated_at' => ['label' => 'updated_at'],
    ],
    'navigation' => [
        'name' => 'Roles',
        'plural' => 'Roles',
        'group' => ['name' => 'General', 'description' => 'General Settings'],
        'label' => 'Roles',
        'sort' => 1,
        'icon' => 'heroicon-o-collection',
    ],
    'label' => 'Roles',
    'plural_label' => 'Roles (Plurale)',
    'actions' => [
        'create' => ['label' => 'Crea Roles', 'icon' => 'create', 'tooltip' => 'create'],
        'edit' => ['label' => 'Modifica Roles'],
        'delete' => ['label' => 'Elimina Roles'],
    ],
];
