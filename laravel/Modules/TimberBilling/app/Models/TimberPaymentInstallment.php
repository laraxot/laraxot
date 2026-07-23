<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Models;

use App\Timber\Models\TimberCustomer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\TimberBilling\Database\Factories\TimberPaymentInstallmentFactory;

final class TimberPaymentInstallment extends Model
{
    /** @use HasFactory<TimberPaymentInstallmentFactory> */
    use HasFactory;

    protected $table = 'timber_payment_installments';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'reminded_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<TimberInvoice, $this> */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(TimberInvoice::class, 'invoice_id');
    }

    /** @return BelongsTo<TimberCustomer, $this> */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(TimberCustomer::class, 'customer_id');
    }

    public function isSettled(): bool
    {
        return $this->status_code === 'saldato';
    }

    protected static function newFactory(): TimberPaymentInstallmentFactory
    {
        return TimberPaymentInstallmentFactory::new();
    }
}
