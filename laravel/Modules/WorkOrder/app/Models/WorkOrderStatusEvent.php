<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\User\Models\User;

/**
 * @property int $id
 * @property int $work_order_id
 * @property int|null $from_status_id
 * @property int $to_status_id
 * @property string|null $changed_by_user_id
 * @property string|null $note
 * @property Carbon|null $changed_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class WorkOrderStatusEvent extends BaseModel
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
