<?php

declare(strict_types=1);

return [
    'fields' => [
        'id' => ['label' => 'id'],
        'queue' => ['label' => 'queue'],
        'attempts' => ['label' => 'attempts'],
        'available_at' => ['label' => 'available_at'],
        'created_at' => ['label' => 'created_at'],
        'status' => ['label' => 'status'],
        'name' => ['label' => 'name'],
        'reserved_at' => ['label' => 'reserved_at'],
    ],
    'actions' => [
        'create' => ['label' => 'create', 'icon' => 'create', 'tooltip' => 'create'],
        'layout' => ['label' => 'layout', 'icon' => 'layout', 'tooltip' => 'layout'],
        'view' => ['label' => 'view', 'icon' => 'view', 'tooltip' => 'view'],
        'delete' => ['label' => 'delete', 'icon' => 'delete', 'tooltip' => 'delete'],
    ],
];
