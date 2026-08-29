<?php

declare(strict_types=1);

namespace Modules\Timber\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Customer\Models\Customer;
use Modules\Timber\Database\Factories\TimberPaymentInstallmentFactory;

/**
 * @property int $id
 * @property int|null $invoice_id
 * @property int|null $customer_id
 * @property string $direction
 * @property Carbon $due_date
 * @property string $amount
 * @property string|null $payment_method_code
 * @property string $status_code
 * @property Carbon|null $paid_at
 * @property Carbon|null $reminded_at
 * @property string|null $notes
 */
final class TimberPaymentInstallment extends BaseModel
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

    /** @return BelongsTo<Customer, $this> */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
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
