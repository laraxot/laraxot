<?php

declare(strict_types=1);

namespace Modules\Timber\Actions;

use DateTimeInterface;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Modules\Timber\Models\TimberCustomer;
use Modules\Timber\Models\TimberProduct;
use Modules\Timber\Models\TimberStockMovement;
use Modules\Timber\Queries\GetTimberStockMovementsQuery;

final class RegisterTimberStockMovementAction
{
    public function __construct(
        private readonly GetTimberStockMovementsQuery $stockMovements,
    ) {}

    public function execute(
        TimberProduct $product,
        string $directionCode,
        float $quantity,
        string $unitOfMeasure,
        ?int $userId = null,
        ?TimberCustomer $customer = null,
        ?string $documentTypeCode = null,
        ?string $documentReference = null,
        ?string $lotCode = null,
        ?DateTimeInterface $movementDate = null,
        ?float $unitCost = null,
        ?string $notes = null,
    ): TimberStockMovement {
        $normalizedDirection = trim($directionCode);
        $configuredDirections = config('timber.timber.stock_movement_directions', []);
        $allowedDirections = is_array($configuredDirections) ? array_keys($configuredDirections) : [];
        if (! in_array($normalizedDirection, $allowedDirections, true)) {
            throw new InvalidArgumentException('Il tipo di movimento non e valido.');
        }

        if (abs($quantity) < 0.0001) {
            throw new InvalidArgumentException('La quantita del movimento non puo essere nulla.');
        }

        if ($normalizedDirection !== 'adjustment' && $quantity <= 0) {
            throw new InvalidArgumentException('La quantita deve essere positiva.');
        }

        $normalizedUnit = trim($unitOfMeasure);
        if ($normalizedUnit === '') {
            throw new InvalidArgumentException('L unita di misura e obbligatoria.');
        }

        if ($unitCost !== null && $unitCost < 0) {
            throw new InvalidArgumentException('Il costo unitario non puo essere negativo.');
        }

        return DB::transaction(function () use (
            $product,
            $normalizedDirection,
            $quantity,
            $normalizedUnit,
            $userId,
            $customer,
            $documentTypeCode,
            $documentReference,
            $lotCode,
            $movementDate,
            $unitCost,
            $notes,
        ): TimberStockMovement {
            /** @var TimberStockMovement $movement */
            $movement = TimberStockMovement::query()->create([
                'movement_date' => $movementDate !== null
                    ? $movementDate->format('Y-m-d')
                    : now()->toDateString(),
                'product_id' => $product->getKey(),
                'direction_code' => $normalizedDirection,
                'quantity' => $quantity,
                'unit_of_measure' => $normalizedUnit,
                'customer_id' => $customer?->getKey(),
                'document_type_code' => $this->nullableString($documentTypeCode),
                'document_reference' => $this->nullableString($documentReference),
                'lot_code' => $this->nullableString($lotCode),
                'category' => $product->category,
                'unit_cost' => $unitCost,
                'notes' => $this->nullableString($notes),
                'created_by_user_id' => $userId,
            ]);

            if ($normalizedDirection === 'load' && $unitCost !== null) {
                $this->updateProductCosts($product, $quantity, $unitCost, (int) $movement->id);
            }

            return $movement;
        });
    }

    private function updateProductCosts(TimberProduct $product, float $quantity, float $unitCost, int $currentMovementId): void
    {
        $previousStock = $this->stockMovements->stockForProduct((int) $product->id, $currentMovementId);
        $previousAverage = $product->average_cost !== null ? (float) $product->average_cost : $unitCost;

        $baseStock = max($previousStock, 0.0);
        $totalQuantity = $baseStock + $quantity;

        $weightedAverage = $totalQuantity > 0
            ? (($baseStock * $previousAverage) + ($quantity * $unitCost)) / $totalQuantity
            : $unitCost;

        $product->forceFill([
            'last_cost' => $unitCost,
            'average_cost' => round($weightedAverage, 4),
        ])->save();
    }

    private function nullableString(?string $value): ?string
    {
        $trimmed = trim((string) $value);

        return $trimmed !== '' ? $trimmed : null;
    }
}
