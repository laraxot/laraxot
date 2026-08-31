<?php

declare(strict_types=1);

namespace Modules\Timber\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Timber\Database\Factories\TimberHumidityCheckFactory;
use Modules\User\Models\User;

/**
 * @property int $drying_cycle_id
 * @property Carbon $checked_at
 * @property string $humidity_percent
 * @property int|null $checked_by_user_id
 * @property string|null $notes
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class TimberHumidityCheck extends BaseModel
{

    protected $table = 'timber_humidity_checks';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'checked_at' => 'datetime',
            'humidity_percent' => 'decimal:2',
        ];
    }

    /** @return BelongsTo<TimberDryingCycle, $this> */
    public function dryingCycle(): BelongsTo
    {
        return $this->belongsTo(TimberDryingCycle::class, 'drying_cycle_id');
    }

    /** @return BelongsTo<User, $this> */
    public function checkedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checked_by_user_id');
    }

    protected static function newFactory(): TimberHumidityCheckFactory
    {
        return TimberHumidityCheckFactory::new();
    }
}
