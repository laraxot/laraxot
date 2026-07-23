<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class WorkOrderStatusEvent extends Model
{
    protected $table = 'work_order_status_events';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'changed_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<WorkOrder, $this> */
    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    /** @return BelongsTo<WorkOrderStatus, $this> */
    public function fromStatus(): BelongsTo
    {
        return $this->belongsTo(WorkOrderStatus::class, 'from_status_id');
    }

    /** @return BelongsTo<WorkOrderStatus, $this> */
    public function toStatus(): BelongsTo
    {
        return $this->belongsTo(WorkOrderStatus::class, 'to_status_id');
    }

    /** @return BelongsTo<User, $this> */
    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by_user_id');
    }
}

