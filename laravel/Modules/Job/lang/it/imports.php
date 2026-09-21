<?php

declare(strict_types=1);

return [
    'fields' => [
        'id' => ['label' => 'id'],
        'name' => ['label' => 'name'],
        'created_at' => ['label' => 'created_at'],
        'file_name' => ['label' => 'file_name'],
        'processed_rows' => ['label' => 'processed_rows'],
        'total_rows' => ['label' => 'total_rows'],
        'successful_rows' => ['label' => 'successful_rows'],
        'completed_at' => ['label' => 'completed_at'],
    ],
    'actions' => [
        'edit' => ['label' => 'edit', 'icon' => 'edit', 'tooltip' => 'edit'],
    ],
];
