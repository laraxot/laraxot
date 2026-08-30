<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Catalog\Models\Product;

/**
 * Una riga della distinta materiali di una commessa.
 *
 * Testo libero + link opzionale al prodotto: metà di ciò che un'officina mette
 * su una commessa non esiste a catalogo, e forzarlo riporterebbe la gente ai
 * fogli di calcolo.
 *
 * @property int $id
 * @property int $work_order_id
 * @property int|null $product_id
 * @property string $description
 * @property string|null $unit_code
 * @property numeric-string $quantity
 * @property numeric-string|null $unit_cost
 * @property numeric-string|null $line_total
 * @property string $source_code
 * @property string|null $notes
 * @property int $sort_order
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read WorkOrder|null $workOrder
 * @property-read Product|null $product
 */
final class WorkOrderMaterial extends BaseModel
{
    protected $table = 'work_order_materials';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:3',
            'unit_cost' => 'decimal:4',
            'line_total' => 'decimal:2',
            'sort_order' => 'integer',
        ];
    }

    /** @return BelongsTo<WorkOrder, $this> */
    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class, 'work_order_id');
    }

    /** @return BelongsTo<Product, $this> */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
