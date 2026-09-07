<?php

declare(strict_types=1);

namespace Modules\HR\Filament\Resources\AbsenceRequestResource\Tables;

use Filament\Actions\Action;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Modules\HR\Actions\ApproveAbsenceRequestAction;
use Modules\HR\Actions\RejectAbsenceRequestAction;
use Modules\HR\Filament\Resources\AbsenceRequestResource;
use Modules\HR\Models\AbsenceRequest;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class AbsenceRequestsTable extends XotBaseResourceTable
{
    protected static string $resource = AbsenceRequestResource::class;

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'type' => TextColumn::make('type')
                ->label(__('hr.fields.absence_type'))
                ->formatStateUsing(fn (?string $state): string => self::translateAbsenceType($state))
                ->badge(),
            'starts_at' => TextColumn::make('starts_at')
                ->label(__('hr.fields.starts_at'))
                ->dateTime()
                ->sortable(),
            'ends_at' => TextColumn::make('ends_at')
                ->label(__('hr.fields.ends_at'))
                ->dateTime()
                ->sortable(),
            'status' => TextColumn::make('status')
                ->label(__('hr.fields.status'))
                ->formatStateUsing(fn (string $state): string => __("hr.statuses.{$state}"))
                ->badge()
                ->sortable(),
        ];
    }

    /**
     * @return array<string, Action>
     */
    public function getTableFilters(): array
    {
        return [];
    }

    /**
     * @return array<string, ViewAction|EditAction|Action>
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
     * @return array<string, DeleteBulkAction>
     */
    public function getTableBulkActions(): array
    {
        return [
            'delete' => DeleteBulkAction::make(),
        ];
    }

    private const array ABSENCE_TYPE_ALIASES = [
        'permesso' => 'leave',
        'ferie' => 'vacation',
        'malattia' => 'sick',
        'infortunio' => 'injury',
    ];

    public static function translateAbsenceType(?string $type): string
    {
        if ($type === null || trim($type) === '') {
            return '-';
        }

        $normalized = self::normalizeAbsenceType($type);
        if ($normalized === null || trim($normalized) === '') {
            return '-';
        }

        $key = "hr::hr.absence_types.{$normalized}";

        if (Lang::has($key)) {
            return __($key);
        }

        return ucfirst(str_replace('_', ' ', $type));
    }

    private static function normalizeAbsenceType(?string $type): ?string
    {
        if ($type === null || trim($type) === '') {
            return $type;
        }

        $normalized = strtolower(trim($type));

        return self::ABSENCE_TYPE_ALIASES[$normalized] ?? $normalized;
    }
}
