<?php

declare(strict_types=1);

namespace Modules\Timber\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\Timber\Database\Factories\TimberEInvoiceFactory;

/**
 * @property int $id
 * @property int $invoice_id
 * @property string $direction
 * @property string $sdi_status
 * @property string|null $sdi_identifier
 * @property string|null $file_name
 * @property string|null $xml_content
 * @property Carbon|null $transmitted_at
 * @property Carbon|null $delivered_at
 * @property string|null $error_message
 */
final class TimberEInvoice extends BaseModel
{

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
