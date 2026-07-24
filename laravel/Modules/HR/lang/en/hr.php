<?php

declare(strict_types=1);

return [
    'navigation' => 'Human resources',
    'employee' => 'Employee',
    'employees' => 'Employees',
    'absence_request' => 'Absence request',
    'absence_requests' => 'Absence requests',
    'documents' => 'Documents',
    'medical_visits' => 'Medical visits',
    'trainings' => 'Trainings',
    'fields' => [
        'user' => 'Linked user',
        'first_name' => 'First name',
        'last_name' => 'Last name',
        'email' => 'Email',
        'phone' => 'Phone',
        'hired_at' => 'Hired at',
        'terminated_at' => 'Terminated at',
        'notes' => 'Notes',
        'is_active' => 'Active',
        'title' => 'Title',
        'kind' => 'Kind',
        'file' => 'File',
        'name' => 'Course name',
        'visited_at' => 'Visited at',
        'expires_at' => 'Expires at',
        'completed_at' => 'Completed at',
        'absence_type' => 'Absence type',
        'starts_at' => 'Starts at',
        'ends_at' => 'Ends at',
        'status' => 'Status',
    ],
    'statuses' => [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
    ],
    'absence_types' => [
        'vacation' => 'Vacation',
        'leave' => 'Leave',
        'sick' => 'Sick',
        'injury' => 'Injury',
    ],
    'actions' => [
        'approve' => 'Approve',
        'reject' => 'Reject',
        'download' => 'Download',
    ],
];
