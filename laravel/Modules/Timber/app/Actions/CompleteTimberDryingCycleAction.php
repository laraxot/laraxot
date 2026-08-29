<?php

declare(strict_types=1);

namespace Modules\Timber\Actions;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Modules\Timber\Models\TimberDerivedMaterial;
use Modules\Timber\Models\TimberDryingCycle;
use Modules\Timber\Models\TimberLot;

final class CompleteTimberDryingCycleAction
{
    public function __construct(
        private readonly RecordTimberMaterialStageAction $recordStage,
    ) {}

    public function execute(TimberDryingCycle $cycle): TimberDryingCycle
    {
        if ($cycle->status_code !== 'in_progress') {
            throw new InvalidArgumentException('Il ciclo di essiccazione non e in corso.');
        }

        return DB::transaction(function () use ($cycle): TimberDryingCycle {
            $cycle->forceFill([
                'status_code' => 'completed',
                'actual_exit_at' => now(),
            ])->save();

            $dryable = $cycle->dryable;

            if ($dryable instanceof TimberLot) {
                $dryable->forceFill([
                    'status_code' => 'available',
                    'current_area_code' => 'area_stagionatura',
                ])->save();
            } elseif ($dryable instanceof TimberDerivedMaterial) {
                $dryable->forceFill([
                    'status_code' => 'pronto_da_confezionare',
                    'current_area_code' => 'deposito_semilavorati',
                ])->save();

                // Fine essiccazione: registra il passaggio (senza allungare il
                // codice, l'·ESS c'e' gia') e ristampa l'etichetta.
                $this->recordStage->execute($dryable, 'essiccazione_fine', appendSegment: false, reasonCode: 'drying_complete');
            }

            return $cycle->refresh();
        });
    }
}
