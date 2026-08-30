<?php

declare(strict_types=1);

namespace Modules\MetalWork\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\MetalWork\Database\Factories\MetalSupplierPriceFactory;
use Modules\Xot\Models\XotBaseModel;

/**
 * @property int $id
 * @property int $raw_material_id
 * @property string $supplier_name
 * @property string|null $invoice_number
 * @property string|null $delivery_note_number
 * @property Carbon $recorded_on
 * @property numeric-string $quantity
 * @property string $unit_code
 * @property numeric-string $unit_price
 * @property numeric-string $line_total
 * @property string|null $source_file
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class MetalSupplierPrice extends XotBaseModel
{
    /** @use HasFactory<MetalSupplierPriceFactory> */
    use HasFactory;

    protected $table = 'metalwork_supplier_prices';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'recorded_on' => 'date',
            'quantity' => 'decimal:3',
            'unit_price' => 'decimal:4',
            'line_total' => 'decimal:2',
        ];
    }

    /** @return BelongsTo<MetalRawMaterial, $this> */
    public function rawMaterial(): BelongsTo
    {
        return $this->belongsTo(MetalRawMaterial::class, 'raw_material_id');
    }

    protected static function newFactory(): MetalSupplierPriceFactory
    {
        return MetalSupplierPriceFactory::new();
    }
}
