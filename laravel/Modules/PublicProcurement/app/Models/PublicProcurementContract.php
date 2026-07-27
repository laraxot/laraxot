<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Customer\Models\Customer;
use Modules\PublicProcurement\Database\Factories\PublicProcurementContractFactory;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Models\Traits\HasXotFactory;
use Modules\Xot\Models\XotBaseModel;

/**
 * @property int $id
 * @property int|null $work_order_id
 * @property int|null $tender_opportunity_id
 * @property int|null $customer_id
 * @property string $status
 * @property string|null $code
 * @property string $title
 * @property string $contracting_authority
 * @property string|null $procedure_type
 * @property string|null $cig
 * @property string|null $cup
 * @property string|null $rup_name
 * @property string|null $works_director_name
 * @property string|null $safety_coordinator_name
 * @property Carbon|null $awarded_on
 * @property Carbon|null $signed_on
 * @property Carbon|null $site_handover_on
 * @property Carbon|null $works_start_on
 * @property Carbon|null $works_due_on
 * @property Carbon|null $works_completed_on
 * @property string $contract_amount
 * @property string $security_costs
 * @property string $vat_rate
 * @property string $discount_rate
 * @property string $retention_rate
 * @property array<string, mixed>|null $meta
 * @property string|null $description
 * @property string|null $notes
 * @property Carbon|null $deleted_at
 *
 * @method static PublicProcurementContractFactory factory($count = null, $state = [])
 */
final class PublicProcurementContract extends XotBaseModel
{
    /** @phpstan-use HasXotFactory<PublicProcurementContractFactory> */
    use HasXotFactory;

    use SoftDeletes;

    protected $table = 'public_procurement_contracts';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'awarded_on' => 'date',
            'signed_on' => 'date',
            'site_handover_on' => 'date',
            'works_start_on' => 'date',
            'works_due_on' => 'date',
            'works_completed_on' => 'date',
            'contract_amount' => 'decimal:2',
            'security_costs' => 'decimal:2',
            'vat_rate' => 'decimal:2',
            'discount_rate' => 'decimal:3',
            'retention_rate' => 'decimal:3',
            'meta' => 'array',
            'deleted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<WorkOrder, $this> */
    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class, 'work_order_id');
    }

    /** @return BelongsTo<PublicProcurementTenderOpportunity, $this> */
    public function opportunity(): BelongsTo
    {
        return $this->belongsTo(PublicProcurementTenderOpportunity::class, 'tender_opportunity_id');
    }

    /** @return BelongsTo<Customer, $this> */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /** @return HasMany<PublicProcurementDocumentRequirement, $this> */
    public function documentRequirements(): HasMany
    {
        return $this->hasMany(PublicProcurementDocumentRequirement::class, 'public_procurement_contract_id');
    }

    /** @return HasMany<PublicProcurementPriceItem, $this> */
    public function priceItems(): HasMany
    {
        return $this->hasMany(PublicProcurementPriceItem::class, 'public_procurement_contract_id');
    }

    /** @return HasMany<PublicProcurementMeasurement, $this> */
    public function measurements(): HasMany
    {
        return $this->hasMany(PublicProcurementMeasurement::class, 'public_procurement_contract_id');
    }

    /** @return HasMany<PublicProcurementProgressReport, $this> */
    public function progressReports(): HasMany
    {
        return $this->hasMany(PublicProcurementProgressReport::class, 'public_procurement_contract_id');
    }

    /** @return HasMany<PublicProcurementDeadline, $this> */
    public function deadlines(): HasMany
    {
        return $this->hasMany(PublicProcurementDeadline::class, 'public_procurement_contract_id');
    }

    /** @return HasMany<PublicProcurementOfficialRecord, $this> */
    public function officialRecords(): HasMany
    {
        return $this->hasMany(PublicProcurementOfficialRecord::class, 'public_procurement_contract_id');
    }

    /** @return HasMany<PublicProcurementGuarantee, $this> */
    public function guarantees(): HasMany
    {
        return $this->hasMany(PublicProcurementGuarantee::class, 'public_procurement_contract_id');
    }
}
