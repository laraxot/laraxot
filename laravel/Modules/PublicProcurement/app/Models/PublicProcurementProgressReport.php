<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\PublicProcurement\Database\Factories\PublicProcurementProgressReportFactory;

final class PublicProcurementProgressReport extends BaseModel
{
    /**
 * @method static PublicProcurementProgressReportFactory factory($count = null, $state = [])
 */

    protected static function newFactory(): PublicProcurementProgressReportFactory
    {
        return PublicProcurementProgressReportFactory::new();
    }

    use SoftDeletes;

    protected $table = 'public_procurement_progress_reports';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'issued_on' => 'date',
            'period_from' => 'date',
            'period_to' => 'date',
            'work_amount' => 'decimal:2',
            'security_amount' => 'decimal:2',
            'retention_amount' => 'decimal:2',
            'certificate_amount' => 'decimal:2',
            'progress_percentage' => 'decimal:2',
            'deleted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<PublicProcurementContract, $this> */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(PublicProcurementContract::class, 'public_procurement_contract_id');
    }

    /** @return HasOne<PublicProcurementPaymentCertificate, $this> */
    public function paymentCertificate(): HasOne
    {
        return $this->hasOne(PublicProcurementPaymentCertificate::class, 'progress_report_id');
    }
}
