<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Customer\Models\Customer;
use Modules\Xot\Models\XotBaseModel;

/**
 * @property int $id
 * @property int|null $tender_source_id
 * @property int|null $customer_id
 * @property string $status
 * @property string|null $external_id
 * @property string|null $source_url
 * @property string $contracting_authority
 * @property string $title
 * @property string|null $cig
 * @property string|null $cup
 * @property string|null $cpv_code
 * @property string|null $category
 * @property string|null $territory
 * @property string|null $estimated_amount
 * @property Carbon|null $published_at
 * @property Carbon|null $submission_deadline_at
 * @property Carbon|null $site_visit_due_at
 * @property array<int, string>|null $keywords
 * @property array<string, mixed>|null $ai_summary
 * @property string|null $raw_excerpt
 * @property string|null $notes
 * @property Carbon|null $deleted_at
 */
final class PublicProcurementTenderOpportunity extends XotBaseModel
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
