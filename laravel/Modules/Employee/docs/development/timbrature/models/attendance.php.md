<?php

declare(strict_types=1);

namespace Modules\Employee\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\User\Models\User;

/**
 * Class Attendance.
 *
 * @property int $id
 * @property int $user_id
 * @property Carbon $timestamp
 * @property string $type
 * @property string $method
 * @property string|null $latitude
 * @property string|null $longitude
 * @property string|null $address
 * @property string|null $notes
 * @property string $status
 * @property bool $is_manual
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 * @property-read User|null $createdBy
 * @property-read User|null $updatedBy
 */
class Attendance extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'timestamp',
        'type',
        'method',
        'latitude',
        'longitude',
        'address',
        'notes',
        'status',
        'is_manual',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'timestamp' => 'datetime',
            'is_manual' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the attendance record.
     *
     * @return BelongsTo<User, Attendance>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user that created the attendance record.
     *
     * @return BelongsTo<User, Attendance>
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user that updated the attendance record.
     *
     * @return BelongsTo<User, Attendance>
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope a query to only include attendance records for a specific user.
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include attendance records of a specific type.
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to only include attendance records for a specific date.
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeForDate($query, Carbon $date)
    {
        return $query->whereDate('timestamp', $date);
    }

    /**
     * Scope a query to only include valid attendance records.
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeValid($query)
    {
        return $query->where('status', 'valid');
    }

    /**
     * Get the formatted timestamp.
     */
    public function getFormattedTimestampAttribute(): string
    {
        return $this->timestamp->format('d/m/Y H:i:s');
    }

    /**
     * Get the formatted time only.
     */
    public function getFormattedTimeAttribute(): string
    {
        return $this->timestamp->format('H:i:s');
    }

    /**
     * Get the formatted date only.
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->timestamp->format('d/m/Y');
    }

    /**
     * Check if the attendance record is an entry.
     */
    public function isEntry(): bool
    {
        return $this->type === 'entry';
    }

    /**
     * Check if the attendance record is an exit.
     */
    public function isExit(): bool
    {
        return $this->type === 'exit';
    }

    /**
     * Check if the attendance record is manual.
     */
    public function isManual(): bool
    {
        return $this->is_manual;
    }

    /**
     * Check if the attendance record has location data.
     */
    public function hasLocation(): bool
    {
        return ! empty($this->latitude) && ! empty($this->longitude);
    }
}
