<?php

declare(strict_types=1);

return [
    'active' => [
        'label' => 'Active',
        'color' => 'success',
        'description' => 'User is active and operational in the system',
        'icon' => 'heroicon-o-check-circle',
        'hex_color' => '#10b981',
    ],
    'inactive' => [
        'label' => 'Inactive',
        'color' => 'danger',
        'description' => 'User is not active, access disabled',
        'icon' => 'heroicon-o-x-circle',
        'hex_color' => '#ef4444',
    ],
    'pending' => [
        'label' => 'Pending',
        'color' => 'warning',
        'description' => 'Registration pending approval',
        'icon' => 'heroicon-o-clock',
        'hex_color' => '#f59e0b',
    ],
    'rejected' => [
        'label' => 'Rejected',
        'color' => 'danger',
        'description' => 'Registration rejected by the system',
        'icon' => 'heroicon-o-x-circle',
        'hex_color' => '#dc2626',
    ],
    'integration_requested' => [
        'label' => 'Integration Requested',
        'color' => 'warning',
        'description' => 'Integration request awaiting processing',
        'icon' => 'heroicon-o-arrow-path',
        'hex_color' => '#d97706',
    ],
    'integration_approved' => [
        'label' => 'Integration Approved',
        'color' => 'success',
        'description' => 'Integration request has been approved',
        'icon' => 'heroicon-o-check-circle',
        'hex_color' => '#059669',
    ],
    'integration_rejected' => [
        'label' => 'Integration Rejected',
        'color' => 'danger',
        'description' => 'Integration request has been rejected',
        'icon' => 'heroicon-o-x-circle',
        'hex_color' => '#dc2626',
    ],
    'integration_pending' => [
        'label' => 'Integration Pending',
        'color' => 'warning',
        'description' => 'Integration awaiting processing',
        'icon' => 'heroicon-o-hourglass',
        'hex_color' => '#d97706',
    ],
    'integration_completed' => [
        'label' => 'Integration Completed',
        'color' => 'success',
        'description' => 'Integration process completed successfully',
        'icon' => 'heroicon-o-check-circle',
        'hex_color' => '#10b981',
    ],
    'integration_cancelled' => [
        'label' => 'Integration Cancelled',
        'color' => 'danger',
        'description' => 'Integration process has been cancelled',
        'icon' => 'heroicon-o-no-symbol',
        'hex_color' => '#ef4444',
    ],
    'suspended' => [
        'label' => 'Suspended',
        'color' => 'danger',
        'description' => 'User temporarily suspended from the system',
        'icon' => 'heroicon-o-pause-circle',
        'hex_color' => '#dc2626',
    ],
];
