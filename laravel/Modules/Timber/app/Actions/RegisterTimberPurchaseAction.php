<?php

declare(strict_types=1);

namespace Modules\Timber\Actions;

use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Modules\Timber\Models\TimberLot;
use Modules\Timber\Models\TimberPurchaseRecord;
use Modules\Timber\Models\TimberSupplier;
use Modules\Timber\Models\TimberTransporter;
use Modules\Timber\Support\WoodSpeciesCatalog;

final class RegisterTimberPurchaseAction
{
    public function execute(
        TimberSupplier $supplier,
        ?TimberTransporter $transporter,
        ?string $originForest,
        ?string $ddtNumber,
        ?string $ddtDate,
        string $woodSpecies,
        float $quantity,
        string $unitOfMeasure,
        ?float $grossWeight,
        ?float $netWeight,
        ?float $unitPurchasePrice,
        ?string $certifications,
        ?string $photoPath,
        ?string $notes,
        ?int $userId,
        ?string $purchasePriceUnit = null,
    ): TimberPurchaseRecord {
        $normalizedSpecies = trim($woodSpecies);
        if ($normalizedSpecies === '' || ! in_array($normalizedSpecies, WoodSpeciesCatalog::validCodes(), true)) {
            throw new InvalidArgumentException('La specie legnosa non e valida.');
        }

        $normalizedUnit = trim($unitOfMeasure);
        $configuredUnits = config('timber.timber.units_of_measure', []);
        $allowedUnits = is_array($configuredUnits) ? array_keys($configuredUnits) : [];
        if ($normalizedUnit === '' || ! in_array($normalizedUnit, $allowedUnits, true)) {
            throw new InvalidArgumentException('L unita di misura non e valida.');
        }

        if ($quantity <= 0) {
            throw new InvalidArgumentException('La quantita deve essere positiva.');
        }

        if ($grossWeight !== null && $grossWeight < 0) {
            throw new InvalidArgumentException('Il peso lordo non puo essere negativo.');
        }

        if ($netWeight !== null && $netWeight < 0) {
            throw new InvalidArgumentException('Il peso netto non puo essere negativo.');
        }

        if ($unitPurchasePrice !== null && $unitPurchasePrice < 0) {
            throw new InvalidArgumentException('Il prezzo di acquisto non puo essere negativo.');
        }

        $configuredPriceUnits = config('timber.timber.purchase_price_units', []);
        $allowedPriceUnits = is_array($configuredPriceUnits) ? array_keys($configuredPriceUnits) : [];
        $normalizedPriceUnit = trim((string) $purchasePriceUnit);
        if ($normalizedPriceUnit === '' || ! in_array($normalizedPriceUnit, $allowedPriceUnits, true)) {
            $normalizedPriceUnit = 'quintale';
        }

        return DB::transaction(function () use (
            $supplier,
            $transporter,
            $normalizedPriceUnit,
            $originForest,
            $ddtNumber,
            $ddtDate,
            $normalizedSpecies,
            $quantity,
            $normalizedUnit,
            $grossWeight,
            $netWeight,
            $unitPurchasePrice,
            $certifications,
            $photoPath,
            $notes,
            $userId,
        ): TimberPurchaseRecord {
            /** @var TimberPurchaseRecord $record */
            $record = TimberPurchaseRecord::query()->create([
                'supplier_id' => $supplier->id,
                'transporter_id' => $transporter?->id,
                'origin_forest' => trim((string) $originForest) !== '' ? trim((string) $originForest) : null,
                'ddt_number' => trim((string) $ddtNumber) !== '' ? trim((string) $ddtNumber) : null,
                'ddt_date' => $ddtDate,
                'wood_species' => $normalizedSpecies,
                'quantity' => $quantity,
                'unit_of_measure' => $normalizedUnit,
                'gross_weight' => $grossWeight,
                'net_weight' => $netWeight,
                'unit_purchase_price' => $unitPurchasePrice,
                'purchase_price_unit' => $normalizedPriceUnit,
                'certifications' => trim((string) $certifications) !== '' ? trim((string) $certifications) : null,
                'photo_path' => $photoPath,
                'notes' => trim((string) $notes) !== '' ? trim((string) $notes) : null,
                'status_code' => 'registered',
                'created_by_user_id' => $userId,
                'received_at' => now(),
            ]);

            $record->lot()->create([
                'lot_code' => $this->nextLotCode(),
                'status_code' => 'available',
                'current_area_code' => 'piazzale',
                'quantity_available' => $quantity,
                'unit_of_measure' => $normalizedUnit,
                'wood_species' => $normalizedSpecies,
            ]);

            return $record->load('lot');
        });
    }

    private function nextLotCode(): string
    {
        $sequence = TimberLot::query()->count() + 1;

        do {
            $lotCode = sprintf('LOT-%s-%04d', now()->format('Y'), $sequence);
            $sequence++;
        } while (TimberLot::query()->where('lot_code', $lotCode)->exists());

        return $lotCode;
    }
}
