<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Billing\Models\Invoice;
use Modules\PublicProcurement\Database\Factories\PublicProcurementPaymentCertificateFactory;
use Modules\Xot\Models\Traits\HasXotFactory;
use Modules\Xot\Models\XotBaseModel;

final class PublicProcurementPaymentCertificate extends XotBaseModel
{
    /**
 * @method static PublicProcurementPaymentCertificateFactory factory($count = null, $state = [])
 */
    use HasXotFactory;

    protected static function newFactory(): PublicProcurementPaymentCertificateFactory
    {
        return PublicProcurementPaymentCertificateFactory::new();
    }

    use SoftDeletes;

    protected $table = 'public_procurement_payment_certificates';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'issued_on' => 'date',
            'amount' => 'decimal:2',
            'deleted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<PublicProcurementContract, $this> */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(PublicProcurementContract::class, 'public_procurement_contract_id');
    }

    /** @return BelongsTo<PublicProcurementProgressReport, $this> */
    public function progressReport(): BelongsTo
    {
        return $this->belongsTo(PublicProcurementProgressReport::class, 'progress_report_id');
    }

    /** @return BelongsTo<Invoice, $this> */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
