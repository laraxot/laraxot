# Employee Module - Getting Started Guide

## 🚀 Quick Start

This guide will help you quickly set up and start using the Employee Module.

### Prerequisites
- PHP 8.2+ 
- Laravel 11+
- Composer 2.0+
- MySQL 8.0+ or PostgreSQL 13+
- Node.js 18+ (for frontend assets)

### Installation Steps

#### 1. Install the Module
```bash
# Install via Composer
composer require modules/employee

# Publish configuration files
php artisan vendor:publish --tag=employee-config

# Publish language files
php artisan vendor:publish --tag=employee-lang
```

#### 2. Run Database Migrations
```bash
# Run all migrations
php artisan migrate

# Or run specific Employee module migrations
php artisan migrate --path=Modules/Employee/database/migrations
```

#### 3. Seed Demo Data (Optional)
```bash
# Seed with demo employees and departments
php artisan db:seed --class=\\Modules\\Employee\\Database\\Seeders\\EmployeeSeeder
```

#### 4. Clear Caches
```bash
# Clear all caches
php artisan optimize:clear

# Clear configuration cache
php artisan config:clear

# Clear route cache
php artisan route:clear

# Clear view cache
php artisan view:clear
```

#### 5. Start Development Server
```bash
# Start Laravel development server
php artisan serve

# The application will be available at http://localhost:8000
```

## 📋 First Steps After Installation

### 1. Access the Employee Module
- Navigate to `/admin` in your browser
- Login with admin credentials
- The Employee module will be available in the navigation sidebar

### 2. Configure Basic Settings
- Go to **Settings → Employee Module**
- Configure working hours, break durations, and other preferences
- Set up department structure and job positions

### 3. Create Your First Employee
1. Navigate to **Employees → Manage Employees**
2. Click "Create Employee"
3. Fill in employee details (personal info, department, position)
4. Save the employee record

### 4. Test Time Tracking
1. Go to the employee dashboard
2. Use the **TimeClockWidget** to clock in/out
3. Verify time entries are recorded correctly

## ⚙️ Basic Configuration

### Environment Variables
Add these to your `.env` file:

```env
# Employee Module Configuration
EMPLOYEE_DEFAULT_WORKING_HOURS=8
EMPLOYEE_BREAK_DURATION=60
EMPLOYEE_OVERTIME_THRESHOLD=8
EMPLOYEE_GPS_REQUIRED=true
EMPLOYEE_PHOTO_VERIFICATION=true

# Module-specific settings
EMPLOYEE_MODULE_ENABLED=true
EMPLOYEE_TIME_TRACKING_ENABLED=true
EMPLOYEE_LEAVE_MANAGEMENT_ENABLED=true
```

### Module Configuration File
`config/employee.php` contains all configurable options:

```php
return [
    'default_working_hours' => env('EMPLOYEE_DEFAULT_WORKING_HOURS', 8),
    'break_duration' => env('EMPLOYEE_BREAK_DURATION', 60), // minutes
    'overtime_threshold' => env('EMPLOYEE_OVERTIME_THRESHOLD', 8), // hours
    'gps_required' => env('EMPLOYEE_GPS_REQUIRED', true),
    'photo_verification' => env('EMPLOYEE_PHOTO_VERIFICATION', true),
    'module_enabled' => env('EMPLOYEE_MODULE_ENABLED', true),
    'time_tracking_enabled' => env('EMPLOYEE_TIME_TRACKING_ENABLED', true),
    'leave_management_enabled' => env('EMPLOYEE_LEAVE_MANAGEMENT_ENABLED', true),
];
```

## 👥 User Roles and Permissions

### Default Roles
- **Administrator**: Full access to all features
- **HR Manager**: Employee management and reporting
- **Department Manager**: Manage own department employees
- **Employee**: Self-service and time tracking

### Permission Setup
Permissions are automatically configured during installation. You can customize them in:
- `Modules/Employee/config/permissions.php`
- Filament's role management interface

## 🎯 Core Features to Explore

### 1. Employee Management
- **Employee Profiles**: Complete employee information management
- **Department Structure**: Hierarchical organizational management
- **Position Management**: Job roles and responsibilities
- **Document Storage**: Contract and document management

### 2. Time Tracking System
- **Clock In/Out**: Web-based time tracking with verification
- **Break Management**: Automatic break time calculation
- **Overtime Tracking**: Overtime hours and approval workflows
- **Attendance Reports**: Detailed attendance analytics

### 3. Leave Management
- **Leave Requests**: Online vacation and leave requests
- **Approval Workflows**: Multi-level approval processes
- **Balance Tracking**: Real-time leave balance calculations
- **Calendar Integration**: Company-wide leave calendar

### 4. Dashboard Widgets
- **TimeClockWidget**: Real-time clock in/out interface
- **EmployeeOverviewWidget**: Employee statistics and overview
- **WorkHourStatsWidget**: Time tracking analytics
- **RecentActivityWidget**: Latest system activities

## 🔧 Development Setup

### Local Development Environment
```bash
# Clone the repository
git clone [repository-url]

# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install

# Build frontend assets
npm run build

# Set up environment
cp .env.example .env
php artisan key:generate

# Run migrations and seeders
php artisan migrate --seed

# Start development servers
php artisan serve
npm run dev
```

### Testing the Module
```bash
# Run all tests
php artisan test

# Run specific Employee module tests
php artisan test --testsuite=Employee

# Run with test coverage
php artisan test --coverage --testsuite=Employee

# Run specific test file
php artisan test tests/Feature/Employee/EmployeeTest.php
```

## 🚨 Troubleshooting Common Issues

### Module Not Appearing
```bash
# Clear all caches
php artisan optimize:clear

# Re-register the module
php artisan module:enable Employee

# Check module status
php artisan module:list
```

### Database Migration Issues
```bash
# Reset and re-run migrations
php artisan migrate:fresh
php artisan migrate

# Run specific module migrations
php artisan migrate --path=Modules/Employee/database/migrations
```

### Language File Issues
```bash
# Check language file syntax
php -l Modules/Employee/lang/it/employee.php

# Clear language cache
php artisan lang:clear

# Check for missing translations
php artisan lang:missing
```

### Filament Integration Issues
```bash
# Clear Filament cache
php artisan filament:clear-cache

# Re-publish Filament assets
php artisan filament:assets
```

## 📚 Next Steps

### Explore Documentation
- **[Architecture Overview](ARCHITECTURE.md)**: System design and components
- **[Feature Documentation](02-features/README.md)**: Detailed feature guides
- **[Implementation Guides](03-implementation/README.md)**: Technical setup
- **[Widget Documentation](04-widgets/README.md)**: Dashboard widgets

### Advanced Configuration
- Customize employee fields and forms
- Configure approval workflows
- Set up email notifications
- Integrate with external systems

### Development Resources
- **[Laravel Documentation](https://laravel.com/docs)**: Framework reference
- **[Filament Documentation](https://filamentphp.com/docs)**: Admin panel guide
- **[XotBase Framework](../Xot/docs/)**: Extension framework documentation

---

**Need Help?**
- Check the [Troubleshooting FAQ](07-reference/troubleshooting-faq.md)
- Review [Common Issues](06-maintenance/debugging-troubleshooting.md)
- Join community discussions for support

**Ready for Production?**
- Review [Production Deployment](03-implementation/setup-installation.md#production-deployment)
- Configure [Security Settings](06-maintenance/security-updates.md)
- Set up [Monitoring](06-maintenance/performance-optimization.md#monitoring)

---

**Last Updated**: September 2025  
**Module Version**: 2.0  
**Compatibility**: Laravel 11+, Filament 3+, PHP 8.2+