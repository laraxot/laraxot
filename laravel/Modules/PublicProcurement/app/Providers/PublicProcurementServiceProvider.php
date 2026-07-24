<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Providers;

use Illuminate\Support\Facades\Gate;
use Modules\PublicProcurement\Models\PublicProcurementAiExtraction;
use Modules\PublicProcurement\Models\PublicProcurementContact;
use Modules\PublicProcurement\Models\PublicProcurementContract;
use Modules\PublicProcurement\Models\PublicProcurementDeadline;
use Modules\PublicProcurement\Models\PublicProcurementDocumentRequirement;
use Modules\PublicProcurement\Models\PublicProcurementEconomicFrame;
use Modules\PublicProcurement\Models\PublicProcurementGuarantee;
use Modules\PublicProcurement\Models\PublicProcurementMeasurement;
use Modules\PublicProcurement\Models\PublicProcurementOfficialRecord;
use Modules\PublicProcurement\Models\PublicProcurementPaymentCertificate;
use Modules\PublicProcurement\Models\PublicProcurementPriceItem;
use Modules\PublicProcurement\Models\PublicProcurementProgressReport;
use Modules\PublicProcurement\Models\PublicProcurementTenderEligibilityCheck;
use Modules\PublicProcurement\Models\PublicProcurementTenderOpportunity;
use Modules\PublicProcurement\Models\PublicProcurementTenderSource;
use Modules\PublicProcurement\Policies\PublicProcurementPolicy;
use Modules\Xot\Providers\XotBaseServiceProvider;

class PublicProcurementServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'PublicProcurement';

    protected string $module_dir = __DIR__;

    protected string $module_ns = __NAMESPACE__;

    public function boot(): void
    {
        parent::boot();

        foreach ([
            PublicProcurementAiExtraction::class,
            PublicProcurementContact::class,
            PublicProcurementContract::class,
            PublicProcurementDeadline::class,
            PublicProcurementDocumentRequirement::class,
            PublicProcurementEconomicFrame::class,
            PublicProcurementGuarantee::class,
            PublicProcurementMeasurement::class,
            PublicProcurementOfficialRecord::class,
            PublicProcurementPaymentCertificate::class,
            PublicProcurementPriceItem::class,
            PublicProcurementProgressReport::class,
            PublicProcurementTenderEligibilityCheck::class,
            PublicProcurementTenderOpportunity::class,
            PublicProcurementTenderSource::class,
        ] as $model) {
            Gate::policy($model, PublicProcurementPolicy::class);
        }
    }
}
