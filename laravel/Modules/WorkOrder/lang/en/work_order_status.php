<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'Work order statuses',
        'group' => 'Work orders',
        'icon' => 'heroicon-o-flag',
        'sort' => 90,
    ],
    'actions' => [
        'create' => ['label' => 'New status', 'icon' => 'heroicon-o-plus', 'tooltip' => 'Define a new progress status'],
        'edit' => ['label' => 'Edit', 'icon' => 'heroicon-o-pencil-square', 'tooltip' => 'Edit the status'],
        'delete' => ['label' => 'Delete', 'icon' => 'heroicon-o-trash', 'tooltip' => 'Delete the status'],
    ],
    'fields' => [
        'code' => [
            'label' => 'Code',
            'placeholder' => 'e.g. in_progress',
            'helper_text' => 'Technical identifier used by the code: do not change it lightly.',
        ],
        'name' => ['label' => 'Name', 'placeholder' => 'e.g. In progress'],
        'color' => [
            'label' => 'Colour',
            'helper_text' => 'Badge colour in listings.',
        ],
        'sort_order' => [
            'label' => 'Sort order',
            'helper_text' => 'Position in the flow: from the first status to the last.',
        ],
        'is_terminal' => [
            'label' => 'Final status',
            'helper_text' => 'Marks the work order as closed: no further progress.',
        ],
        'work_orders_count' => ['label' => 'Work orders'],
    ],
];
