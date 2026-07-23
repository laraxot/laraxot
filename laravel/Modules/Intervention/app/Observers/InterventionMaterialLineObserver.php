<?php

declare(strict_types=1);

namespace Modules\Intervention\Observers;

use Modules\Intervention\Actions\SyncInterventionExtraMaterialsAction;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\InterventionMaterialLine;

/**
 * Ricalcola l'extra preventivo ogni volta che le righe materiale cambiano.
 *
 * Sta su un observer e non nella resource Filament perche' le righe materiale
 * vengono scritte da piu' punti (pannello admin, pannello operatore, import,
 * seeder): agganciare il ricalcolo alla sola form dell'admin lascerebbe
 * extra_quantity e extra_amount a zero ovunque altro, cioe' esattamente il
 * problema che si vuole evitare — materiale fuori preventivo che nessuno vede
 * e che finisce regalato.
 */
final class InterventionMaterialLineObserver
{
    /**
     * Evita rientri se un giorno il ricalcolo dovesse passare da Eloquent:
     * oggi scrive con il query builder, che non emette eventi di modello.
     */
    private static bool $syncing = false;

    public function saved(InterventionMaterialLine $line): void
    {
        $this->sync($line);
    }

    public function deleted(InterventionMaterialLine $line): void
    {
        $this->sync($line);
    }

    private function sync(InterventionMaterialLine $line): void
    {
        if (self::$syncing) {
            return;
        }

        $interventionId = $line->intervention_id;

        if ($interventionId === null) {
            return;
        }

        $intervention = Intervention::query()->find($interventionId);

        if (! $intervention instanceof Intervention) {
            return;
        }

        self::$syncing = true;

        try {
            app(SyncInterventionExtraMaterialsAction::class)->execute($intervention);
        } finally {
            self::$syncing = false;
        }
    }
}
