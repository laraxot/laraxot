<?php

declare(strict_types=1);

return [
    'label' => 'AI Action Proposal',
    'plural_label' => 'AI Action Proposals',
    'navigation_group' => 'AI Assistant',
    'fields' => [
        'section' => 'Proposal details',
        'thread' => 'Thread',
        'type' => 'Type',
        'status' => 'Status',
        'preview' => 'Preview',
        'error' => 'Error',
        'confirmed_at' => 'Confirmed at',
        'executed_at' => 'Executed at',
    ],
    'statuses' => [
        'pending' => 'Pending',
        'cancelled' => 'Cancelled',
        'confirmed' => 'Confirmed',
        'executed' => 'Executed',
        'failed' => 'Failed',
    ],
    'actions' => [
        'confirm' => 'Confirm',
        'cancel' => 'Cancel',
    ],
];
