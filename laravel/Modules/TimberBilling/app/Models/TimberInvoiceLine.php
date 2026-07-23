<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Models;

use App\Timber\Models\TimberProduct;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\TimberBilling\Database\Factories\TimberInvoiceLineFactory;

final class TimberInvoiceLine extends Model
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

    /** @return BelongsTo<TimberProduct, $this> */
    public function product(): BelongsTo
    {
        return $this->belongsTo(TimberProduct::class, 'product_id');
    }

    protected static function newFactory(): TimberInvoiceLineFactory
    {
        return TimberInvoiceLineFactory::new();
    }
}
