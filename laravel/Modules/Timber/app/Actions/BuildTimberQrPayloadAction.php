<?php

declare(strict_types=1);

namespace Modules\Timber\Actions;

use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Modules\Timber\Models\TimberDerivedMaterial;
use Modules\Timber\Models\TimberLot;
use Modules\Timber\Models\TimberPackagedUnit;

final class BuildTimberQrPayloadAction
{
    public function execute(Model $labelable): string
    {
        return match (true) {
            $labelable instanceof TimberLot => implode('|', array_values(array_filter([
                'TMB',
                'LOTTO='.$labelable->lot_code,
                'SPECIE='.$labelable->wood_species,
                ...self::lotOriginSegments($labelable),
                'DATA='.$labelable->created_at?->toDateString(),
            ], static fn (?string $segment): bool => $segment !== null && $segment !== ''))),
            $labelable instanceof TimberDerivedMaterial => implode('|', [
                'TMB',
                'LOTTO='.($labelable->traceable_code !== null ? $labelable->traceable_code : $labelable->output_code),
                'MATERIALE='.$labelable->output_code,
                'SPECIE='.$labelable->wood_species,
                'DATA='.$labelable->created_at?->toDateString(),
            ]),
            $labelable instanceof TimberPackagedUnit => implode('|', array_values(array_filter([
                'TMB',
                $labelable->traceable_code !== null ? 'LOTTO='.$labelable->traceable_code : null,
                'CONFEZIONE='.$labelable->package_code,
                'TIPO='.$labelable->package_type_code,
                $labelable->article_code !== null ? 'ARTICOLO='.$labelable->article_code : null,
                'DATA='.$labelable->created_at?->toDateString(),
            ], static fn (?string $segment): bool => $segment !== null))),
            default => throw new InvalidArgumentException('Tipo di materiale non supportato per la generazione QR.'),
        };
    }

    /**
     * Supplier and DDT segments for an incoming lot label, when available.
     *
     * @return array<int, string|null>
     */
    private static function lotOriginSegments(TimberLot $lot): array
    {
        $purchaseRecord = $lot->purchaseRecord;
        $supplier = $purchaseRecord !== null ? $purchaseRecord->supplier : null;
        $supplierName = $supplier !== null ? $supplier->legal_name : null;
        $ddtNumber = $purchaseRecord !== null ? $purchaseRecord->ddt_number : null;

        return [
            $supplierName !== null && $supplierName !== '' ? 'FORNITORE='.$supplierName : null,
            $ddtNumber !== null && $ddtNumber !== '' ? 'DDT='.$ddtNumber : null,
        ];
    }
}
