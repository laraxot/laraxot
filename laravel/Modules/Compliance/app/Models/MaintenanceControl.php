<?php

declare(strict_types=1);

namespace Modules\Compliance\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $heating_system_id
 * @property int|null $boiler_id
 * @property string $control_type
 * @property CarbonInterface $performed_on
 * @property string|null $performed_by_user_id
 * @property int|null $intervention_id
 * @property string|null $rcee_type
 * @property string|null $outcome
 * @property string|null $co_ppm
 * @property string|null $co2_percent
 * @property string|null $o2_percent
 * @property string|null $flue_gas_temp_c
 * @property string|null $air_temp_c
 * @property string|null $combustion_efficiency_percent
 * @property int|null $smoke_index
 * @property string|null $draft_pa
 * @property string|null $bollino_code
 * @property bool $bollino_paid
 * @property CarbonInterface|null $next_due_on
 * @property string|null $notes
 */
final class MaintenanceControl extends BaseModel
{
    protected $table = 'maintenance_controls';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'performed_on' => 'date',
            'next_due_on' => 'date',
            'co_ppm' => 'decimal:2',
            'co2_percent' => 'decimal:2',
            'o2_percent' => 'decimal:2',
            'flue_gas_temp_c' => 'decimal:2',
            'air_temp_c' => 'decimal:2',
            'combustion_efficiency_percent' => 'decimal:2',
            'smoke_index' => 'integer',
            'draft_pa' => 'decimal:2',
            'bollino_paid' => 'boolean',
        ];
    }

    /** @return BelongsTo<HeatingSystem, $this> */
    public function heatingSystem(): BelongsTo
    {
        return $this->belongsTo(HeatingSystem::class, 'heating_system_id');
    }

    /** @return BelongsTo<Boiler, $this> */
    public function boiler(): BelongsTo
    {
        return $this->belongsTo(Boiler::class, 'boiler_id');
    }
}
