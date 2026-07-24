<?php

declare(strict_types=1);

namespace Modules\Intervention\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Intervention\Database\Factories\TimePunchFactory;
use Modules\User\Models\User;

/**
 * @property int $id
 * @property int $intervention_id
 * @property int $user_id
 * @property Carbon $started_at
 * @property Carbon|null $ended_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Intervention|null $intervention
 * @property-read User|null $user
 *
 * @method static TimePunchFactory factory($count = null, $state = [])
 */
final class TimePunch extends BaseModel
{
    /** @use HasFactory<TimePunchFactory> */
    use HasFactory;

    protected $table = 'time_punches';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<Intervention, $this> */
    public function intervention(): BelongsTo
    {
        return $this->belongsTo(Intervention::class);
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
