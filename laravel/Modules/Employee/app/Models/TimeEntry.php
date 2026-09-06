<?php

declare(strict_types=1);

namespace Modules\Employee\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Employee\Database\Factories\TimeEntryFactory;
use Modules\Xot\Contracts\ProfileContract;

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
 * @property string $type Type of time entry
 * @property string $timestamp Exact time of entry
 * @property numeric|null $location_lat GPS latitude coordinate
 * @property numeric|null $location_lng GPS longitude coordinate
 * @property string|null $location_name Human readable location name
 * @property string|null $photo_path Path to verification photo
 * @property-read ProfileContract|null $creator
 * @property-read ProfileContract|null $deleter
 * @property-read ProfileContract|null $updater
 *
 * @method static TimeEntryFactory factory($count = null, $state = [])
 * @method static Builder<static>|TimeEntry forEmployee(int $employeeId)
 * @method static Builder<static>|TimeEntry newModelQuery()
 * @method static Builder<static>|TimeEntry newQuery()
 * @method static Builder<static>|TimeEntry pending()
 * @method static Builder<static>|TimeEntry query()
 * @method static Builder<static>|TimeEntry whereApprovedAt($value)
 * @method static Builder<static>|TimeEntry whereApprovedBy($value)
 * @method static Builder<static>|TimeEntry whereClockIn($value)
 * @method static Builder<static>|TimeEntry whereClockOut($value)
 * @method static Builder<static>|TimeEntry whereBreakStart($value)
 * @method static Builder<static>|TimeEntry whereBreakEnd($value)
 * @method static Builder<static>|TimeEntry whereBreakDuration($value)
 * @method static Builder<static>|TimeEntry whereTotalHours($value)
 * @method static Builder<static>|TimeEntry whereRegularHours($value)
 * @method static Builder<static>|TimeEntry whereOvertimeHours($value)
 * @method static Builder<static>|TimeEntry whereLocationIn($value)
 * @method static Builder<static>|TimeEntry whereLocationOut($value)
 * @method static Builder<static>|TimeEntry whereDeviceInfo($value)
 * @method static Builder<static>|TimeEntry whereNotes($value)
 * @method static Builder<static>|TimeEntry whereEmployeeNotes($value)
 * @method static Builder<static>|TimeEntry whereSupervisorNotes($value)
 * @method static Builder<static>|TimeEntry whereStatus($value)
 * @method static Builder<static>|TimeEntry whereRejectionReason($value)
 * @method static Builder<static>|TimeEntry whereAnomalies($value)
 * @method static Builder<static>|TimeEntry whereCreatedAt($value)
 * @method static Builder<static>|TimeEntry whereUpdatedAt($value)
 * @method static Builder<static>|TimeEntry whereId($value)
 * @method static Builder<static>|TimeEntry whereEmployeeId($value)
 * @method static Builder<static>|TimeEntry whereLocationLat($value)
 * @method static Builder<static>|TimeEntry whereLocationLng($value)
 * @method static Builder<static>|TimeEntry whereLocationName($value)
 * @method static Builder<static>|TimeEntry wherePhotoPath($value)
 * @method static Builder<static>|TimeEntry whereTimestamp($value)
 * @method static Builder<static>|TimeEntry whereType($value)
 * @method static Builder<static>|TimeEntry withAnomalies()
 *
 * @mixin \Eloquent
 */
final class TimeEntry extends BaseModel
{
    public const string STATUS_PENDING = 'pending';

    public const string STATUS_APPROVED = 'approved';

    public const string STATUS_AUTO_APPROVED = 'auto_approved';

    public const string STATUS_REJECTED = 'rejected';

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
     * Get the employee that owns this time entry.
     *
     * @return BelongsTo<Employee, $this>
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the employee who approved this time entry.
     *
     * @return BelongsTo<Employee, $this>
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
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope to get entries for a specific employee.
     *
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeForEmployee(Builder $query, int $employeeId): Builder
    {
        return $query->where('employee_id', $employeeId);
    }

    /**
     * Scope to get entries with anomalies.
     *
     * @param  Builder<self>  $query
     * @return Builder<self>
     */
    public function scopeWithAnomalies(Builder $query): Builder
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
        return (bool) $this->anomalies;
    }

    /**
     * Check if entry is approved.
     */
    public function isApproved(): bool
    {
        return in_array($this->status, [self::STATUS_APPROVED, self::STATUS_AUTO_APPROVED], strict: true);
    }

    /**
     * Check if entry is pending.
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Check if entry is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }
}
