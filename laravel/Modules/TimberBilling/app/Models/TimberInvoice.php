<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Models;

use App\Models\User;
use App\Timber\Models\TimberCustomer;
use App\Timber\Models\TimberDocument;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\TimberBilling\Database\Factories\TimberInvoiceFactory;

final class TimberInvoice extends Model
{
    /** @use HasFactory<TimberInvoiceFactory> */
    use HasFactory;

    use SoftDeletes;

    protected $table = 'timber_invoices';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'invoice_date' => 'date',
            'taxable_amount' => 'decimal:2',
            'vat_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'issued_at' => 'datetime',
            'stock_posted_at' => 'datetime',
        ];
    }

    /** @return HasMany<TimberInvoiceLine, $this> */
    public function lines(): HasMany
    {
        return $this->hasMany(TimberInvoiceLine::class, 'invoice_id');
    }

    /** @return BelongsTo<TimberCustomer, $this> */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(TimberCustomer::class, 'customer_id');
    }

    /** @return BelongsTo<TimberDocument, $this> */
    public function sourceDocument(): BelongsTo
    {
        return $this->belongsTo(TimberDocument::class, 'source_document_id');
    }

    /** @return BelongsTo<User, $this> */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function hasStockImpact(): bool
    {
        $map = config('timberbilling.invoice_stock_directions', []);

        return is_array($map) && array_key_exists($this->kind, $map);
    }

    protected static function newFactory(): TimberInvoiceFactory
    {
        return TimberInvoiceFactory::new();
    }
}
