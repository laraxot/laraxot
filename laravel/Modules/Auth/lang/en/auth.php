<?php

declare(strict_types=1);

return [
    'navigation' => 'Users',
    'user' => 'User',
    'users' => 'Users',
    'invalid_credentials' => 'Invalid credentials.',
    'no_role_assigned' => 'No role assigned.',
    'fields' => [
        'name' => 'Name',
        'email' => 'Email',
        'password' => 'Password',
        'password_confirmation' => 'Confirm password',
        'roles' => 'Roles',
        'approval_status' => 'Approval status',
    ],
    'hierarchy' => [
        'manager' => 'Manager',
        'manager_hint' => 'The selected user will also see the work of assigned collaborators.',
        'none' => 'None',
    ],
    'status' => [
        'pending' => 'Pending',
        'approved' => 'Approved',
    ],
    'actions' => [
        'approve' => 'Approve',
    ],
    'two_factor' => [
        'title' => 'Two factor verification',
        'code_sent' => 'We sent you a code by email.',
        'invalid_code' => 'Invalid or expired code.',
    ],
];
