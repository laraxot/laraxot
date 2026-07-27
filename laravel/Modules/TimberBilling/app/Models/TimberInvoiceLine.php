<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Catalog\Models\Product;
use Modules\TimberBilling\Database\Factories\TimberInvoiceLineFactory;

/**
 * @property int $id
 * @property int $invoice_id
 * @property int|null $product_id
 * @property string $description
 * @property string $quantity
 * @property string $unit_of_measure
 * @property string $unit_price
 * @property string $discount_percent
 * @property string $vat_rate
 * @property string|null $lot_code
 * @property string $line_total
 * @property int $position
 */
final class TimberInvoiceLine extends BaseModel
{
    /** @use HasFactory<TimberInvoiceLineFactory> */
    use HasFactory;

    protected $table = 'timber_invoice_lines';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:3',
            'unit_price' => 'decimal:4',
            'discount_percent' => 'decimal:2',
            'vat_rate' => 'decimal:2',
            'line_total' => 'decimal:2',
        ];
    }

    /** @return BelongsTo<TimberInvoice, $this> */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(TimberInvoice::class, 'invoice_id');
    }

    /** @return BelongsTo<Product, $this> */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    protected static function newFactory(): TimberInvoiceLineFactory
    {
        return TimberInvoiceLineFactory::new();
    }
}
