<?php

declare(strict_types=1);

namespace Modules\Intervention\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Quotation\Models\QuotationItem;

final class InterventionMaterialLine extends Model
{
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
