<?php

declare(strict_types=1);

namespace Modules\Compliance\Actions;

use Carbon\CarbonImmutable;
use Modules\Compliance\Models\Boiler;
use Modules\Compliance\Models\HeatingSystem;
use Modules\Compliance\Models\MaintenanceControl;
use Spatie\QueueableAction\QueueableAction;

/**
 * Records a maintenance or energy-efficiency (RCEE) control for a heating system,
 * computes the next legal due date from the applicable periodicity, and rolls the
 * boiler's due dates forward. Combustion-analysis and bollino fields are captured
 * as provided.
 */
final class RegisterMaintenanceControlAction
{
    use QueueableAction;

    private const MAINTENANCE = 'maintenance';

    private const EFFICIENCY = 'efficiency_check';

    /** @var list<string> */
    private const FIELDS = [
        'performed_by_user_id', 'intervention_id', 'rcee_type', 'outcome',
        'co_ppm', 'co2_percent', 'o2_percent', 'flue_gas_temp_c', 'air_temp_c',
        'combustion_efficiency_percent', 'smoke_index', 'draft_pa',
        'bollino_code', 'bollino_paid', 'notes',
    ];

    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(HeatingSystem $system, ?Boiler $boiler, array $data): MaintenanceControl
    {
        $controlType = ($data['control_type'] ?? self::MAINTENANCE) === self::EFFICIENCY
            ? self::EFFICIENCY
            : self::MAINTENANCE;

        $performedOn = isset($data['performed_on']) && is_string($data['performed_on']) && $data['performed_on'] !== ''
            ? CarbonImmutable::parse($data['performed_on'])
            : CarbonImmutable::now();

        $nextDue = $performedOn->addMonths($this->intervalMonths($controlType, $boiler));

        $attributes = [
            'heating_system_id' => $system->getKey(),
            'boiler_id' => $boiler?->getKey(),
            'control_type' => $controlType,
            'performed_on' => $performedOn,
            'next_due_on' => $nextDue,
        ];
        foreach (self::FIELDS as $field) {
            if (array_key_exists($field, $data)) {
                $attributes[$field] = $data[$field];
            }
        }

        /** @var MaintenanceControl $control */
        $control = MaintenanceControl::query()->create($attributes);

        if ($boiler !== null) {
            $column = $controlType === self::EFFICIENCY ? 'next_efficiency_check_due_on' : 'next_maintenance_due_on';
            $boiler->forceFill([$column => $nextDue])->save();
        }

        return $control;
    }

    private function intervalMonths(string $controlType, ?Boiler $boiler): int
    {
        if ($controlType === self::MAINTENANCE) {
            $override = $boiler?->maintenance_interval_months;

            return $override !== null && $override > 0
                ? $override
                : $this->configInt('heating.default_maintenance_interval_months', 12);
        }

        $override = $boiler?->efficiency_check_interval_months;
        if ($override !== null && $override > 0) {
            return $override;
        }

        $fuel = is_string($boiler?->fuel_type) ? $boiler->fuel_type : '';
        /** @var list<string> $liquidSolid */
        $liquidSolid = (array) config('heating.efficiency_check.liquid_solid_fuels', []);
        if (in_array($fuel, $liquidSolid, true)) {
            return $this->configInt('heating.efficiency_check.liquid_solid_months', 24);
        }

        $power = $boiler?->power_kw !== null ? (float) $boiler->power_kw : 0.0;
        $threshold = (float) $this->configInt('heating.efficiency_check.high_power_threshold_kw', 100);
        if ($power >= $threshold && $power > 0.0) {
            return $this->configInt('heating.efficiency_check.high_power_months', 24);
        }

        return $this->configInt('heating.efficiency_check.default_months', 48);
    }

    private function configInt(string $key, int $default): int
    {
        $value = config($key, $default);

        return is_numeric($value) ? (int) $value : $default;
    }
}
