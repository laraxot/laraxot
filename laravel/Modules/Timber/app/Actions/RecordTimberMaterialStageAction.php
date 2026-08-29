<?php

declare(strict_types=1);

namespace Modules\Timber\Actions;

use Modules\Timber\Models\TimberDerivedMaterial;
use Modules\Timber\Support\TimberTraceCode;

/**
 * Registra una tappa sul semilavorato: arricchisce il codice tracciabile con il
 * segmento della tappa (opzionale) e ristampa l'etichetta con il QR aggiornato.
 */
final class RecordTimberMaterialStageAction
{
    public function __construct(
        private readonly BuildTimberQrPayloadAction $qrPayload,
    ) {}

    public function execute(
        TimberDerivedMaterial $material,
        string $stageTypeCode,
        ?int $userId = null,
        bool $appendSegment = true,
        string $reasonCode = 'reprint',
    ): TimberDerivedMaterial {
        // Se il semilavorato non ha ancora un codice tracciabile (materiale
        // legacy), si parte dal suo output_code per non generare codici degeneri.
        $current = trim((string) ($material->traceable_code ?? ''));
        if ($current === '') {
            $current = (string) $material->output_code;
        }
        $segment = TimberTraceCode::marker($stageTypeCode);

        if ($appendSegment && ! self::alreadyEndsWith($current, $segment)) {
            $current = TimberTraceCode::append($current, $segment);
        }

        $history = is_array($material->stage_history) ? $material->stage_history : [];
        $history[] = [
            'stage' => $stageTypeCode,
            'segment' => $segment,
            'code' => $current,
            'date' => now()->toDateString(),
        ];

        $material->forceFill([
            'traceable_code' => $current !== '' ? $current : null,
            'stage_type_code' => $stageTypeCode,
            'stage_history' => $history,
        ])->save();

        $material->labelPrints()->create([
            'label_code' => $current !== '' ? $current : $material->output_code,
            'qr_payload' => $this->qrPayload->execute($material->refresh()),
            'print_reason_code' => $reasonCode,
            'printed_by_user_id' => $userId,
            'printed_at' => now(),
        ]);

        return $material->refresh();
    }

    private static function alreadyEndsWith(string $code, string $segment): bool
    {
        return $code === $segment || str_ends_with($code, TimberTraceCode::SEPARATOR.$segment);
    }
}
