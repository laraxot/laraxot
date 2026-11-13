<?php

declare(strict_types=1);

namespace Modules\Employee\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Employee\Database\Factories\AbsenceRequestFactory;
use Modules\Employee\Enums\AbsenceRequestStatusEnum;

/**
 * Class AbsenceRequest.
 *
 * Richiesta di assenza (ferie/permesso/malattia/infortunio) di un dipendente,
 * soggetta ad approvazione/rifiuto da parte di un responsabile.
 *
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property Carbon $starts_at
 * @property Carbon $ends_at
 * @property string|null $notes
 * @property string $status
 * @property string|null $decided_by_user_id
 * @property Carbon|null $decided_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Employee|null $user
 * @property-read Employee|null $decidedBy
 *
 * @method static AbsenceRequestFactory factory($count = null, $state = [])
 * @method static Builder<static>|AbsenceRequest newModelQuery()
 * @method static Builder<static>|AbsenceRequest newQuery()
 * @method static Builder<static>|AbsenceRequest query()
 *
 * @mixin \Eloquent
 */
class AbsenceRequest extends BaseModel
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'type',
        'starts_at',
        'ends_at',
        'notes',
        'status',
        'decided_by_user_id',
        'decided_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'decided_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Employee who submitted the absence request.
     *
     * @return BelongsTo<Employee, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'user_id');
    }

    /**
     * Employee (manager) who approved/rejected the absence request.
     *
     * @return BelongsTo<Employee, $this>
     */
    public function decidedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'decided_by_user_id');
    }

    /**
     * Scope a query to only include pending requests.
     *
     * @param  Builder<AbsenceRequest>  $query
     * @return Builder<AbsenceRequest>
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', AbsenceRequestStatusEnum::PENDING->value);
    }

    /**
     * Scope a query to only include requests for the given user.
     *
     * @param  Builder<AbsenceRequest>  $query
     * @return Builder<AbsenceRequest>
     */
    public function scopeForUser(Builder $query, int|string $userId): Builder
    {
        return $query->where('user_id', $userId);
    }
}
