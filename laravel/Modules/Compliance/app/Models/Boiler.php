<?php

declare(strict_types=1);

namespace Modules\Compliance\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Xot\Models\XotBaseModel;

/**
 * @property int $id
 * @property int $heating_system_id
 * @property string|null $brand
 * @property string|null $model
 * @property string|null $serial_number
 * @property string|null $generator_type
 * @property string|null $fuel_type
 * @property string|null $power_kw
 * @property CarbonInterface|null $installation_date
 * @property int|null $maintenance_interval_months
 * @property int|null $efficiency_check_interval_months
 * @property CarbonInterface|null $next_maintenance_due_on
 * @property CarbonInterface|null $next_efficiency_check_due_on
 * @property string|null $notes
 */
final class Boiler extends XotBaseModel
{
    use SoftDeletes;

    protected $table = 'boilers';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'power_kw' => 'decimal:2',
            'installation_date' => 'date',
            'maintenance_interval_months' => 'integer',
            'efficiency_check_interval_months' => 'integer',
            'next_maintenance_due_on' => 'date',
            'next_efficiency_check_due_on' => 'date',
            'deleted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<HeatingSystem, $this> */
    public function heatingSystem(): BelongsTo
    {
        return $this->belongsTo(HeatingSystem::class, 'heating_system_id');
    }

    /** @return HasMany<MaintenanceControl, $this> */
    public function controls(): HasMany
    {
        return $this->hasMany(MaintenanceControl::class);
    }
}
