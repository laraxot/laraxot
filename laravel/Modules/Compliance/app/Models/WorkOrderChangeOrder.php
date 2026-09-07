<?php

declare(strict_types=1);

namespace Modules\Compliance\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Modules\Compliance\Actions\EvaluateWorkOrderReadinessAction;
use Modules\Document\Models\Media;
use Modules\User\Models\User;
use Modules\WorkOrder\Models\WorkOrder;

/**
 * @property int $id
 * @property int $work_order_id
 * @property string|null $requested_by_user_id
 * @property string|null $approved_by_user_id
 * @property string $title
 * @property string|null $description
 * @property string|null $estimated_amount
 * @property string $status
 * @property Carbon|null $approved_at
 * @property Carbon|null $executed_at
 * @property Carbon|null $billed_at
 * @property int|null $signature_media_id
 * @property array<string, mixed>|null $meta
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
final class WorkOrderChangeOrder extends BaseModel
{
    use SoftDeletes;

    protected $table = 'work_order_change_orders';

    /** @var array<int, string> */
    protected $guarded = [];

    protected static function booted(): void
    {
        self::saved(static function (WorkOrderChangeOrder $changeOrder): void {
            $changeOrder->refreshWorkOrderReadiness();
        });

        self::deleted(static function (WorkOrderChangeOrder $changeOrder): void {
            $changeOrder->refreshWorkOrderReadiness();
        });

        self::restored(static function (WorkOrderChangeOrder $changeOrder): void {
            $changeOrder->refreshWorkOrderReadiness();
        });
    }

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'estimated_amount' => 'decimal:2',
            'approved_at' => 'datetime',
            'executed_at' => 'datetime',
            'billed_at' => 'datetime',
            'meta' => 'array',
            'deleted_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<WorkOrder, $this> */
    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(WorkOrder::class);
    }

    /** @return BelongsTo<User, $this> */
    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by_user_id');
    }

    /** @return BelongsTo<User, $this> */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }

    /** @return BelongsTo<Media, $this> */
    public function signatureMedia(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'signature_media_id');
    }

    public function refreshWorkOrderReadiness(): void
    {
        /** @var WorkOrder|null $workOrder */
        $workOrder = WorkOrder::query()->find($this->work_order_id);
        if (! $workOrder instanceof WorkOrder) {
            return;
        }

        app(EvaluateWorkOrderReadinessAction::class)->execute($workOrder);
    }
}
