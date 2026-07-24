<?php

declare(strict_types=1);

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Inventory\Database\Factories\WarehouseLocationFactory;
use Modules\Xot\Models\XotBaseModel;

/**
 * @property int $id
 * @property int $warehouse_id
 * @property string $code
 * @property string|null $label
 * @property bool $is_active
 * @property-read Warehouse|null $warehouse
 */
final class WarehouseLocation extends XotBaseModel
{
    /** @use HasFactory<WarehouseLocationFactory> */
    use HasFactory;

    protected $table = 'warehouse_locations';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'meta' => 'array',
        ];
    }

    /** @return BelongsTo<Warehouse, $this> */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /** @return HasMany<InventoryMovement, $this> */
    public function outgoingMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class, 'from_location_id');
    }

    /** @return HasMany<InventoryMovement, $this> */
    public function incomingMovements(): HasMany
    {
        return $this->hasMany(InventoryMovement::class, 'to_location_id');
    }

    /** @return HasMany<ProductUnit, $this> */
    public function productUnits(): HasMany
    {
        return $this->hasMany(ProductUnit::class, 'current_location_id');
    }
}
