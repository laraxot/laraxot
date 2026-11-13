<?php

declare(strict_types=1);

return [
    'actions' => [
        'clock' => [
            'label' => 'Clock In/Out',
        ],
        'viewTodayEntries' => [
            'label' => 'View Today Entries',
        ],
    ],
    'tracker' => [
        'status' => [
            'active' => 'Active Session',
            'completed' => 'Day Completed',
            'waiting' => 'Waiting for Clock-in',
        ],
        'no_entries_today' => [
            'message' => 'No clock entries today',
        ],
        'end_session' => [
            'label' => 'End session',
        ],
        'start_session' => [
            'label' => 'Start session',
        ],
        'entries_today' => [
            'description' => '{1} :count clock entry today|[2,*] :count clock entries today',
        ],
    ],
    'card' => [
        'hours_worked_today' => [
            'label' => 'Hours worked today',
        ],
        'last_entry' => [
            'label' => 'Last clock entry',
        ],
        'hours_today' => [
            'label' => 'Hours today',
        ],
        'week' => [
            'label' => 'Week',
        ],
        'month' => [
            'label' => 'Month',
        ],
        'auto_refresh' => [
            'label' => 'Refreshes automatically every 30 seconds',
        ],
    ],
];
