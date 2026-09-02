# TimeEntry Model - Analysis and Refactoring

## Current Issues Analysis

The `TimeEntry.php` model has **significant redundancy issues** with **67+ redundant static methods** that simply proxy to parent methods. This violates DRY principles and adds unnecessary complexity.

### Redundant Methods Identified

The following static methods are completely redundant and should be removed:

1. **Basic query methods** (lines 221-240):
   - `create()` - Already inherited from Eloquent
   - `find()` - Already inherited from Eloquent
   - `query()` - Already inherited from Eloquent

2. **Query builder methods** (lines 245-536):
   - `on()`, `where()`, `whereDate()`, `whereNotNull()`, `orderBy()`, `latest()`
   - `first()`, `firstOrFail()`, `all()`, `get()`, `count()`, `limit()`
   - `join()`, `leftJoin()`, `rightJoin()`, `groupBy()`, `having()`, `distinct()`
   - `whereRaw()`, `orWhereRaw()`, `whereIn()`, `whereNotIn()`
   - `whereBetween()`, `orWhereBetween()`, `whereNotBetween()`, `orWhereNotBetween()`
   - `whereNull()`, `orWhereNull()`, `whereDate()` (duplicate), `orWhereDate()`
   - `whereTime()`, `orWhereTime()`, `whereDay()`, `whereMonth()`, `whereYear()`
   - `exists()`, `doesntExist()`

### Root Cause Analysis

These methods appear to be auto-generated or copied from a base class without understanding that they're already available through Eloquent inheritance.

## Refactoring Plan

### Step 1: Remove All Redundant Static Methods
All static methods from line 219 to 537 should be removed entirely.

### Step 2: Keep Essential Business Logic
The following methods should be preserved as they contain actual business logic:

1. **Scopes** (lines 144-169):
   - `scopePending()` - Filter pending entries
   - `scopeForEmployee()` - Filter by employee
   - `scopeWithAnomalies()` - Filter entries with anomalies

2. **Business Logic Methods** (lines 174-216):
   - `calculateTotalHours()` - Calculate worked hours
   - `hasAnomalies()` - Check for anomalies
   - `isApproved()`, `isPending()`, `isRejected()` - Status checks

3. **Relationships** (lines 125-136):
   - `employee()` - Employee relationship
   - `approvedBy()` - Approval relationship

### Step 3: Add Missing Business Logic
Based on the database schema and requirements, the following methods should be added:

1. **Validation Rules** - For creating/updating time entries
2. **Status Transition Methods** - For approving/rejecting entries
3. **Location Methods** - For handling GPS coordinates
4. **Break Calculation Methods** - For calculating break durations

## Improved TimeEntry Model Structure

```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class TimeEntry.
 *
 * @property int $id
 * @property int $employee_id
 * @property Carbon $clock_in
 * @property Carbon|null $clock_out
 * @property Carbon|null $break_start
 * @property Carbon|null $break_end
 * @property int $break_duration
 * @property float|null $total_hours
 * @property float|null $regular_hours
 * @property float|null $overtime_hours
 * @property array<string, mixed>|null $location_in
 * @property array<string, mixed>|null $location_out
 * @property array<string, mixed>|null $device_info
 * @property string|null $notes
 * @property string|null $employee_notes
 * @property string|null $supervisor_notes
 * @property string $status
 * @property int|null $approved_by
 * @property Carbon|null $approved_at
 * @property string|null $rejection_reason
 * @property array<string, mixed>|null $anomalies
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Employee $employee
 * @property-read Employee|null $approvedBy
 */
class TimeEntry extends BaseModel
{
    /** @var list<string> */
    protected $fillable = [
        'employee_id',
        'clock_in',
        'clock_out',
        'break_start',
        'break_end',
        'break_duration',
        'total_hours',
        'regular_hours',
        'overtime_hours',
        'location_in',
        'location_out',
        'device_info',
        'notes',
        'employee_notes',
        'supervisor_notes',
        'status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'anomalies',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'clock_in' => 'datetime',
            'clock_out' => 'datetime',
            'break_start' => 'datetime',
            'break_end' => 'datetime',
            'break_duration' => 'integer',
            'total_hours' => 'decimal:2',
            'regular_hours' => 'decimal:2',
            'overtime_hours' => 'decimal:2',
            'location_in' => 'array',
            'location_out' => 'array',
            'device_info' => 'array',
            'approved_at' => 'datetime',
            'anomalies' => 'array',
        ];
    }

    /**
     * Get the employee that owns this time entry.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the employee who approved this time entry.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    /**
     * Scope to get pending entries.
     *
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get entries for a specific employee.
     *
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeForEmployee($query, int $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    /**
     * Scope to get entries with anomalies.
     *
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeWithAnomalies($query)
    {
        return $query->whereNotNull('anomalies');
    }

    /**
     * Calculate total hours worked.
     */
    public function calculateTotalHours(): float
    {
        if (! $this->clock_out) {
            return 0.0;
        }

        $totalMinutes = $this->clock_in->diffInMinutes($this->clock_out);
        $totalMinutes -= $this->break_duration;

        return round($totalMinutes / 60, 2);
    }

    /**
     * Check if entry has anomalies.
     */
    public function hasAnomalies(): bool
    {
        return ! empty($this->anomalies);
    }

    /**
     * Check if entry is approved.
     */
    public function isApproved(): bool
    {
        return in_array($this->status, ['approved', 'auto_approved'], strict: true);
    }

    /**
     * Check if entry is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if entry is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
```

## Benefits of Refactoring

1. **Reduced Complexity**: From 537 lines to ~120 lines
2. **Improved Maintainability**: No redundant proxy methods
3. **Better Performance**: Less code to load and parse
4. **Clearer Intent**: Only business logic remains
5. **PHPStan Compliance**: Fewer false positives from redundant methods

## Next Steps

1. Implement the refactored model
2. Run PHPStan to verify no regressions
3. Run PHPMD to check code quality
4. Run PHPInsights for comprehensive analysis
5. Update tests to ensure functionality is preserved