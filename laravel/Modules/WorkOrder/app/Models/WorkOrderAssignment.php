<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Modules\User\Models\User;

/**
 * @property int $id
 * @property int $work_order_id
 * @property int $user_id
 * @property string|null $role
 * @property int|null $assigned_by_user_id
 * @property Carbon|null $assigned_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class WorkOrderAssignment extends BaseModel
{
    protected $table = 'work_order_assignments';

    /** @var array<int, string> */
    protected $guarded = [];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'assigned_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<WorkOrder, $this> */
    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return BelongsTo<User, $this> */
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by_user_id');
    }
}
