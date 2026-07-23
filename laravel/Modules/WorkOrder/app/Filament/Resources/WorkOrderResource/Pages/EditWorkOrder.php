<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Resources\WorkOrderResource\Pages;

use Illuminate\Database\Eloquent\Model;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Modules\WorkOrder\Actions\ChangeWorkOrderStatusAction;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource;

final class EditWorkOrder extends EditRecord
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
            app(ChangeWorkOrderStatusAction::class)->execute(
                workOrder: $record->refresh(),
                toStatusId: $newStatusId,
                changedByUserId: Auth::id(),
                note: null,
                changedAt: null,
            );
        }

        return $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\RestoreAction::make(),
            Actions\ForceDeleteAction::make(),
        ];
    }
}

