<?php

declare(strict_types=1);

namespace Modules\Billing\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Xot\Models\XotBaseModel;

/** Fattura ricevuta dal fornitore (ciclo passivo). */
/**
 * @property int $id
 * @property int $supplier_id
 * @property string $number
 * @property Carbon|null $issued_on
 * @property string $total
 * @property string $status_code
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
final class SupplierInvoice extends XotBaseModel
{
    use SoftDeletes;

    protected $table = 'supplier_invoices';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return ['issued_on' => 'date', 'total' => 'decimal:2'];
    }

    /** @return BelongsTo<Supplier, $this> */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    /** @return HasMany<SupplierInvoiceLine, $this> */
    public function lines(): HasMany
    {
        return $this->hasMany(SupplierInvoiceLine::class, 'supplier_invoice_id');
    }
}
