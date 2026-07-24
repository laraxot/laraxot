<?php

declare(strict_types=1);

namespace Modules\Compliance\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Compliance\Actions\EvaluateWorkOrderReadinessAction;
use Modules\Document\Models\Media;
use Modules\User\Models\User;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Models\XotBaseModel;

final class WorkOrderChangeOrder extends XotBaseModel
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
