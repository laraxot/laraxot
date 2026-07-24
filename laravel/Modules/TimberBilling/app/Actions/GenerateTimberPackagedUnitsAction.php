<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Modules\TimberBilling\Models\TimberDerivedMaterial;
use Modules\TimberBilling\Models\TimberLabelPrint;
use Modules\TimberBilling\Models\TimberPackagedUnit;

final class GenerateTimberPackagedUnitsAction
{
    public function __construct(
        private readonly BuildTimberQrPayloadAction $qrPayload,
    ) {}

    /**
     * @return Collection<int, TimberPackagedUnit>
     */
    public function execute(
        TimberDerivedMaterial $material,
        string $packageTypeCode,
        int $unitsCount,
        float $quantityPerUnit,
        ?int $userId = null,
        ?string $articleCode = null,
        ?string $traceableCode = null,
    ): Collection {
        $normalizedType = trim($packageTypeCode);
        $configuredTypes = config('timberbilling.timber.package_types', []);
        $allowedTypes = is_array($configuredTypes) ? array_keys($configuredTypes) : [];
        if ($normalizedType === '' || ! in_array($normalizedType, $allowedTypes, true)) {
            throw new InvalidArgumentException('Il tipo di confezione non e valido.');
        }

        if ($unitsCount <= 0) {
            throw new InvalidArgumentException('Il numero di confezioni deve essere maggiore di zero.');
        }

        if ($quantityPerUnit <= 0) {
            throw new InvalidArgumentException('La quantita per confezione deve essere maggiore di zero.');
        }

        if ($material->is_waste) {
            throw new InvalidArgumentException('Il materiale di scarto non puo essere confezionato.');
        }

        $totalQuantity = $unitsCount * $quantityPerUnit;
        if ($totalQuantity > (float) $material->quantity) {
            throw new InvalidArgumentException('La quantita richiesta supera quella disponibile sul materiale.');
        }

        $normalizedArticle = trim((string) $articleCode) !== '' ? trim((string) $articleCode) : null;
        $normalizedTrace = trim((string) $traceableCode) !== '' ? trim((string) $traceableCode) : null;

        return DB::transaction(function () use ($material, $normalizedType, $unitsCount, $quantityPerUnit, $userId, $totalQuantity, $normalizedArticle, $normalizedTrace): Collection {
            $generatedUnits = collect();

            for ($sequence = 1; $sequence <= $unitsCount; $sequence++) {
                $packageCode = $this->buildPackageCode($material->output_code, $normalizedType, $sequence);

                /** @var TimberPackagedUnit $unit */
                $unit = $material->packagedUnits()->create([
                    'package_code' => $packageCode,
                    'qr_code' => 'TMB-QR-'.Str::upper(Str::slug($packageCode, '')),
                    'package_type_code' => $normalizedType,
                    'article_code' => $normalizedArticle,
                    'traceable_code' => $normalizedTrace,
                    'quantity' => $quantityPerUnit,
                    'unit_of_measure' => $material->unit_of_measure,
                    'status_code' => 'pronto_per_vendita',
                ]);

                $unit->forceFill(['qr_code' => $this->qrPayload->execute($unit)])->save();

                /** @var TimberLabelPrint $labelPrint */
                $labelPrint = $unit->labelPrints()->create([
                    'label_code' => $normalizedTrace ?? $unit->package_code,
                    'qr_payload' => $unit->qr_code,
                    'print_reason_code' => 'initial_print',
                    'printed_by_user_id' => $userId,
                    'printed_at' => now(),
                ]);

                $unit->setRelation('labelPrints', collect([$labelPrint]));
                $generatedUnits->push($unit);
            }

            $material->forceFill([
                'quantity' => max((float) $material->quantity - $totalQuantity, 0),
                'status_code' => (float) $material->quantity - $totalQuantity <= 0 ? 'pronto_per_vendita' : $material->status_code,
            ])->save();

            return $generatedUnits;
        });
    }

    private function buildPackageCode(string $outputCode, string $packageTypeCode, int $sequence): string
    {
        $typeSegment = Str::of($packageTypeCode)->replace('_', '-')->upper()->value();

        return sprintf('%s-%s-%02d', $outputCode, $typeSegment, $sequence);
    }
}
