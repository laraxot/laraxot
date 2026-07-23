<?php

declare(strict_types=1);

namespace Modules\Compliance\Queries;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Modules\Compliance\DTO\HeatingComplianceRow;
use Modules\Compliance\Models\Boiler;

/**
 * Builds the heating-compliance deadlines overview: one row per boiler with its
 * next maintenance and efficiency-control due dates and an aggregate status
 * (overdue | expiring | ok | unknown), worst first.
 */
final class GetHeatingComplianceOverviewQuery
{
    /** @var array<string, int> */
    private const STATUS_RANK = ['overdue' => 0, 'expiring' => 1, 'ok' => 2, 'unknown' => 3];

    /**
     * @return list<HeatingComplianceRow>
     */
    public function execute(?int $withinDays = null): array
    {
        $today = CarbonImmutable::today();
        $days = $withinDays ?? $this->configInt('heating.expiring_within_days', 30);

        $boilers = Boiler::query()
            ->with(['heatingSystem.customer'])
            ->get();

        $rows = [];
        foreach ($boilers as $boiler) {
            /** @var Boiler $boiler */
            $system = $boiler->heatingSystem;
            if ($system === null || ! $system->is_active) {
                continue;
            }

            $maintenance = $this->toDate($boiler->next_maintenance_due_on);
            $efficiency = $this->toDate($boiler->next_efficiency_check_due_on);
            $customerName = $system->customer?->legal_name;

            $rows[] = new HeatingComplianceRow(
                systemId: (int) $system->id,
                boilerId: (int) $boiler->id,
                customerName: is_string($customerName) && $customerName !== '' ? $customerName : '—',
                systemLabel: (string) ($system->code ?: $system->description ?: ('Impianto #'.$system->id)),
                boilerLabel: trim(implode(' ', array_filter([$boiler->brand, $boiler->model]))) ?: ('Generatore #'.$boiler->id),
                maintenanceDueOn: $maintenance?->format('Y-m-d'),
                efficiencyDueOn: $efficiency?->format('Y-m-d'),
                status: $this->statusFor($maintenance, $efficiency, $today, $days),
            );
        }

        usort($rows, function (HeatingComplianceRow $a, HeatingComplianceRow $b): int {
            $rank = self::STATUS_RANK[$a->status] <=> self::STATUS_RANK[$b->status];

            return $rank !== 0 ? $rank : (($a->maintenanceDueOn ?? '9999') <=> ($b->maintenanceDueOn ?? '9999'));
        });

        return $rows;
    }

    public function statusFor(?CarbonInterface $maintenance, ?CarbonInterface $efficiency, CarbonInterface $today, int $withinDays): string
    {
        $dates = array_values(array_filter([$maintenance, $efficiency]));
        if ($dates === []) {
            return 'unknown';
        }

        $status = 'ok';
        foreach ($dates as $date) {
            if ($date->lessThan($today)) {
                return 'overdue';
            }
            if ($date->lessThanOrEqualTo($today->addDays($withinDays))) {
                $status = 'expiring';
            }
        }

        return $status;
    }

    private function configInt(string $key, int $default): int
    {
        $value = config($key, $default);

        return is_numeric($value) ? (int) $value : $default;
    }

    private function toDate(mixed $value): ?CarbonImmutable
    {
        if ($value instanceof CarbonInterface) {
            return CarbonImmutable::instance($value);
        }

        return is_string($value) && $value !== '' ? CarbonImmutable::parse($value) : null;
    }
}
