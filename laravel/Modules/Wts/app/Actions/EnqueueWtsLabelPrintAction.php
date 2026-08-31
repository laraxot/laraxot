<?php

declare(strict_types=1);

namespace Modules\Wts\Actions;

use Modules\Wts\Models\WtsPrintJob;
use Modules\Wts\Models\WtsUnit;
use Illuminate\Support\Collection;

/**
 * Accoda la stampa dell'etichetta di uno o piu' colli: genera lo ZPL e crea un
 * WtsPrintJob in stato "pending". L'agente locale (Mojito sul PC di impianto) lo
 * preleva via API e lo invia all'etichettatrice.
 */
final readonly class EnqueueWtsLabelPrintAction
{
    public function __construct(
        private GenerateWtsUnitLabelZplAction $zplAction,
    ) {}

    public function execute(WtsUnit $unit, int $copies = 1, int|string|null $printedByUserId = null): WtsPrintJob
    {
        /** @var WtsPrintJob $job */
        $job = WtsPrintJob::query()->create([
            'wts_unit_id' => $unit->id,
            'label_code' => $unit->unit_code,
            'zpl' => $this->zplAction->execute($unit),
            'copies' => max(1, $copies),
            'status_code' => 'pending',
            'attempts' => 0,
        ]);

        // Registro stampe: anche l'invio all'etichettatrice va tracciato come le
        // stampe PDF, altrimenti le etichette fisiche non lasciano traccia.
        $unit->labelPrints()->create([
            'label_code' => $unit->unit_code.'-Z'.str_pad((string) ($unit->labelPrints()->withTrashed()->count() + 1), 2, '0', STR_PAD_LEFT),
            'qr_payload' => $unit->qr_code,
            'print_reason_code' => 'zpl_queue',
            'printed_by_user_id' => $printedByUserId,
            'printed_at' => now(),
        ]);

        return $job;
    }

    /**
     * @param  Collection<int, WtsUnit>  $units
     * @return Collection<int, WtsPrintJob>
     */
    public function executeMany(Collection $units, int $copies = 1, int|string|null $printedByUserId = null): Collection
    {
        return $units
            ->map(fn (WtsUnit $unit): WtsPrintJob => $this->execute($unit, $copies, $printedByUserId))
            ->values();
    }
}
