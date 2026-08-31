<?php

declare(strict_types=1);

return [
    'fields' => [
        'id' => ['label' => 'id'],
        'name' => ['label' => 'name', 'placeholder' => 'name', 'helper_text' => 'name', 'description' => 'name'],
        'created_at' => ['label' => 'created_at'],
    ],
    'actions' => [
        'create' => ['label' => 'create', 'icon' => 'create', 'tooltip' => 'create'],
        'associate' => ['label' => 'associate', 'icon' => 'associate', 'tooltip' => 'associate'],
        'edit' => ['label' => 'edit', 'icon' => 'edit', 'tooltip' => 'edit'],
        'dissociate' => ['label' => 'dissociate', 'icon' => 'dissociate', 'tooltip' => 'dissociate'],
        'delete' => ['label' => 'delete', 'icon' => 'delete', 'tooltip' => 'delete'],
    ],
    'label' => 'role permissions',
    'navigation' => ['label' => 'role permissions.navigation', 'icon' => 'role permissions.navigation', 'sort' => 86],
];
