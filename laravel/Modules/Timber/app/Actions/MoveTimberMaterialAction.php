<?php

declare(strict_types=1);

namespace Modules\Timber\Actions;

use InvalidArgumentException;
use Modules\Timber\Models\TimberDerivedMaterial;
use Modules\Timber\Models\TimberLot;

final class MoveTimberMaterialAction
{
    public function __construct(
        private readonly RecordTimberMaterialStageAction $recordStage,
    ) {}

    public function execute(
        TimberLot|TimberDerivedMaterial $material,
        string $toAreaCode,
        ?int $userId,
        ?string $notes,
    ): TimberLot|TimberDerivedMaterial {
        $normalizedAreaCode = trim($toAreaCode);
        $configuredAreas = config('timber.timber.area_codes', []);
        $allowedAreas = is_array($configuredAreas) ? array_keys($configuredAreas) : [];

        if ($normalizedAreaCode === '' || ! in_array($normalizedAreaCode, $allowedAreas, true)) {
            throw new InvalidArgumentException('Area non valida.');
        }

        $fromAreaCode = $material->current_area_code;

        $material->forceFill(['current_area_code' => $normalizedAreaCode])->save();

        $material->movements()->create([
            'from_area_code' => $fromAreaCode,
            'to_area_code' => $normalizedAreaCode,
            'moved_by_user_id' => $userId,
            'moved_at' => now(),
            'notes' => trim((string) $notes) !== '' ? trim((string) $notes) : null,
        ]);

        // Spostamento di un semilavorato in un'area-tappa (essiccatoio/stagionatura):
        // arricchisce il codice tracciabile e ristampa l'etichetta.
        if ($material instanceof TimberDerivedMaterial) {
            $markers = config('timber.timber.area_stage_markers', []);
            $stageType = is_array($markers) && isset($markers[$normalizedAreaCode]) && is_string($markers[$normalizedAreaCode])
                ? $markers[$normalizedAreaCode]
                : null;

            if ($stageType !== null) {
                return $this->recordStage->execute($material, $stageType, $userId, appendSegment: true, reasonCode: 'move');
            }
        }

        return $material->refresh();
    }
}
