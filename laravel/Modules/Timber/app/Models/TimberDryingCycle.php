<?php

declare(strict_types=1);

namespace Modules\Timber\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Modules\Timber\Database\Factories\TimberDryingCycleFactory;

/**
 * @property Carbon $entered_at
 * @property Carbon|null $expected_exit_at
 * @property Carbon|null $actual_exit_at
 * @property string|null $target_humidity_percent
 * @property string $available_quantity
 * @property string $status_code
 * @property string|null $notes
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class TimberDryingCycle extends BaseModel
{

    protected $table = 'timber_drying_cycles';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'entered_at' => 'datetime',
            'expected_exit_at' => 'datetime',
            'actual_exit_at' => 'datetime',
            'target_humidity_percent' => 'decimal:2',
            'available_quantity' => 'decimal:2',
        ];
    }

    /** @return MorphTo<Model, $this> */
    public function dryable(): MorphTo
    {
        return $this->morphTo();
    }

    /** @return HasMany<TimberHumidityCheck, $this> */
    public function humidityChecks(): HasMany
    {
        return $this->hasMany(TimberHumidityCheck::class, 'drying_cycle_id');
    }

    protected static function newFactory(): TimberDryingCycleFactory
    {
        return TimberDryingCycleFactory::new();
    }
}
