<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberStockMovementResource\Pages;

use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\Timber\Actions\RegisterTimberStockMovementAction;
use Modules\Timber\Filament\Resources\TimberStockMovementResource;
use Modules\Timber\Models\TimberCustomer;
use Modules\Timber\Models\TimberProduct;
use Safe\DateTimeImmutable;

final class CreateTimberStockMovement extends XotBaseCreateRecord
{
    protected static string $resource = TimberStockMovementResource::class;

    /**
     * Route creation through the domain action so stock and product costs stay consistent.
     *
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordCreation(array $data): Model
    {
        $productId = is_numeric($data['product_id'] ?? null) ? (int) $data['product_id'] : 0;
        $product = TimberProduct::query()->findOrFail($productId);

        $customerId = $data['customer_id'] ?? null;
        $customer = is_numeric($customerId) ? TimberCustomer::query()->find($customerId) : null;

        $movementDateValue = $data['movement_date'] ?? null;
        $movementDate = is_string($movementDateValue) && $movementDateValue !== ''
            ? new DateTimeImmutable($movementDateValue)
            : null;

        $unitCost = $data['unit_cost'] ?? null;

        return app(RegisterTimberStockMovementAction::class)->execute(
            product: $product,
            directionCode: self::asString($data['direction_code'] ?? null) ?? '',
            quantity: is_numeric($data['quantity'] ?? null) ? (float) $data['quantity'] : 0.0,
            unitOfMeasure: self::asString($data['unit_of_measure'] ?? null) ?? '',
            userId: (int) Auth::id(),
            customer: $customer,
            documentTypeCode: self::asString($data['document_type_code'] ?? null),
            documentReference: self::asString($data['document_reference'] ?? null),
            lotCode: self::asString($data['lot_code'] ?? null),
            movementDate: $movementDate,
            unitCost: is_numeric($unitCost) ? (float) $unitCost : null,
            notes: self::asString($data['notes'] ?? null),
        );
    }

    private static function asString(mixed $value): ?string
    {
        return is_string($value) ? $value : null;
    }
}
