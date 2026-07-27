<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\TimberBilling\Database\Factories\TimberStockMovementFactory;
use Modules\User\Models\User;

/**
 * @property Carbon $movement_date
 * @property int $product_id
 * @property string $direction_code
 * @property string $quantity
 * @property string $unit_of_measure
 * @property int|null $customer_id
 * @property string|null $document_type_code
 * @property string|null $document_reference
 * @property string|null $lot_code
 * @property string|null $category
 * @property string|null $unit_cost
 * @property string|null $notes
 * @property int|null $created_by_user_id
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class TimberStockMovement extends BaseModel
{
    /** @use HasFactory<TimberStockMovementFactory> */
    use HasFactory;

    protected $table = 'timber_stock_movements';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'movement_date' => 'date',
            'quantity' => 'decimal:3',
            'unit_cost' => 'decimal:4',
        ];
    }

    /** @return BelongsTo<TimberProduct, $this> */
    public function product(): BelongsTo
    {
        return $this->belongsTo(TimberProduct::class, 'product_id');
    }

    /** @return BelongsTo<TimberCustomer, $this> */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(TimberCustomer::class, 'customer_id');
    }

    /** @return BelongsTo<User, $this> */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Signed contribution of this movement to on-hand stock.
     */
    public function signedQuantity(): float
    {
        $quantity = (float) $this->quantity;

        return $this->direction_code === 'unload' ? -abs($quantity) : $quantity;
    }

    protected static function newFactory(): TimberStockMovementFactory
    {
        return TimberStockMovementFactory::new();
    }
}
