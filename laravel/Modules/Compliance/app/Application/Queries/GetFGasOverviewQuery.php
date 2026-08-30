<?php

declare(strict_types=1);

namespace Modules\Compliance\Application\Queries;

use DateTimeImmutable;
use Illuminate\Support\Facades\DB;
use Modules\Compliance\Actions\CalculateFGasObligationAction;
use Modules\Compliance\DTO\FGasObligation;
use Modules\Compliance\DTO\ValueObjects\ThermalPlantRegistry;
use Safe\DateTimeImmutable as SafeDateTimeImmutable;

/**
 * Reads the refrigerant circuits on file and works out each one's duty afresh.
 *
 * The obligation is recomputed rather than read back from the stored columns:
 * change a charge or swap a refrigerant and the schedule has to move with it.
 * Trusting the stored copy is how equipment ends up on a schedule that stopped
 * matching the gas inside it.
 */
final class GetFGasOverviewQuery
{
    public function __construct(
        private readonly CalculateFGasObligationAction $calculateObligation,
    ) {}

    /**
     * @return list<array{code: string, customer: string, description: string, refrigerant: string, charge_kg: float, obligation: FGasObligation, last_check: ?string, overdue: bool}>
     */
    public function circuits(?DateTimeImmutable $asOf = null): array
    {
        $asOf ??= new SafeDateTimeImmutable('today');

        $rows = DB::table('refrigerant_circuits')
            ->leftJoin('customers', 'customers.id', '=', 'refrigerant_circuits.customer_id')
            ->whereNull('refrigerant_circuits.deleted_at')
            ->where('refrigerant_circuits.is_active', true)
            ->select([
                'refrigerant_circuits.code',
                'refrigerant_circuits.description',
                'refrigerant_circuits.refrigerant_code',
                'refrigerant_circuits.charge_kg',
                'refrigerant_circuits.is_hermetically_sealed',
                'refrigerant_circuits.has_leak_detection_system',
                'refrigerant_circuits.last_leak_check_on',
                'customers.legal_name',
            ])
            ->get();

        /** @var list<array{code: string, customer: string, description: string, refrigerant: string, charge_kg: float, obligation: FGasObligation, last_check: ?string, overdue: bool}> $circuits */
        $circuits = [];

        foreach ($rows as $row) {
            $lastCheck = is_string($row->last_leak_check_on) && $row->last_leak_check_on !== ''
                ? new SafeDateTimeImmutable($row->last_leak_check_on)
                : null;

            $obligation = $this->calculateObligation->execute(
                refrigerantCode: is_string($row->refrigerant_code) ? $row->refrigerant_code : '',
                chargeKg: (float) $row->charge_kg,
                lastCheckedOn: $lastCheck,
                hasLeakDetectionSystem: (bool) $row->has_leak_detection_system,
                isHermeticallySealed: (bool) $row->is_hermetically_sealed,
            );

            $circuits[] = [
                'code' => is_string($row->code) ? $row->code : '',
                'customer' => is_string($row->legal_name) ? $row->legal_name : '',
                'description' => is_string($row->description) ? $row->description : '',
                'refrigerant' => is_string($row->refrigerant_code) ? $row->refrigerant_code : '',
                'charge_kg' => (float) $row->charge_kg,
                'obligation' => $obligation,
                'last_check' => $lastCheck?->format('d/m/Y'),
                'overdue' => $obligation->isOverdue($asOf),
            ];
        }

        // Prima i ritardi, poi le scadenze piu' vicine; gli esenti in fondo.
        usort($circuits, fn (array $a, array $b): int => [
            $this->sortRank($a['obligation'], (bool) $a['overdue']),
            $a['obligation']->nextCheckDueOn?->format('Y-m-d') ?? '9999',
        ] <=> [
            $this->sortRank($b['obligation'], (bool) $b['overdue']),
            $b['obligation']->nextCheckDueOn?->format('Y-m-d') ?? '9999',
        ]);

        return $circuits;
    }

    /**
     * Controls carried out but never filed with the regional register. Filing is
     * manual, so this is the list someone works through on the portal.
     *
     * @return list<array{customer: string, system: string, control_type: string, performed_on: string, registry: string, bollino_code: ?string}>
     */
    public function pendingRegistrySubmissions(): array
    {
        $rows = DB::table('maintenance_controls')
            ->join('heating_systems', 'heating_systems.id', '=', 'maintenance_controls.heating_system_id')
            ->leftJoin('customers', 'customers.id', '=', 'heating_systems.customer_id')
            ->whereNull('maintenance_controls.registry_submitted_on')
            ->whereNotNull('heating_systems.registry_code')
            ->orderBy('maintenance_controls.performed_on')
            ->select([
                'maintenance_controls.control_type',
                'maintenance_controls.performed_on',
                'maintenance_controls.bollino_code',
                'heating_systems.code as system_code',
                'heating_systems.registry_code',
                'customers.legal_name',
            ])
            ->get();

        $pending = [];

        foreach ($rows as $row) {
            $registryCode = is_string($row->registry_code) ? $row->registry_code : '';

            $pending[] = [
                'customer' => is_string($row->legal_name) ? $row->legal_name : '',
                'system' => is_string($row->system_code) ? $row->system_code : '',
                'control_type' => is_string($row->control_type) ? $row->control_type : '',
                'performed_on' => is_string($row->performed_on)
                    ? (new SafeDateTimeImmutable($row->performed_on))->format('d/m/Y')
                    : '',
                'registry' => ThermalPlantRegistry::nameForCode($registryCode) ?? $registryCode,
                'bollino_code' => is_string($row->bollino_code) ? $row->bollino_code : null,
            ];
        }

        return $pending;
    }

    /**
     * Chi e' in ritardo va guardato per primo; poi chi ha un obbligo vivo, poi
     * chi non e' valutabile (manca il dato per dirlo) e infine gli esenti.
     */
    private function sortRank(FGasObligation $obligation, bool $isOverdue): int
    {
        return match (true) {
            $isOverdue => 0,
            $obligation->requiresLeakChecks() => 1,
            $obligation->isUnassessable() => 2,
            default => 3,
        };
    }
}
