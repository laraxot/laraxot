<?php

declare(strict_types=1);

namespace Modules\Compliance\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Models\XotBaseModel;

final class Equipment extends XotBaseModel
{
    use SoftDeletes;

    protected $table = 'equipment';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'last_checked_on' => 'date',
            'next_check_due_on' => 'date',
            'maintenance_due_on' => 'date',
            'deleted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<WorkOrder, $this> */
    public function assignedWorkOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class, 'assigned_work_order_id');
    }

    /** @return HasMany<ComplianceDocument, $this> */
    public function complianceDocuments(): HasMany
    {
        return $this->hasMany(ComplianceDocument::class);
    }
}
