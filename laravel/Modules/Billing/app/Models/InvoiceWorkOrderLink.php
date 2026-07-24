<?php

declare(strict_types=1);

namespace Modules\Billing\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Xot\Models\XotBaseModel;

/**
 * @property int $id
 * @property int $invoice_id
 * @property int $work_order_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class InvoiceWorkOrderLink extends XotBaseModel
{
    protected $table = 'invoice_work_order_links';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return BelongsTo<Invoice, $this> */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
