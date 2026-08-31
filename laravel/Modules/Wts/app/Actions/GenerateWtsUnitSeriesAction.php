<?php

declare(strict_types=1);

namespace Modules\Wts\Actions;

use Modules\Wts\Models\WtsInboundLot;
use Modules\Wts\Models\WtsLabelPrint;
use Modules\Wts\Models\WtsTechnicalSheetSnapshot;
use Modules\Wts\Models\WtsUnit;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use InvalidArgumentException;

final class GenerateWtsUnitSeriesAction
{
    /**
     * @return Collection<int, WtsUnit>
     */
    public function execute(
        WtsInboundLot $lot,
        string $unitTypeCode,
        int $unitsCount,
        ?WtsTechnicalSheetSnapshot $technicalSheetSnapshot,
        string $printReasonCode,
        int|string|null $printedByUserId = null,
    ): Collection {
        if ($unitsCount <= 0) {
            throw new InvalidArgumentException('The unit count must be greater than zero.');
        }

        $normalizedType = trim($unitTypeCode);
        if ($normalizedType === '') {
            throw new InvalidArgumentException('The unit type is required.');
        }

        // withTrashed: i colli rimossi (soft delete) mantengono unit_code/qr_code
        // univoci, quindi la sequenza non deve riusare i loro numeri.
        $maxSequence = $lot->units()->withTrashed()->max('generated_sequence');
        $startSequence = (is_numeric($maxSequence) ? (int) $maxSequence : 0) + 1;
        $generatedUnits = [];

        for ($sequence = $startSequence; $sequence < $startSequence + $unitsCount; $sequence++) {
            /** @var WtsUnit $unit */
            $unit = $lot->units()->create([
                'technical_sheet_snapshot_id' => $technicalSheetSnapshot?->id,
                'unit_code' => $this->buildUnitCode($lot->lot_code, $normalizedType, $sequence),
                'qr_code' => $this->buildQrCode($lot->lot_code, $normalizedType, $sequence),
                'unit_type_code' => $normalizedType,
                'status_code' => 'available',
                'current_area_code' => 'to_process',
                'generated_sequence' => $sequence,
            ]);

            /** @var WtsLabelPrint $labelPrint */
            $labelPrint = $unit->labelPrints()->create([
                'label_code' => $unit->unit_code,
                'qr_payload' => $unit->qr_code,
                'print_reason_code' => $printReasonCode,
                'printed_by_user_id' => $printedByUserId,
                'printed_at' => now(),
            ]);

            $unit->setRelation('labelPrints', collect([$labelPrint]));
            $generatedUnits[] = $unit;
        }

        $lot->forceFill([
            'initial_units_count' => $lot->units()->count(),
            'current_area_code' => 'to_process',
        ])->save();

        return new Collection($generatedUnits);
    }

    private function buildUnitCode(string $lotCode, string $unitTypeCode, int $sequence): string
    {
        $typeSegment = Str::of($unitTypeCode)
            ->replace('_', '-')
            ->upper()
            ->value();

        return sprintf('%s-%s-%02d', $lotCode, $typeSegment, $sequence);
    }

    private function buildQrCode(string $lotCode, string $unitTypeCode, int $sequence): string
    {
        return sprintf(
            'WTS-QR-%s-%s-%02d',
            Str::upper(Str::slug($lotCode, '')),
            Str::upper(Str::slug($unitTypeCode, '')),
            $sequence,
        );
    }
}
