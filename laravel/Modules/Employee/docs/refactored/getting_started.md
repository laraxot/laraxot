# Getting Started - Employee Module

## 🚀 Quick Installation Guide

### Prerequisites
- PHP 8.2+ with required extensions
- Composer 2.5+
- MySQL 8.0+ or PostgreSQL 14+
- Laravel 11+ application
- Filament 3+ installed

### 1. Install the Module
```bash
# Install via Composer
composer require modules/employee

# Publish configuration
php artisan vendor:publish --tag=employee-config

# Publish language files
php artisan vendor:publish --tag=employee-lang

# Run migrations
php artisan migrate

# Seed demo data (optional)
php artisan db:seed --class=EmployeeSeeder
```

### 2. Configure the Module

#### Environment Configuration
Add to your `.env` file:
```env
EMPLOYEE_DEFAULT_WORKING_HOURS=8
EMPLOYEE_BREAK_DURATION=60
EMPLOYEE_OVERTIME_THRESHOLD=8
EMPLOYEE_GPS_REQUIRED=true
EMPLOYEE_PHOTO_VERIFICATION=true
```

#### Module Configuration
Edit `config/employee.php`:
```php
return [
    'default_working_hours' => env('EMPLOYEE_DEFAULT_WORKING_HOURS', 8),
    'break_duration' => env('EMPLOYEE_BREAK_DURATION', 60), // minutes
    'overtime_threshold' => env('EMPLOYEE_OVERTIME_THRESHOLD', 8), // hours
    'gps_required' => env('EMPLOYEE_GPS_REQUIRED', true),
    'photo_verification' => env('EMPLOYEE_PHOTO_VERIFICATION', true),
    'auto_approval' => false,
    'notifications' => [
        'clock_in' => true,
        'clock_out' => true,
        'break_start' => true,
        'break_end' => true,
    ],
];
```

### 3. Register Filament Resources

Add to your `config/filament.php`:
```php
'resources' => [
    // ... other resources
    Modules\Employee\Filament\Resources\EmployeeResource::class,
    Modules\Employee\Filament\Resources\DepartmentResource::class,
    Modules\Employee\Filament\Resources\WorkHourResource::class,
],

'widgets' => [
    // ... other widgets
    Modules\Employee\Filament\Widgets\TimeClockWidget::class,
    Modules\Employee\Filament\Widgets\TodoWidget::class,
    Modules\Employee\Filament\Widgets\UpcomingScheduleWidget::class,
    Modules\Employee\Filament\Widgets\PendingRequestsWidget::class,
    Modules\Employee\Filament\Widgets\TimeOffBalanceWidget::class,
    Modules\Employee\Filament\Widgets\TodayPresenceWidget::class,
],
```

### 4. Set Up Navigation

Add to your Filament navigation configuration:
```php
'employee' => [
    'label' => 'Employees',
    'icon' => 'employee-icon', // Auto-registered from resources/svg/
    'group' => 'HR Management',
    'items' => [
        [
            'label' => 'Employee List',
            'url' => EmployeeResource::getUrl(),
            'icon' => 'heroicon-o-users',
        ],
        [
            'label' => 'Departments',
            'url' => DepartmentResource::getUrl(),
            'icon' => 'heroicon-o-building-office',
        ],
        [
            'label' => 'Time Tracking',
            'url' => WorkHourResource::getUrl(),
            'icon' => 'heroicon-o-clock',
        ],
    ],
],
```

### 5. Verify Installation

Run verification commands:
```bash
# Check module registration
php artisan module:list | grep Employee

# Verify database schema
php artisan migrate:status | grep employee

# Test language files
php -l Modules/Employee/lang/it/employee.php

# Clear caches
php artisan optimize:clear
```

## 🎯 First Steps After Installation

### 1. Create Your First Department
1. Navigate to **HR Management → Departments**
2. Click "Create Department"
3. Fill in department details:
   - Name: e.g., "Human Resources"
   - Code: e.g., "HR"
   - Description: Department purpose and responsibilities
4. Save the department

### 2. Add Your First Employee
1. Navigate to **HR Management → Employee List**
2. Click "Create Employee"
3. Fill in employee information:
   - Personal details (name, email, phone)
   - Work information (department, position)
   - Contract details (start date, employment type)
4. Upload employee photo (optional)
5. Save the employee

### 3. Test Time Tracking
1. Navigate to the dashboard
2. Use the **TimeClockWidget** to:
   - Clock in for the current employee
   - Take breaks (start/end)
   - Clock out at end of day
3. Verify time entries appear in **HR Management → Time Tracking**

### 4. Configure Permissions

Set up role-based access control:
```php
// In your Filament authorization setup
Gate::define('view_employee', function ($user) {
    return $user->hasRole(['admin', 'hr_manager']);
});

Gate::define('manage_employee', function ($user) {
    return $user->hasRole(['admin', 'hr_manager']);
});
```

## 🔧 Common Setup Issues

### Issue: Module Not Found
```bash
# Solution: Run composer dump-autoload
composer dump-autoload
php artisan optimize:clear
```

### Issue: Missing SVG Icons
```bash
# Solution: Verify SVG files exist
ls Modules/Employee/resources/svg/
# Should show: icon.svg, icon1.svg, icon2.svg, icon3.svg
```

### Issue: Language File Errors
```bash
# Solution: Validate syntax
php -l Modules/Employee/lang/it/*.php
php -l Modules/Employee/lang/en/*.php
```

### Issue: Database Migration Errors
```bash
# Solution: Check migration status
php artisan migrate:status
# If needed, rollback and re-run
php artisan migrate:rollback --step=1
php artisan migrate
```

## 📊 Initial Configuration Checklist

- [ ] Module installed via Composer
- [ ] Configuration files published
- [ ] Database migrations executed
- [ ] Language files validated
- [ ] Filament resources registered
- [ ] Navigation configured
- [ ] SVG icons available
- [ ] Test data created (optional)
- [ ] Permissions configured
- [ ] Caches cleared

## 🚀 Next Steps

After successful installation:

1. **Explore Features**: Review the [Features Documentation](../02-features/README.md)
2. **Configure Settings**: Adjust module configuration in `config/employee.php`
3. **Customize UI**: Modify views and translations as needed
4. **Integrate APIs**: Set up external integrations if required
5. **Set Up Reporting**: Configure dashboards and reports

## 📞 Support

If you encounter issues:

1. **Check Logs**: `tail -f storage/logs/laravel.log`
2. **Verify Requirements**: Ensure all prerequisites are met
3. **Review Documentation**: See [Troubleshooting FAQ](../07-reference/troubleshooting-faq.md)
4. **Community Support**: Check GitHub issues and discussions

---

**Next**: Read the [Architecture Overview](../ARCHITECTURE.md) to understand the system design, or explore [Feature Documentation](../02-features/README.md) for detailed functionality guides.