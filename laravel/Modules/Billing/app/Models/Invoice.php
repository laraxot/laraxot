<?php

declare(strict_types=1);

namespace Modules\Billing\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Invoice extends Model
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

