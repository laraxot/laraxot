<?php

declare(strict_types=1);

namespace Modules\Production\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Modules\Catalog\Models\Product;
use Modules\Production\Database\Factories\ProductionOrderFactory;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Models\XotBaseModel;

/**
 * @property int $id
 * @property string|null $code
 * @property int $product_id
 * @property int $quantity
 * @property string $status
 * @property int|null $work_order_id
 * @property string|null $notes
 * @property Carbon|null $due_at
 *
 * @method static ProductionOrderFactory factory($count = null, $state = [])
 */
final class ProductionOrder extends XotBaseModel
{
    /** @use HasFactory<ProductionOrderFactory> */
    use HasFactory;

    protected $table = 'production_orders';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'due_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<Product, $this> */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /** @return BelongsTo<WorkOrder, $this> */
    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class, 'work_order_id');
    }

    /** @return HasMany<ProductionPhase, $this> */
    public function phases(): HasMany
    {
        return $this->hasMany(ProductionPhase::class, 'production_order_id');
    }
}
