<?php

declare(strict_types=1);

namespace Modules\Compliance\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\WorkOrder\Models\WorkOrder;

/**
 * @property int $id
 * @property string $name
 * @property string|null $type
 * @property string|null $serial_number
 * @property string $status
 * @property Carbon|null $last_checked_on
 * @property Carbon|null $next_check_due_on
 * @property Carbon|null $maintenance_due_on
 * @property int|null $assigned_work_order_id
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
final class Equipment extends BaseModel
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
