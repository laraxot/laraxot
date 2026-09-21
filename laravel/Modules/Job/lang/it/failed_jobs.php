<?php

declare(strict_types=1);

return [
    'fields' => [
        'id' => ['label' => 'id'],
        'connection' => ['label' => 'connection'],
        'queue' => ['label' => 'queue'],
        'failed_at' => ['label' => 'failed_at'],
        'exception' => ['label' => 'exception'],
        'uuid' => ['label' => 'uuid'],
    ],
    'actions' => [
        'view' => ['label' => 'view', 'icon' => 'view', 'tooltip' => 'view'],
        'edit' => ['label' => 'edit', 'icon' => 'edit', 'tooltip' => 'edit'],
        'delete' => ['label' => 'delete', 'icon' => 'delete', 'tooltip' => 'delete'],
        'retry_all' => ['label' => 'retry_all', 'icon' => 'retry_all', 'tooltip' => 'retry_all'],
        'delete_all' => ['label' => 'delete_all', 'icon' => 'delete_all', 'tooltip' => 'delete_all'],
    ],
];
