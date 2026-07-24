<?php

declare(strict_types=1);

namespace Modules\Billing\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Xot\Models\XotBaseModel;

/**
 * @property int $id
 * @property int $supplier_invoice_id
 * @property int|null $delivery_note_line_id
 * @property string $description
 * @property string $quantity
 * @property string $unit_price
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class SupplierInvoiceLine extends XotBaseModel
{
    protected $table = 'supplier_invoice_lines';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return ['quantity' => 'decimal:2', 'unit_price' => 'decimal:2'];
    }

    /** @return BelongsTo<SupplierInvoice, $this> */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(SupplierInvoice::class, 'supplier_invoice_id');
    }

    /** @return BelongsTo<DeliveryNoteLine, $this> */
    public function deliveryNoteLine(): BelongsTo
    {
        return $this->belongsTo(DeliveryNoteLine::class, 'delivery_note_line_id');
    }
}
