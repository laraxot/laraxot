<?php

declare(strict_types=1);

namespace Modules\Employee\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Employee\Database\Factories\TimeRecordFactory;
use Modules\User\Models\User;
use Modules\Xot\Contracts\ProfileContract;

/**
 * Class TimeRecord.
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
 * @property-read ProfileContract|null $creator
 * @property-read string $formatted_date
 * @property-read string $formatted_time
 * @property-read string $formatted_timestamp
 * @property-read ProfileContract|null $updater
 *
 * @method static Builder<static>|TimeRecord forDate(\Carbon\Carbon $date)
 * @method static Builder<static>|TimeRecord forUser(int $userId)
 * @method static Builder<static>|TimeRecord newModelQuery()
 * @method static Builder<static>|TimeRecord newQuery()
 * @method static Builder<static>|TimeRecord ofType(string $type)
 * @method static Builder<static>|TimeRecord query()
 * @method static Builder<static>|TimeRecord valid()
 *
 * @property-read ProfileContract|null $deleter
 *
 * @method static TimeRecordFactory factory($count = null, $state = [])
 *
 * @mixin \Eloquent
 */
class TimeRecord extends BaseModel
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
     * Get the user that owns the timbratura.
     *
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user that created the timbratura.
     *
     * @return BelongsTo<User, $this>
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user that updated the timbratura.
     *
     * @return BelongsTo<User, $this>
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope a query to only include time records for a specific user.
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include time records of a specific type.
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to only include time records for a specific date.
     *
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeForDate($query, Carbon $date)
    {
        return $query->whereDate('timestamp', $date);
    }

    /**
     * Scope a query to only include valid time records.
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
     * Check if the time record is an entry.
     */
    public function isEntry(): bool
    {
        return $this->type === 'entry';
    }

    /**
     * Check if the time record is an exit.
     */
    public function isExit(): bool
    {
        return $this->type === 'exit';
    }

    /**
     * Check if the time record is manual.
     */
    public function isManual(): bool
    {
        return $this->is_manual;
    }

    /**
     * Check if the time record has location data.
     */
    public function hasLocation(): bool
    {
        return ! empty($this->latitude) && ! empty($this->longitude);
    }
}
