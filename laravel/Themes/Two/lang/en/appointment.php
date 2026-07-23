<?php

declare(strict_types=1);

return [
    'hero' => [
        'accepted_appointments' => [
            'title' => 'Accepted Appointments',
            'description' => 'View all appointments that have been confirmed',
            'back_button' => [
                'label' => 'Go back',
                'tooltip' => 'Return to previous page',
            ],
        ],
        'pending_appointments' => [
            'title' => 'Pending Appointments',
            'description' => 'View all appointments waiting for confirmation',
        ],
        'completed_appointments' => [
            'title' => 'Completed Appointments',
            'description' => 'View all appointments that have been completed',
        ],
        'rejected_appointments' => [
            'title' => 'Rejected Appointments',
            'description' => 'View all appointments that have been rejected',
        ],
        'entry_appointments' => [
            'title' => 'Incoming Appointments',
            'description' => 'View all new appointment requests',
        ],
    ],
    'states' => [
        'pending' => 'Pending',
        'confirmed' => 'Confirmed',
        'scheduled' => 'Scheduled',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        'rejected' => 'Rejected',
        'no_show' => 'No Show',
        'rescheduled' => 'Rescheduled',
    ],
    'accepted_appointments' => [
        'title' => 'Appuntamenti Accettati',
        'back_home' => 'Torna alla Home',
        'redirecting' => 'Reindirizzamento in corso...',
        'click_here' => 'clicca qui',
        'if_not_redirected' => 'Se non vieni reindirizzato automaticamente, :link.',
    ],
];
