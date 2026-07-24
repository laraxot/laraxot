<?php

declare(strict_types=1);

return [
    'label' => 'Absence Request',
    'plural_label' => 'Absence Requests',
    'navigation_group' => 'Attendance & Absences',
    'fields' => [
        'section' => 'Request details',
        'user' => 'Employee',
        'type' => 'Type',
        'status' => 'Status',
        'starts_at' => 'Starts at',
        'ends_at' => 'Ends at',
        'notes' => 'Notes',
        'decided_by' => 'Decided by',
        'decided_at' => 'Decided at',
    ],
    'types' => [
        'vacation' => 'Vacation',
        'leave' => 'Leave',
        'sick' => 'Sick leave',
        'injury' => 'Injury',
    ],
    'statuses' => [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
    ],
    'actions' => [
        'approve' => 'Approve',
        'reject' => 'Reject',
    ],
];
