<?php

declare(strict_types=1);

namespace Modules\HR\Filament\Resources\AbsenceRequestResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Auth;
use Modules\HR\Actions\ApproveAbsenceRequestAction;
use Modules\HR\Actions\RejectAbsenceRequestAction;
use Modules\HR\Filament\Resources\AbsenceRequestResource;
use Modules\HR\Models\AbsenceRequest;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListAbsenceRequests extends XotBaseListRecords
{
    protected static string $resource = AbsenceRequestResource::class;

    /**
     * @return array<string, Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            'create' => CreateAction::make(),
        ];
    }

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'type' => Tables\Columns\TextColumn::make('type')
                ->label(__('hr.fields.absence_type'))
                ->formatStateUsing(fn (?string $state): string => AbsenceRequestResource::translateAbsenceType($state))
                ->badge(),
            'starts_at' => Tables\Columns\TextColumn::make('starts_at')
                ->label(__('hr.fields.starts_at'))
                ->dateTime()
                ->sortable(),
            'ends_at' => Tables\Columns\TextColumn::make('ends_at')
                ->label(__('hr.fields.ends_at'))
                ->dateTime()
                ->sortable(),
            'status' => Tables\Columns\TextColumn::make('status')
                ->label(__('hr.fields.status'))
                ->formatStateUsing(fn (string $state): string => __("hr.statuses.{$state}"))
                ->badge()
                ->sortable(),
        ];
    }

    /**
     * @return array<string, BaseFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'status' => SelectFilter::make('status')
                ->label(__('hr.fields.status'))
                ->options([
                    'pending' => __('hr.statuses.pending'),
                    'approved' => __('hr.statuses.approved'),
                    'rejected' => __('hr.statuses.rejected'),
                ])
                ->default('pending'),
        ];
    }

    /**
     * @return array<string, Action>
     */
    public function getTableActions(): array
    {
        return [
            'view' => ViewAction::make(),
            'edit' => EditAction::make(),
            'approve' => Action::make('approve')
                ->label(__('hr.actions.approve'))
                ->color('success')
                ->visible(fn (AbsenceRequest $record): bool => request()->is('admin/*') && $record->status === 'pending')
                ->requiresConfirmation()
                ->action(fn (AbsenceRequest $record): AbsenceRequest => app(ApproveAbsenceRequestAction::class)->execute($record, (int) Auth::id())),
            'reject' => Action::make('reject')
                ->label(__('hr.actions.reject'))
                ->color('danger')
                ->visible(fn (AbsenceRequest $record): bool => request()->is('admin/*') && $record->status === 'pending')
                ->requiresConfirmation()
                ->action(fn (AbsenceRequest $record): AbsenceRequest => app(RejectAbsenceRequestAction::class)->execute($record, (int) Auth::id())),
        ];
    }

    /**
     * @return array<string, BulkAction>
     */
    public function getTableBulkActions(): array
    {
        return [
            'deleteBulk' => DeleteBulkAction::make(),
        ];
    }
}
