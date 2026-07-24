<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Resources\WorkOrderResource\Pages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\WorkOrder\Actions\ChangeWorkOrderStatusAction;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

final class CreateWorkOrder extends XotBaseCreateRecord
{
    protected static string $resource = WorkOrderResource::class;

    /**
     * Ensure initial status change is tracked in history.
     *
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordCreation(array $data): Model
    {
        $statusId = isset($data['status_id']) && $data['status_id'] ? (int) $data['status_id'] : null;
        unset($data['status_id']);

        /** @var WorkOrder $workOrder */
        $workOrder = WorkOrder::query()->create($data);

        if ($statusId !== null) {
            $authUserId = Auth::id();

            app(ChangeWorkOrderStatusAction::class)->execute(
                workOrder: $workOrder,
                toStatusId: $statusId,
                changedByUserId: $authUserId !== null ? (int) $authUserId : null,
                note: null,
                changedAt: null,
            );
        }

        return $workOrder;
    }
}
