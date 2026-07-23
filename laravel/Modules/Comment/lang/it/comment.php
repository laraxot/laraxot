<?php

declare(strict_types=1);

return [
    'actions' => [
        'approve' => ['label' => 'approve', 'icon' => 'approve', 'tooltip' => 'approve'],
        'reject' => ['label' => 'reject', 'icon' => 'reject', 'tooltip' => 'reject'],
    ],
    'fields' => [
        'moderation' => ['label' => 'moderation'],
        'id' => ['label' => 'id'],
        'original_text' => ['label' => 'original_text'],
        'commentator_display' => ['label' => 'commentator_display'],
        'commentable_type' => ['label' => 'commentable_type'],
        'moderation_status' => ['label' => 'moderation_status'],
        'parent_id' => ['label' => 'parent_id'],
        'created_at' => ['label' => 'created_at'],
    ],
];
