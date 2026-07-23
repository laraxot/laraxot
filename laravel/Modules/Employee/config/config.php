<?php

declare(strict_types=1);

use Illuminate\Support\Env;

return [
    /*
     * |--------------------------------------------------------------------------
     * | Module Information
     * |--------------------------------------------------------------------------
     * |
     * | Basic module configuration and metadata
     * |
     */
    'name' => 'Employee',
    'description' => 'Modulo per la gestione completa delle risorse umane e dipendenti',
    'version' => '1.0.0',
    'icon' => 'employee-icon2',
    /*
     * |--------------------------------------------------------------------------
     * | Navigation Configuration
     * |--------------------------------------------------------------------------
     * |
     * | Configuration for the module's navigation in the admin panel.
     * |
     */
    'navigation' => [
        'enabled' => true,
        'sort' => 50,
        'group' => 'Gestione Risorse Umane',
    ],
    /*
     * |--------------------------------------------------------------------------
     * | Routes Configuration
     * |--------------------------------------------------------------------------
     * |
     * | Configuration for the module's routes and middleware.
     * |
     */
    'routes' => [
        'enabled' => true,
        'middleware' => ['web', 'auth'],
        'prefix' => 'employee',
    ],
    /*
     * |--------------------------------------------------------------------------
     * | Service Providers
     * |--------------------------------------------------------------------------
     * |
     * | Module service providers that should be registered.
     * |
     */
    'providers' => [
        'Modules\\Employee\\Providers\\EmployeeServiceProvider',
    ],
    /*
     * |--------------------------------------------------------------------------
     * | Permissions
     * |--------------------------------------------------------------------------
     * |
     * | Module-specific permissions for role-based access control.
     * |
     */
    'permissions' => [
        'view' => 'employee.view',
        'create' => 'employee.create',
        'edit' => 'employee.edit',
        'delete' => 'employee.delete',
        'manage' => 'employee.manage',
        'approve_hours' => 'employee.approve_hours',
        'view_reports' => 'employee.view_reports',
        'export_data' => 'employee.export_data',
    ],
    /*
     * |--------------------------------------------------------------------------
     * | Work Hours Configuration
     * |--------------------------------------------------------------------------
     * |
     * | Default settings for employee work hours and time tracking.
     * |
     */
    'work_hours' => [
        'default_start' => Env::get('EMPLOYEE_DEFAULT_START_TIME', '09:00'),
        'default_end' => Env::get('EMPLOYEE_DEFAULT_END_TIME', '17:00'),
        'break_duration' => Env::get('EMPLOYEE_BREAK_DURATION', 60), // minutes
        'max_daily_hours' => Env::get('EMPLOYEE_MAX_DAILY_HOURS', 8),
        'max_weekly_hours' => Env::get('EMPLOYEE_MAX_WEEKLY_HOURS', 40),
        'overtime_threshold' => Env::get('EMPLOYEE_OVERTIME_THRESHOLD', 8), // hours per day
    ],
    /*
     * |--------------------------------------------------------------------------
     * | Timeclock Configuration
     * | Timeclock Configuration
     * | Timeclock Configuration
     * |--------------------------------------------------------------------------
     * |
     * | Settings for the timeclock functionality and validation.
     * |
     */
    'timeclock' => [
        'gps_required' => Env::get('EMPLOYEE_GPS_REQUIRED', true),
        'gps_accuracy_threshold' => Env::get('EMPLOYEE_GPS_ACCURACY', 100), // meters
        'photo_verification' => Env::get('EMPLOYEE_PHOTO_VERIFICATION', false),
        'auto_clock_out' => Env::get('EMPLOYEE_AUTO_CLOCK_OUT', false),
        'clock_out_after_hours' => Env::get('EMPLOYEE_CLOCK_OUT_AFTER_HOURS', 10), // hours
        'allowed_clock_in_early' => Env::get('EMPLOYEE_EARLY_CLOCK_IN', 30), // minutes before start
        'allowed_clock_out_late' => Env::get('EMPLOYEE_LATE_CLOCK_OUT', 30), // minutes after end
    ],
    /*
     * |--------------------------------------------------------------------------
     * | Approval Workflow
     * |--------------------------------------------------------------------------
     * |
     * | Configuration for time entry approval processes.
     * |
     */
    'approval' => [
        'auto_approve' => Env::get('EMPLOYEE_AUTO_APPROVE', false),
        'approval_required_after_hours' => Env::get('EMPLOYEE_APPROVAL_REQUIRED_AFTER_HOURS', 8),
        'approval_timeout_days' => Env::get('EMPLOYEE_APPROVAL_TIMEOUT', 7),
        'notify_supervisor' => Env::get('EMPLOYEE_NOTIFY_SUPERVISOR', true),
    ],
    /*
     * |--------------------------------------------------------------------------
     * | Validation Rules
     * |--------------------------------------------------------------------------
     * |
     * | Rules for validating time entries and preventing data issues.
     * |
     */
    'validation' => [
        'duplicate_prevention' => Env::get('EMPLOYEE_DUPLICATE_PREVENTION', true),
        'sequence_validation' => Env::get('EMPLOYEE_SEQUENCE_VALIDATION', true),
        'future_date_prevention' => Env::get('EMPLOYEE_FUTURE_DATE_PREVENTION', true),
        'max_edit_days' => Env::get('EMPLOYEE_MAX_EDIT_DAYS', 7), // days
        'minimum_break_time' => Env::get('EMPLOYEE_MIN_BREAK_TIME', 15), // minutes
    ],
    /*
     * |--------------------------------------------------------------------------
     * | Notifications Configuration
     * |--------------------------------------------------------------------------
     * |
     * | Settings for employee-related notifications.
     * |
     */
    'notifications' => [
        'enabled' => Env::get('EMPLOYEE_NOTIFICATIONS_ENABLED', true),
        'channels' => Env::get('EMPLOYEE_NOTIFICATION_CHANNELS', 'mail,database'),
        'remind_clock_out' => Env::get('EMPLOYEE_REMIND_CLOCK_OUT', true),
        'overtime_alerts' => Env::get('EMPLOYEE_OVERTIME_ALERTS', true),
        'missing_entries_alert' => Env::get('EMPLOYEE_MISSING_ENTRIES_ALERT', true),
    ],
    /*
     * |--------------------------------------------------------------------------
     * | Reports Configuration
     * |--------------------------------------------------------------------------
     * |
     * | Settings for reporting and data export functionality.
     * |
     */
    'reports' => [
        'enabled' => Env::get('EMPLOYEE_REPORTS_ENABLED', true),
        'default_period' => Env::get('EMPLOYEE_DEFAULT_REPORT_PERIOD', 'monthly'),
        'export_formats' => ['xlsx', 'pdf', 'csv'],
        'max_export_records' => Env::get('EMPLOYEE_MAX_EXPORT_RECORDS', 10000),
        'cache_reports' => Env::get('EMPLOYEE_CACHE_REPORTS', true),
        'cache_ttl' => Env::get('EMPLOYEE_REPORT_CACHE_TTL', 3600), // seconds
    ],
    /*
     * |--------------------------------------------------------------------------
     * | Cache Configuration
     * |--------------------------------------------------------------------------
     * |
     * | Cache settings for improving module performance.
     * |
     */
    'cache' => [
        'enabled' => Env::get('EMPLOYEE_CACHE_ENABLED', true),
        'ttl' => Env::get('EMPLOYEE_CACHE_TTL', 3600), // seconds
        'prefix' => 'employee_',
        'store' => Env::get('EMPLOYEE_CACHE_STORE', 'default'),
    ],
    /*
     * |--------------------------------------------------------------------------
     * | Feature Flags
     * |--------------------------------------------------------------------------
     * |
     * | Toggle features on/off for different deployment environments.
     * |
     */
    'features' => [
        'time_tracking' => Env::get('EMPLOYEE_TIME_TRACKING_ENABLED', true),
        'gps_tracking' => Env::get('EMPLOYEE_GPS_TRACKING_ENABLED', true),
        'photo_verification' => Env::get('EMPLOYEE_PHOTO_VERIFICATION_ENABLED', false),
        'break_tracking' => Env::get('EMPLOYEE_BREAK_TRACKING_ENABLED', true),
        'overtime_calculation' => Env::get('EMPLOYEE_OVERTIME_CALCULATION_ENABLED', true),
        'mobile_app' => Env::get('EMPLOYEE_MOBILE_APP_ENABLED', false),
        'biometric_auth' => Env::get('EMPLOYEE_BIOMETRIC_AUTH_ENABLED', false),
    ],
    /*
     * |--------------------------------------------------------------------------
     * | API Configuration
     * | API Configuration
     * | API Configuration
     * |--------------------------------------------------------------------------
     * |
     * | Settings for API access and rate limiting.
     * |
     */
    'api' => [
        'enabled' => Env::get('EMPLOYEE_API_ENABLED', true),
        'rate_limit' => Env::get('EMPLOYEE_API_RATE_LIMIT', 60), // requests per minute
        'throttle_key' => Env::get('EMPLOYEE_API_THROTTLE_KEY', 'api'),
        'versioning' => Env::get('EMPLOYEE_API_VERSIONING', true),
        'current_version' => 'v1',
    ],
    /*
     * |--------------------------------------------------------------------------
     * | Security Configuration
     * |--------------------------------------------------------------------------
     * |
     * | Security-related settings for the employee module.
     * |
     */
    'security' => [
        'encrypt_sensitive_data' => Env::get('EMPLOYEE_ENCRYPT_SENSITIVE_DATA', true),
        'audit_trail' => Env::get('EMPLOYEE_AUDIT_TRAIL', true),
        'session_timeout' => Env::get('EMPLOYEE_SESSION_TIMEOUT', 3600), // seconds
        'max_login_attempts' => Env::get('EMPLOYEE_MAX_LOGIN_ATTEMPTS', 3),
        'lockout_duration' => Env::get('EMPLOYEE_LOCKOUT_DURATION', 900), // seconds
    ],
    /*
     * |--------------------------------------------------------------------------
     * | Integration Settings
     * |--------------------------------------------------------------------------
     * |
     * | Settings for third-party integrations and services.
     * |
     */
    'integrations' => [
        'payroll' => [
            'enabled' => Env::get('EMPLOYEE_PAYROLL_INTEGRATION', false),
            'provider' => Env::get('EMPLOYEE_PAYROLL_PROVIDER', null),
            'api_key' => Env::get('EMPLOYEE_PAYROLL_API_KEY', null),
        ],
        'hr_system' => [
            'enabled' => Env::get('EMPLOYEE_HR_INTEGRATION', false),
            'provider' => Env::get('EMPLOYEE_HR_PROVIDER', null),
            'sync_interval' => Env::get('EMPLOYEE_HR_SYNC_INTERVAL', 3600), // seconds
        ],
    ],
];
