<?php

declare(strict_types=1);

namespace Modules\Intervention\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Intervention\Database\Factories\InterventionMaterialLineFactory;
use Modules\Quotation\Models\QuotationItem;

/**
 * @property int $id
 * @property int $intervention_id
 * @property int|null $quotation_item_id
 * @property string $description
 * @property numeric-string|float $quantity
 * @property string $unit
 * @property numeric-string|float $unit_price
 * @property numeric-string|float $line_total
 * @property numeric-string|float $extra_quantity
 * @property numeric-string|float $extra_amount
 * @property string $extra_status_code
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Intervention|null $intervention
 * @property-read QuotationItem|null $quotationItem
 *
 * @method static InterventionMaterialLineFactory factory($count = null, $state = [])
 */
final class InterventionMaterialLine extends BaseModel
{
    /** @use HasFactory<InterventionMaterialLineFactory> */
    use HasFactory;

    protected $table = 'intervention_material_lines';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'unit_price' => 'decimal:2',
            'line_total' => 'decimal:2',
            'extra_quantity' => 'decimal:2',
            'extra_amount' => 'decimal:2',
        ];
    }

    /** @return BelongsTo<Intervention, $this> */
    public function intervention(): BelongsTo
    {
        return $this->belongsTo(Intervention::class, 'intervention_id');
    }

    /** @return BelongsTo<QuotationItem, $this> */
    public function quotationItem(): BelongsTo
    {
        return $this->belongsTo(QuotationItem::class, 'quotation_item_id');
    }

    /**
     * Riga con materiale fuori preventivo: e' quella su cui si decide se
     * fatturare o regalare.
     */
    public function isExtra(): bool
    {
        return (float) $this->extra_quantity > 0.0;
    }
}
