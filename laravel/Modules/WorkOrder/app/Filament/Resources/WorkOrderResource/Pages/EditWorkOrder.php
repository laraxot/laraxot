<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Resources\WorkOrderResource\Pages;

use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Modules\WorkOrder\Actions\ChangeWorkOrderStatusAction;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditWorkOrder extends XotBaseEditRecord
{
    protected static string $resource = WorkOrderResource::class;

    /**
     * Ensure status changes are tracked in history.
     *
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $newStatusId = isset($data['status_id']) && $data['status_id'] ? (int) $data['status_id'] : null;
        unset($data['status_id']);

        $oldStatusId = (int) ($record->getAttribute('status_id') ?? 0);

        $record->fill($data)->save();

        if ($newStatusId !== null && $newStatusId !== $oldStatusId) {
            if (! $record instanceof WorkOrder) {
                throw new \RuntimeException('Expected WorkOrder record.');
            }

            $authUserId = Auth::id();

            app(ChangeWorkOrderStatusAction::class)->execute(
                workOrder: $record->refresh(),
                toStatusId: $newStatusId,
                changedByUserId: $authUserId !== null ? (int) $authUserId : null,
                note: null,
                changedAt: null,
            );
        }

        return $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            'delete' => Actions\DeleteAction::make(),
            'restore' => Actions\RestoreAction::make(),
            'forceDelete' => Actions\ForceDeleteAction::make(),
        ];
    }
}
