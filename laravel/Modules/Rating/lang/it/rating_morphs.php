<?php

declare(strict_types=1);

return [
    'fields' => [
        'id' => ['label' => 'id'],
        'rating_id' => ['label' => 'rating_id'],
        'user_id' => ['label' => 'user_id'],
        'model_type' => ['label' => 'model_type'],
        'model_id' => ['label' => 'model_id'],
        'value' => ['label' => 'value'],
        'note' => ['label' => 'note'],
        'is_winner' => ['label' => 'is_winner'],
        'reward' => ['label' => 'reward'],
        'created_at' => ['label' => 'created_at'],
        'updated_at' => ['label' => 'updated_at'],
    ],
    'actions' => [
        'delete' => ['label' => 'delete', 'icon' => 'delete', 'tooltip' => 'delete'],
    ],
];
