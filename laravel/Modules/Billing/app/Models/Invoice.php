<?php

declare(strict_types=1);

namespace Modules\Billing\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Xot\Models\XotBaseModel;

/**
 * @property int $id
 * @property int $customer_id
 * @property int|null $work_order_id
 * @property string|null $number
 * @property Carbon|null $issued_on
 * @property string $total
 * @property string|null $notes
 * @property string|null $electronic_invoice_provider
 * @property string|null $electronic_invoice_external_id
 * @property Carbon|null $electronic_invoice_sent_at
 * @property string|null $electronic_invoice_last_error
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 *
 * @method static \Modules\Billing\Database\Factories\InvoiceFactory factory($count = null, $state = [])
 */
final class Invoice extends XotBaseModel
{
    use SoftDeletes;

    protected $table = 'invoices';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'issued_on' => 'date',
            'total' => 'decimal:2',
            'electronic_invoice_sent_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /** @return HasMany<InvoiceWorkOrderLink, $this> */
    public function workOrderLinks(): HasMany
    {
        return $this->hasMany(InvoiceWorkOrderLink::class);
    }
}
