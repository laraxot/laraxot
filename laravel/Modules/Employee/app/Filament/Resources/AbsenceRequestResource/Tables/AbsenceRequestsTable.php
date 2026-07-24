<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Resources\AbsenceRequestResource\Tables;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;
use Modules\Employee\Actions\ApproveAbsenceRequestAction;
use Modules\Employee\Actions\RejectAbsenceRequestAction;
use Modules\Employee\Models\AbsenceRequest;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

class AbsenceRequestsTable extends XotBaseResourceTable
{
    /**
     * @return array<int|string, Action|ActionGroup>
     */
    public function getTableActions(): array
    {
        $actions = parent::getTableActions();

        $actions['approve'] = Action::make('approve')
            ->label(__('employee::absence_request.actions.approve'))
            ->icon('heroicon-o-check-circle')
            ->color('success')
            ->requiresConfirmation()
            ->visible(static fn (AbsenceRequest $record): bool => $record->status === AbsenceRequest::STATUS_PENDING)
            ->action(function (AbsenceRequest $record): void {
                app(ApproveAbsenceRequestAction::class)->execute($record, (int) Auth::id());
            });

        $actions['reject'] = Action::make('reject')
            ->label(__('employee::absence_request.actions.reject'))
            ->icon('heroicon-o-x-circle')
            ->color('danger')
            ->requiresConfirmation()
            ->visible(static fn (AbsenceRequest $record): bool => $record->status === AbsenceRequest::STATUS_PENDING)
            ->action(function (AbsenceRequest $record): void {
                app(RejectAbsenceRequestAction::class)->execute($record, (int) Auth::id());
            });

        return $actions;
    }

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'id' => TextColumn::make('id')->sortable()->searchable(),
            'user.name' => TextColumn::make('user.name')
                ->label(__('employee::absence_request.fields.user'))
                ->sortable()
                ->searchable(),
            'type' => TextColumn::make('type')
                ->label(__('employee::absence_request.fields.type'))
                ->badge()
                ->formatStateUsing(fn (string $state): string => __("employee::absence_request.types.{$state}")),
            'starts_at' => TextColumn::make('starts_at')
                ->label(__('employee::absence_request.fields.starts_at'))
                ->dateTime('d/m/Y H:i')
                ->sortable(),
            'ends_at' => TextColumn::make('ends_at')
                ->label(__('employee::absence_request.fields.ends_at'))
                ->dateTime('d/m/Y H:i')
                ->sortable(),
            'status' => TextColumn::make('status')
                ->label(__('employee::absence_request.fields.status'))
                ->badge()
                ->colors([
                    'warning' => AbsenceRequest::STATUS_PENDING,
                    'success' => AbsenceRequest::STATUS_APPROVED,
                    'danger' => AbsenceRequest::STATUS_REJECTED,
                ])
                ->formatStateUsing(fn (string $state): string => __("employee::absence_request.statuses.{$state}"))
                ->sortable(),
            'decidedBy.name' => TextColumn::make('decidedBy.name')
                ->label(__('employee::absence_request.fields.decided_by'))
                ->sortable(),
            'decided_at' => TextColumn::make('decided_at')
                ->label(__('employee::absence_request.fields.decided_at'))
                ->dateTime('d/m/Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            'created_at' => TextColumn::make('created_at')
                ->dateTime('d/m/Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }
}
