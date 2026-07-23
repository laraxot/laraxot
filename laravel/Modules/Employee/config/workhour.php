<?php

declare(strict_types=1);

return [
    /*
     * |--------------------------------------------------------------------------
     * | Work Hour Configuration
     * |--------------------------------------------------------------------------
     * |
     * | Configuration settings for the Employee WorkHour module
     * |
     */

    'working_hours' => [
        'start' => '06:00',
        'end' => '22:00',
        'timezone' => 'Europe/Rome',
    ],
    'validation' => [
        'prevent_duplicate_entries' => true,
        'duplicate_threshold_minutes' => 1,
        'allow_past_entries' => false,
        'max_past_hours' => 24,
        'require_sequential_entries' => true,
    ],
    'break_settings' => [
        'max_break_duration_hours' => 2,
        'min_break_duration_minutes' => 15,
        'auto_end_break_hours' => 4,
    ],
    'notifications' => [
        'send_clock_reminders' => true,
        'reminder_times' => [
            'clock_in' => '09:00',
            'clock_out' => '18:00',
        ],
        'notify_managers' => [
            'late_clock_in' => true,
            'missing_clock_out' => true,
            'long_breaks' => true,
        ],
    ],
    'dashboard' => [
        'default_period' => 'week', // week, month
        'target_hours_per_week' => 40,
        'target_hours_per_day' => 8,
        'show_overtime' => true,
        'overtime_threshold_hours' => 8,
    ],
    'export' => [
        'formats' => ['csv', 'excel', 'pdf'],
        'include_notes' => true,
        'include_location' => false,
        'date_format' => 'd/m/Y',
        'time_format' => 'H:i:s',
    ],
    'security' => [
        'require_badge_id' => false,
        'validate_location' => false,
        'allowed_ip_ranges' => [],
        'log_all_actions' => true,
    ],
    'ui' => [
        'refresh_interval_seconds' => 5,
        'dashboard_refresh_seconds' => 30,
        'show_real_time_clock' => true,
        'button_colors' => [
            'clock_in' => 'success',
            'clock_out' => 'danger',
            'break_start' => 'warning',
            'break_end' => 'info',
        ],
    ],
    'permissions' => [
        'employee_can_edit_own' => true,
        'employee_edit_time_limit_hours' => 24,
        'manager_can_edit_all' => true,
        'admin_can_delete' => true,
    ],
];
