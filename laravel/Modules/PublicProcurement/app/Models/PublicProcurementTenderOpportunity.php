<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Customer\Models\Customer;

final class PublicProcurementTenderOpportunity extends Model
{
    use SoftDeletes;

    protected $table = 'public_procurement_tender_opportunities';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'estimated_amount' => 'decimal:2',
            'published_at' => 'datetime',
            'submission_deadline_at' => 'datetime',
            'site_visit_due_at' => 'datetime',
            'keywords' => 'array',
            'ai_summary' => 'array',
            'deleted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<PublicProcurementTenderSource, $this> */
    public function source(): BelongsTo
    {
        return $this->belongsTo(PublicProcurementTenderSource::class, 'tender_source_id');
    }

    /** @return BelongsTo<Customer, $this> */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /** @return HasMany<PublicProcurementTenderEligibilityCheck, $this> */
    public function eligibilityChecks(): HasMany
    {
        return $this->hasMany(PublicProcurementTenderEligibilityCheck::class, 'tender_opportunity_id');
    }

    /** @return HasOne<PublicProcurementContract, $this> */
    public function contract(): HasOne
    {
        return $this->hasOne(PublicProcurementContract::class, 'tender_opportunity_id');
    }
}
