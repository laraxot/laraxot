<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Resources\InterventionResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerSite;
use Modules\Document\Filament\Pages\UploadInterventionPhotos;
use Modules\Intervention\Actions\FinalizeInterventionReportAction;
use Modules\Intervention\Actions\StartTimePunchAction;
use Modules\Intervention\Actions\StopTimePunchAction;
use Modules\Intervention\Actions\SyncInterventionWorkMinutesFromTimePunchesAction;
use Modules\Intervention\Exceptions\NoOpenTimePunchToStop;
use Modules\Intervention\Exceptions\OpenTimePunchAlreadyExists;
use Modules\Intervention\Filament\Resources\InterventionResource;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\TimePunch;
use Modules\User\Models\User;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListInterventions extends XotBaseListRecords
{
    protected static string $resource = InterventionResource::class;

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'work_order_id' => Tables\Columns\TextColumn::make('work_order_id')

                ->formatStateUsing(function ($state, Intervention $record): string {
                    if ($state === null || ! is_numeric($state) || (int) $state === 0) {
                        return '—';
                    }
                    $wo = DB::table('work_orders')->where('id', (int) $state)->first(['title', 'code']);

                    $title = $wo?->title;
                    $code = $wo?->code;

                    return is_string($title) ? $title : (is_string($code) ? $code : '—');
                })
                ->sortable(),
            'customer_display' => Tables\Columns\TextColumn::make('customer_display')

                ->state(fn (Intervention $record): string => $record->workOrder->customer->legal_name
                    ?? $record->customer->legal_name
                    ?? '—')
                ->searchable(query: function (Builder $query, string $search): Builder {
                    return $query->where(function ($q) use ($search): void {
                        $q->whereHas('workOrder.customer', fn ($wq) => $wq->where('legal_name', 'like', "%{$search}%"))
                            ->orWhereHas('customer', fn ($cq) => $cq->where('legal_name', 'like', "%{$search}%"));
                    });
                })
                ->toggleable(),
            'workOrder.site.name' => Tables\Columns\TextColumn::make('workOrder.site.name')

                ->searchable()
                ->toggleable(),
            'performedBy.name' => Tables\Columns\TextColumn::make('performedBy.name')

                ->searchable()
                ->sortable(),
            'assigned_users_summary' => Tables\Columns\TextColumn::make('assigned_users_summary')

                ->state(function (Intervention $record): string {
                    $names = $record->assignedUsers()
                        ->orderBy('name')
                        ->get()
                        ->map(fn (User $user): string => $user->name)
                        ->all();

                    if ($names === [] && $record->performedBy !== null) {
                        $names = [$record->performedBy->name];
                    }

                    return implode(', ', $names);
                })
                ->toggleable(),
            'started_at' => Tables\Columns\TextColumn::make('started_at')

                ->dateTime()
                ->sortable()
                ->toggleable(),
            'ended_at' => Tables\Columns\TextColumn::make('ended_at')

                ->dateTime()
                ->sortable()
                ->toggleable(),
            'scheduled_at' => Tables\Columns\TextColumn::make('scheduled_at')

                ->dateTime('d/m/Y H:i')
                ->sortable()
                ->toggleable(),
            'amount' => Tables\Columns\TextColumn::make('amount')

                ->money('EUR')
                ->sortable()
                ->toggleable(),
            'callout_fee' => Tables\Columns\TextColumn::make('callout_fee')

                ->money('EUR')
                ->sortable()
                ->toggleable(),
            'materials_total' => Tables\Columns\TextColumn::make('materials_total')

                ->money('EUR')
                ->sortable()
                ->toggleable(),
            'total_amount' => Tables\Columns\TextColumn::make('total_amount')

                ->money('EUR')
                ->sortable()
                ->toggleable(),
            // Extra fuori preventivo: se resta invisibile, viene regalato.
            'extra_total' => Tables\Columns\TextColumn::make('extra_total')

                ->state(fn (Intervention $record): float => round(
                    (float) $record->materialLines()->sum('extra_amount'),
                    2,
                ))
                ->money('EUR')
                ->badge()
                ->color(fn (mixed $state): string => is_numeric($state) && (float) $state > 0.0 ? 'warning' : 'gray'),
            'work_minutes' => Tables\Columns\TextColumn::make('work_minutes')

                ->numeric()
                ->sortable(),
            'outcome_code' => Tables\Columns\TextColumn::make('outcome_code')

                ->formatStateUsing(fn (?string $state): string => match ($state) {
                    'completed' => __('interventions.outcomes.completed'),
                    'suspended' => __('interventions.outcomes.suspended'),
                    'quotation' => __('interventions.outcomes.quotation'),
                    default => $state ?: '—',
                })
                ->badge()
                ->toggleable(),
            'notes' => Tables\Columns\TextColumn::make('notes')

                ->limit(70)
                ->searchable()
                ->toggleable(),
            'travel_km' => Tables\Columns\TextColumn::make('travel_km')

                ->numeric()
                ->sortable()
                ->toggleable(),
            'updated_at' => Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    /**
     * @return array<string, BaseFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'has_extra' => Filter::make('has_extra')

                ->query(fn (Builder $query): Builder => $query->whereHas(
                    'materialLines',
                    fn ($lines) => $lines->where('extra_quantity', '>', 0),
                )),
            'customer_id' => SelectFilter::make('customer_id')

                ->options(fn (): array => Customer::query()->whereNotNull('legal_name')->orderBy('legal_name')->pluck('legal_name', 'id')->all())
                ->query(function (Builder $query, array $data): Builder {
                    $value = $data['value'] ?? 0;
                    $customerId = is_numeric($value) ? (int) $value : 0;

                    return $customerId > 0
                        ? $query->where(function ($q) use ($customerId): void {
                            $q->whereHas('workOrder', fn ($wq) => $wq->where('customer_id', $customerId))
                                ->orWhere('customer_id', $customerId);
                        })
                        : $query;
                }),
            'customer_site_id' => SelectFilter::make('customer_site_id')

                ->options(fn (): array => CustomerSite::query()->whereNotNull('name')->orderBy('name')->pluck('name', 'id')->all())
                ->query(function (Builder $query, array $data): Builder {
                    $value = $data['value'] ?? 0;
                    $siteId = is_numeric($value) ? (int) $value : 0;

                    return $siteId > 0
                        ? $query->whereHas('workOrder', fn ($workOrderQuery) => $workOrderQuery->where('customer_site_id', $siteId))
                        : $query;
                }),
            'outcome_code' => SelectFilter::make('outcome_code')

                ->options([
                    'completed' => __('interventions.outcomes.completed'),
                    'suspended' => __('interventions.outcomes.suspended'),
                    'quotation' => __('interventions.outcomes.quotation'),
                ]),
            'scheduled_between' => Filter::make('scheduled_between')
                ->label(__('work_orders.fields.report_from').' / '.__('work_orders.fields.report_to'))
                ->form([
                    DatePicker::make('from'),
                    DatePicker::make('to'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    $from = is_string($data['from'] ?? null) ? $data['from'] : null;
                    $to = is_string($data['to'] ?? null) ? $data['to'] : null;

                    return $query
                        ->when(
                            filled($from),
                            fn ($innerQuery) => $innerQuery->whereDate('scheduled_at', '>=', $from),
                        )
                        ->when(
                            filled($to),
                            fn ($innerQuery) => $innerQuery->whereDate('scheduled_at', '<=', $to),
                        );
                }),
        ];
    }

    /**
     * @return array<string, Action>
     */
    public function getTableActions(): array
    {
        return [
            'start_time_punch' => Action::make('start_time_punch')

                ->icon('heroicon-o-play')
                ->visible(function (Intervention $record): bool {
                    $userId = Auth::id();
                    if ($userId === null) {
                        return false;
                    }
                    if (! InterventionResource::isAssignedToUser($record, (string) $userId)) {
                        return false;
                    }

                    $hasOpenOnThis = TimePunch::query()
                        ->where('intervention_id', $record->id)
                        ->where('user_id', $userId)
                        ->whereNull('ended_at')
                        ->exists();

                    return ! $hasOpenOnThis;
                })
                ->action(function (Intervention $record): void {
                    $userId = (int) Auth::id();
                    try {
                        app(StartTimePunchAction::class)->execute($record, $userId);
                    } catch (OpenTimePunchAlreadyExists $e) {
                        Notification::make()
                            ->title($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            'stop_time_punch' => Action::make('stop_time_punch')

                ->icon('heroicon-o-stop')
                ->color('danger')
                ->visible(function (Intervention $record): bool {
                    $userId = Auth::id();
                    if ($userId === null) {
                        return false;
                    }
                    if (! InterventionResource::isAssignedToUser($record, (string) $userId)) {
                        return false;
                    }

                    return TimePunch::query()
                        ->where('intervention_id', $record->id)
                        ->where('user_id', $userId)
                        ->whereNull('ended_at')
                        ->exists();
                })
                ->requiresConfirmation()
                ->action(function (Intervention $record): void {
                    $userId = (int) Auth::id();
                    try {
                        app(StopTimePunchAction::class)->execute($record, $userId);
                        app(SyncInterventionWorkMinutesFromTimePunchesAction::class)->execute($record->refresh());
                    } catch (NoOpenTimePunchToStop $e) {
                        Notification::make()
                            ->title($e->getMessage())
                            ->warning()
                            ->send();
                    }
                }),
            'validate' => Action::make('validate')

                ->icon('heroicon-o-check-circle')
                ->visible(function (Intervention $record): bool {
                    $userId = Auth::id();
                    if ($userId === null) {
                        return false;
                    }

                    return InterventionResource::isAssignedToUser($record, (string) $userId);
                })
                ->form([
                    TextInput::make('travel_km')

                        ->numeric()
                        ->minValue(0)
                        ->required()
                        ->default(fn (Intervention $record): int => (int) ($record->travel_km ?? 0)),
                    TextInput::make('travel_minutes')

                        ->numeric()
                        ->minValue(0)
                        ->required()
                        ->default(fn (Intervention $record): int => (int) ($record->travel_minutes ?? 0)),
                    Textarea::make('notes')

                        ->default(fn (Intervention $record): ?string => $record->notes)
                        ->columnSpanFull(),
                    Select::make('outcome_code')

                        ->options([
                            'completed' => __('interventions.outcomes.completed'),
                            'suspended' => __('interventions.outcomes.suspended'),
                            'quotation' => __('interventions.outcomes.quotation'),
                        ])
                        ->required()
                        ->default('completed'),
                ])
                ->action(function (Intervention $record, array $data): void {
                    $userId = (int) Auth::id();
                    $outcomeCodeValue = $data['outcome_code'] ?? 'completed';
                    $outcomeCode = is_string($outcomeCodeValue) ? $outcomeCodeValue : 'completed';
                    if (! in_array($outcomeCode, ['completed', 'suspended', 'quotation'], true)) {
                        $outcomeCode = 'completed';
                    }

                    $travelKmValue = $data['travel_km'] ?? 0;
                    $travelMinutesValue = $data['travel_minutes'] ?? 0;

                    app(FinalizeInterventionReportAction::class)->execute(
                        intervention: $record,
                        validatedByUserId: $userId,
                        travelKm: is_numeric($travelKmValue) ? (int) $travelKmValue : 0,
                        travelMinutes: is_numeric($travelMinutesValue) ? (int) $travelMinutesValue : 0,
                        notes: isset($data['notes']) ? (is_string($data['notes']) ? $data['notes'] : null) : null,
                        outcomeCode: $outcomeCode,
                    );
                }),
            'upload_photos' => Action::make('upload_photos')

                ->icon('heroicon-o-camera')
                ->visible(function (Intervention $record): bool {
                    if (! request()->is('operator/*')) {
                        return false;
                    }

                    $userId = Auth::id();
                    if ($userId === null) {
                        return false;
                    }

                    return InterventionResource::isAssignedToUser($record, (string) $userId);
                })
                ->url(fn (Intervention $record): string => UploadInterventionPhotos::getUrl([
                    'interventionId' => $record->id,
                ])),
            'view' => ViewAction::make(),
            'edit' => EditAction::make()
                ->visible(fn (): bool => ! request()->is('operator/*')),
        ];
    }

    /**
     * @return array<string, BulkAction>
     */
    public function getTableBulkActions(): array
    {
        return [
            'deleteBulk' => DeleteBulkAction::make()
                ->visible(fn (): bool => ! request()->is('operator/*')),
            'restoreBulk' => RestoreBulkAction::make()
                ->visible(fn (): bool => ! request()->is('operator/*')),
            'forceDeleteBulk' => ForceDeleteBulkAction::make()
                ->visible(fn (): bool => ! request()->is('operator/*')),
        ];
    }
}
