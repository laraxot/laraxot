<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Actions;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Modules\TimberBilling\Models\TimberDerivedMaterial;
use Modules\TimberBilling\Models\TimberDryingCycle;
use Modules\TimberBilling\Models\TimberLot;

final class StartTimberDryingCycleAction
{
    public function __construct(
        private readonly RecordTimberMaterialStageAction $recordStage,
    ) {}

    public function execute(
        TimberLot|TimberDerivedMaterial $dryable,
        ?string $expectedExitAt,
        ?float $targetHumidityPercent,
        ?string $notes,
    ): TimberDryingCycle {
        $availableQuantity = $dryable instanceof TimberLot
            ? (float) $dryable->quantity_available
            : (float) $dryable->quantity;

        if ($availableQuantity <= 0) {
            throw new InvalidArgumentException('Il materiale non ha quantita disponibile da avviare in essiccazione.');
        }

        if ($targetHumidityPercent !== null && ($targetHumidityPercent < 0 || $targetHumidityPercent > 100)) {
            throw new InvalidArgumentException('L umidita target deve essere compresa tra 0 e 100.');
        }

        return DB::transaction(function () use ($dryable, $expectedExitAt, $targetHumidityPercent, $notes, $availableQuantity): TimberDryingCycle {
            /** @var TimberDryingCycle $cycle */
            $cycle = $dryable->dryingCycles()->create([
                'entered_at' => now(),
                'expected_exit_at' => $expectedExitAt,
                'target_humidity_percent' => $targetHumidityPercent ?? config('timber.operational_parameters.target_humidity_percent.value'),
                'available_quantity' => $availableQuantity,
                'status_code' => 'in_progress',
                'notes' => trim((string) $notes) !== '' ? trim((string) $notes) : null,
            ]);

            $dryable->forceFill([
                'status_code' => 'in_essiccazione',
                'current_area_code' => 'essiccatoio',
            ])->save();

            // Ingresso in essiccatoio = inizio lavorazione: arricchisce il codice
            // (·ESS) e ristampa l'etichetta del semilavorato.
            if ($dryable instanceof TimberDerivedMaterial) {
                $this->recordStage->execute($dryable, 'essiccazione', appendSegment: true, reasonCode: 'drying_start');
            }

            return $cycle;
        });
    }
}
