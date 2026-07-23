<?php

declare(strict_types=1);

namespace Modules\Production\Actions;

use Carbon\CarbonImmutable;
use Modules\Production\Models\ProductionPhase;

final class AdvanceProductionPhaseAction
{
    public function execute(ProductionPhase $phase, string $toStatus): ProductionPhase
    {
        $now = CarbonImmutable::now();

        if ($toStatus === 'in_progress') {
            $phase->forceFill([
                'status' => 'in_progress',
                'started_at' => $phase->started_at ?? $now,
            ])->save();
        } elseif ($toStatus === 'completed') {
            $phase->forceFill([
                'status' => 'completed',
                'completed_at' => $phase->completed_at ?? $now,
            ])->save();
        }

        return $phase;
    }
}

