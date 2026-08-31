<?php

declare(strict_types=1);

namespace Modules\Inventory\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Inventory\Database\Factories\WarehouseFactory;
use Modules\Vehicle\Models\Vehicle;
use Modules\Xot\Models\XotBaseModel;

/**
 * @property int $id
 * @property string $name
 * @property string $code
 * @property bool $is_active
 * @property bool $is_final_destination
 */
final class Warehouse extends XotBaseModel
{

    protected $table = 'warehouses';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_final_destination' => 'boolean',
        ];
    }

    /** @return BelongsTo<Vehicle, $this> */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /** @return HasMany<WarehouseLocation, $this> */
    public function locations(): HasMany
    {
        return $this->hasMany(WarehouseLocation::class);
    }
}
