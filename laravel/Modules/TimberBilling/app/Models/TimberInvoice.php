<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Customer\Models\Customer;
use Modules\TimberBilling\Database\Factories\TimberInvoiceFactory;
use Modules\User\Models\User;

/**
 * @property int $id
 * @property string $kind
 * @property string|null $number
 * @property Carbon $invoice_date
 * @property int|null $customer_id
 * @property int|null $source_document_id
 * @property string $status_code
 * @property string|null $payment_method_code
 * @property string|null $payment_terms
 * @property string $taxable_amount
 * @property string $vat_amount
 * @property string $total_amount
 * @property string|null $notes
 * @property Carbon|null $issued_at
 * @property Carbon|null $stock_posted_at
 * @property int|null $created_by_user_id
 */
final class TimberInvoice extends BaseModel
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

    /** @return BelongsTo<Customer, $this> */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
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
