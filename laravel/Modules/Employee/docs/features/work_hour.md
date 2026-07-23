# WorkHour Module - Complete Implementation Guide

## Overview

Complete WorkHour module implementation for employee time tracking functionality similar to dipendentincloud.it. The module provides comprehensive time tracking with smart toggle buttons, validation, reporting capabilities, and follows strict Laraxot conventions.

## Critical Laraxot Philosophy Compliance

### XotBase Extension Rules (ABSOLUTE PRIORITY)

**NEVER EXTEND FILAMENT CLASSES DIRECTLY - ALWAYS USE XOTBASE**

```php
// ❌ FORBIDDEN
class WorkHourResource extends Filament\Resources\Resource
class TimeclockPage extends Filament\Pages\Page

// ✅ MANDATORY
class WorkHourResource extends Modules\Xot\Filament\Resources\XotBaseResource
class TimeclockPage extends Modules\Xot\Filament\Pages\XotBasePage
```

### Naming Standards (Employee Module)

**ALL DATABASE ELEMENTS MUST BE IN ENGLISH**

- Table names: English only
- Column names: English only
- Enum values: English only
- Comments: English only

## Database Schema

### Table Structure (Implemented)

**Table**: `time_entries` (following technical_architecture.md)

**Current Implementation Fields**:

- `id`: Primary key auto-increment
- `employee_id`: Foreign key to employees table (NOT user_id)
- `type`: ENUM('clock_in', 'clock_out', 'break_start', 'break_end')
- `timestamp`: DATETIME - Exact time of entry
- `location_lat`: DECIMAL(10,8) nullable - GPS latitude
- `location_lng`: DECIMAL(11,8) nullable - GPS longitude
- `location_name`: VARCHAR nullable - Location description
- `device_info`: JSON nullable - Device information
- `photo_path`: VARCHAR nullable - Photo verification
- `notes`: TEXT nullable - Optional notes
- `status`: ENUM('pending', 'approved', 'rejected') default 'pending'
- `approved_by`: Foreign key to users table (nullable)
- `approved_at`: DATETIME nullable
- `created_at`, `updated_at`: Standard timestamps

**Relationships**:

- `belongsTo(Employee::class, 'employee_id')` - Employee relation
- `belongsTo(User::class, 'approved_by')` - Approver relation

## XotBaseMigration Pattern

### Correct Migration Implementation

```php
<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class extends XotBaseMigration
{
    protected string $table_name = 'time_entries';

    public function up(): void
    {
        if ($this->hasTable($this->table_name)) {
            return;
        }

        Schema::create($this->table_name, function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')
                  ->constrained('employees')
                  ->onDelete('cascade')
                  ->comment('Reference to employee');
            $table->enum('type', ['clock_in', 'clock_out', 'break_start', 'break_end'])
                  ->comment('Type of time entry');
            $table->dateTime('timestamp')
                  ->comment('Exact time of entry');
            $table->decimal('location_lat', 10, 8)->nullable()
                  ->comment('GPS latitude coordinate');
            $table->decimal('location_lng', 11, 8)->nullable()
                  ->comment('GPS longitude coordinate');
            $table->string('location_name')->nullable()
                  ->comment('Human readable location name');
            $table->json('device_info')->nullable()
                  ->comment('Device information for tracking');
            $table->string('photo_path')->nullable()
                  ->comment('Path to verification photo');
            $table->text('notes')->nullable()
                  ->comment('Optional notes for entry');
            $table->enum('status', ['pending', 'approved', 'rejected'])
                  ->default('pending')
                  ->comment('Entry approval status');
            $table->foreignId('approved_by')->nullable()
                  ->constrained('users')
                  ->onDelete('set null')
                  ->comment('User who approved entry');
            $table->dateTime('approved_at')->nullable()
                  ->comment('When entry was approved');
            $table->timestamps();
            
            // Performance indexes
            $table->index(['employee_id', 'timestamp'], 'time_entries_employee_timestamp_idx');
            $table->index(['timestamp', 'type'], 'time_entries_timestamp_type_idx');
            $table->index(['status'], 'time_entries_status_idx');
            
            // Prevent duplicate entries within same minute
            $table->unique(['employee_id', 'timestamp', 'type'], 'time_entries_unique_entry');
        });
        
        $this->tableComment($this->table_name, 'Employee time tracking entries for attendance system');
    }
};
```

## Implementation Status

### Files Successfully Implemented

**Models and Database**:

- ✅ `app/Models/WorkHour.php` - Main model with business logic
- ✅ `database/migrations/2025_08_27_121400_create_work_hours_table.php` - Database migration
- ✅ `database/factories/WorkHourFactory.php` - Factory for testing data
- ✅ `database/seeders/WorkHourSeeder.php` - Seeder for sample data

**Filament Resources**:

- ✅ `app/Filament/Resources/WorkHourResource.php` - Main resource
- ✅ `app/Filament/Resources/WorkHourResource/Pages/ListWorkHours.php` - List page
- ✅ `app/Filament/Resources/WorkHourResource/Pages/CreateWorkHour.php` - Create page
- ✅ `app/Filament/Resources/WorkHourResource/Pages/EditWorkHour.php` - Edit page
- ✅ `app/Filament/Resources/WorkHourResource/Pages/TimeclockPage.php` - Timeclock page

**Livewire Components**:

- ✅ `app/Http/Livewire/TimeClock.php` - Interactive timeclock component
- ✅ `app/Http/Livewire/WorkHourDashboard.php` - Dashboard component

**Views**:

- ✅ `resources/views/livewire/time-clock.blade.php` - Timeclock interface
- ✅ `resources/views/livewire/work-hour-dashboard.blade.php` - Dashboard interface
- ✅ `resources/views/filament/pages/timeclock.blade.php` - Filament page wrapper

**Configuration and Translations**:

- ✅ `config/workhour.php` - Module configuration
- ✅ `lang/en/workhour.php` - English translations
- ✅ `lang/it/workhour.php` - Italian translations

**Security**:

- ✅ `app/Policies/WorkHourPolicy.php` - Authorization policies

## Core Features Implemented

### Smart Toggle Button Logic

The system automatically determines the next valid action based on the last time entry:

- No entries today → "Clock In"
- Last entry is clock_in → "Clock Out" or "Start Break"
- Last entry is break_start → "End Break"
- Last entry is break_end → "Clock Out"
- Last entry is clock_out → "Clock In"

### Validation Rules

- Can't clock_out without clock_in
- Can't start break without being clocked in
- Can't end break without starting break
- Prevent duplicate entries within same minute
- Validate working hours (6 AM to 10 PM by default)

### Business Logic Features

- Automatic calculation of worked hours
- Break time tracking and calculation
- Real-time status updates
- GPS location tracking (optional)
- Photo verification support
- Approval workflow system

### User Interface Features

- Large, touch-friendly buttons (minimum 60px height)
- Real-time clock display
- Current status indicator with color coding
- Today's entries timeline
- Responsive design for mobile/tablet
- Color-coded status (green=in, red=out, orange=break)

## Architecture Compliance

### Model Structure

```php
class WorkHour extends BaseModel
{
    // Extends Employee module BaseModel (NOT XotBaseModel directly)
    // Uses employee_id foreign key (NOT user_id)
    // All field names in English
    // Proper relationships and business logic
}
```

### Filament Resources Structure

```php
class WorkHourResource extends XotBaseResource
{
    // NEVER extends Filament\Resources\Resource directly
    // Always extends XotBaseResource
    // Proper form schema and table configuration
}
```

### Security and Authorization

- Role-based access control via WorkHourPolicy
- Employees can only manage their own records
- Managers can view all employees' records
- Proper authorization checks on all actions

## Configuration Options

The module includes comprehensive configuration in `config/workhour.php`:

- Working hours settings
- Validation rules
- Break time policies
- Notification preferences
- Dashboard settings
- Export options
- Security settings
- UI customization

## Integration Points

### Employee Model Integration

The WorkHour module integrates seamlessly with the existing Employee model:

```php
// Employee model relationship
public function workHours(): HasMany
{
    return $this->hasMany(WorkHour::class);
}
```

### User Authentication

- Uses Laravel's built-in authentication
- Integrates with existing user roles and permissions
- Supports multi-tenant scenarios

## Next Steps for Refactoring

Based on the current implementation and Laraxot philosophy requirements:

1. **Update Migration**: Change table name from `work_hours` to `time_entries`
2. **Update Model**: Align field names with new schema
3. **Update Resources**: Ensure all Filament components extend XotBase classes
4. **Update Relationships**: Use `employee_id` consistently
5. **Update Translations**: Ensure all text uses translation files
6. **Update Documentation**: Complete this consolidation process

## Development Guidelines

### Code Quality Standards

- Follow DRY (Don't Repeat Yourself) principles
- Implement KISS (Keep It Simple, Stupid) approach
- Adhere to SOLID principles
- Use proper type hints and return types
- Include comprehensive PHPDoc comments

### Testing Requirements

- Unit tests for all business logic
- Feature tests for all user interactions
- Policy tests for authorization
- Factory and seeder tests

### Performance Considerations

- Proper database indexing
- Query optimization
- Caching where appropriate
- Lazy loading for relationships

This documentation serves as the complete reference for the WorkHour module implementation, ensuring consistency with Laraxot conventions and providing clear guidance for future development and maintenance.