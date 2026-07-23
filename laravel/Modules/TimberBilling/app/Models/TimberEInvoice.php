<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\TimberBilling\Database\Factories\TimberEInvoiceFactory;

final class TimberEInvoice extends Model
{
    /** @use HasFactory<TimberEInvoiceFactory> */
    use HasFactory;

    protected $table = 'timber_e_invoices';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'transmitted_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<TimberInvoice, $this> */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(TimberInvoice::class, 'invoice_id');
    }

    protected static function newFactory(): TimberEInvoiceFactory
    {
        return TimberEInvoiceFactory::new();
    }
}
