<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Compliance\Models\ComplianceDocument;
use Modules\Compliance\Models\WorkOrderChangeOrder;
use Modules\Compliance\Models\WorkOrderReadinessItem;
use Modules\Customer\Models\Contact;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerSite;
use Modules\Intervention\Models\Intervention;
use Modules\PublicProcurement\Models\PublicProcurementContract;

final class WorkOrder extends Model
{
    use SoftDeletes;

    protected $table = 'work_orders';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'opened_at' => 'datetime',
            'scheduled_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'closed_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<Customer, $this> */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /** @return BelongsTo<CustomerSite, $this> */
    public function site(): BelongsTo
    {
        return $this->belongsTo(CustomerSite::class, 'customer_site_id');
    }

    /** @return BelongsTo<Contact, $this> */
    public function primaryContact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'primary_contact_id');
    }

    /** @return BelongsTo<WorkOrderStatus, $this> */
    public function status(): BelongsTo
    {
        return $this->belongsTo(WorkOrderStatus::class, 'status_id');
    }

    /** @return HasMany<WorkOrderActivity, $this> */
    public function activities(): HasMany
    {
        return $this->hasMany(WorkOrderActivity::class);
    }

    /** @return HasMany<WorkOrderReadinessItem, $this> */
    public function readinessItems(): HasMany
    {
        return $this->hasMany(WorkOrderReadinessItem::class);
    }

    /** @return HasMany<WorkOrderAssignment, $this> */
    public function assignments(): HasMany
    {
        return $this->hasMany(WorkOrderAssignment::class);
    }

    /** @return HasMany<WorkOrderStatusEvent, $this> */
    public function statusEvents(): HasMany
    {
        return $this->hasMany(WorkOrderStatusEvent::class);
    }

    /** @return HasMany<Intervention, $this> */
    public function interventions(): HasMany
    {
        return $this->hasMany(Intervention::class, 'work_order_id');
    }

    /** @return HasMany<ComplianceDocument, $this> */
    public function complianceDocuments(): HasMany
    {
        return $this->hasMany(ComplianceDocument::class, 'work_order_id');
    }

    /** @return HasMany<WorkOrderChangeOrder, $this> */
    public function changeOrders(): HasMany
    {
        return $this->hasMany(WorkOrderChangeOrder::class, 'work_order_id');
    }

    /** @return HasMany<PublicProcurementContract, $this> */
    public function publicProcurementContracts(): HasMany
    {
        return $this->hasMany(PublicProcurementContract::class, 'work_order_id');
    }

}

