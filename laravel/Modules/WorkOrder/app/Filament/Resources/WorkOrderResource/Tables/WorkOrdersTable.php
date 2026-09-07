<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Resources\WorkOrderResource\Tables;

use App\Support\AccountFeatures;
use Carbon\CarbonImmutable;
use Filament\Actions\Action;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\User\Models\User;
use Modules\WorkOrder\Actions\GenerateWorkOrderReportAction;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

/**
 * Tabella principale WorkOrder — SSOT colonne/filtri/azioni bulk (XotBaseResourceTable pattern).
 */
final class WorkOrdersTable extends XotBaseResourceTable
{
    protected static string $resource = WorkOrderResource::class;

    /**
     * @return array<int|string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            TextColumn::make('code')
                ->searchable()
                ->sortable(),
            // Lo sforamento va visto in lista, non solo aprendo la commessa.
            TextColumn::make('effort_variance')
                ->state(fn (WorkOrder $record): string => WorkOrderResource::effortBadge($record))
                ->badge()
                ->color(fn (WorkOrder $record): string => WorkOrderResource::effortColor($record))
                ->toggleable(),
            TextColumn::make('title')
                ->searchable()
                ->sortable(),
            TextColumn::make('customer.legal_name')
                ->searchable()
                ->sortable(),
            TextColumn::make('site.name')
                ->searchable()
                ->toggleable(),
            TextColumn::make('status.name')
                ->badge()
                ->sortable(),
            TextColumn::make('description')
                ->limit(60)
                ->searchable()
                ->toggleable(),
            TextColumn::make('notes')
                ->limit(60)
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
            TextColumn::make('scheduled_at')
                ->dateTime()
                ->sortable()
                ->toggleable(),
            TextColumn::make('progress_percent')
                ->badge()
                ->color(fn (?int $state): string => match (true) {
                    $state === null || $state === 0 => 'gray',
                    $state >= 100 => 'success',
                    default => 'warning',
                })
                ->formatStateUsing(fn (?int $state): string => ((int) $state).'%')
                ->sortable(),
            // I ritardi di consegna devono urlare dalla lista.
            TextColumn::make('delivery_due_at')
                ->dateTime()
                ->sortable()
                ->color(fn (?string $state, WorkOrder $record): string => WorkOrderResource::deliveryColour($state, $record))
                ->weight(fn (?string $state, WorkOrder $record): ?string => WorkOrderResource::deliveryColour($state, $record) === 'danger' ? 'bold' : null),
            TextColumn::make('updated_at')
                ->dateTime()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    /**
     * @return array<string, Filter|SelectFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'customer' => SelectFilter::make('customer_id')
                ->relationship('customer', 'legal_name', fn (Builder $query): Builder => $query->whereNotNull('legal_name')),
            'site' => SelectFilter::make('customer_site_id')
                ->relationship('site', 'name', fn (Builder $query): Builder => $query->whereNotNull('name')),
            'status' => SelectFilter::make('status_id')
                ->relationship('status', 'name', fn (Builder $query): Builder => $query->whereNotNull('name')),
            'scheduled_between' => Filter::make('scheduled_between')
                ->schema([
                    DatePicker::make('from'),
                    DatePicker::make('to'),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    $from = is_string($data['from'] ?? null) ? $data['from'] : null;
                    $to = is_string($data['to'] ?? null) ? $data['to'] : null;

                    return $query
                        ->when(
                            filled($from),
                            fn (Builder $innerQuery): Builder => $innerQuery->whereDate('scheduled_at', '>=', $from),
                        )
                        ->when(
                            filled($to),
                            fn (Builder $innerQuery): Builder => $innerQuery->whereDate('scheduled_at', '<=', $to),
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
            'exportReport' => Action::make('exportReport')
                ->icon('heroicon-o-document-chart-bar')
                ->color('success')
                ->visible(function (): bool {
                    /** @var User|null $user */
                    $user = Auth::user();

                    if (! request()->is('admin/*')) {
                        return ! ($user instanceof User && ! AccountFeatures::hasFeature($user, 'operator.work_orders', true));
                    }

                    return AccountFeatures::hasFeature($user, 'admin.reports', true);
                })
                ->schema([
                    Radio::make('format')
                        ->options([
                            'pdf' => 'PDF',
                            'docx' => 'Word (DOCX)',
                        ])
                        ->default('pdf')
                        ->required()
                        ->inline(),
                    DatePicker::make('from')
                        ->displayFormat('d/m/Y')
                        ->default(now()->startOfMonth()->toDateString())
                        ->required(),
                    DatePicker::make('to')
                        ->displayFormat('d/m/Y')
                        ->default(now()->endOfMonth()->toDateString())
                        ->required(),
                ])
                ->action(function (WorkOrder $record, array $data): mixed {
                    $format = match (is_string($data['format'] ?? null) ? $data['format'] : 'pdf') {
                        'docx' => 'docx',
                        default => 'pdf',
                    };
                    $fromRaw = is_string($data['from'] ?? null) ? $data['from'] : now()->startOfMonth()->toDateString();
                    $toRaw = is_string($data['to'] ?? null) ? $data['to'] : now()->endOfMonth()->toDateString();
                    $from = CarbonImmutable::parse($fromRaw)->startOfDay();
                    $to = CarbonImmutable::parse($toRaw)->endOfDay();

                    $result = app(GenerateWorkOrderReportAction::class)->execute(
                        workOrder: $record,
                        from: $from,
                        to: $to,
                        format: $format,
                    );

                    return response()->streamDownload(
                        function () use ($result): void {
                            echo $result['content'];
                        },
                        $result['filename'],
                        ['Content-Type' => $result['mime']],
                    );
                }),
            'preInvoice' => WorkOrderResource::preInvoiceAction(),
            'view' => ViewAction::make(),
            'edit' => EditAction::make()
                ->visible(fn (): bool => ! request()->is('operator/*') && ! request()->is('customer/*')),
        ];
    }

    /**
     * @return array<string, DeleteBulkAction|RestoreBulkAction|ForceDeleteBulkAction>
     */
    public function getTableBulkActions(): array
    {
        return [
            'delete' => DeleteBulkAction::make()
                ->visible(fn (): bool => ! request()->is('operator/*') && ! request()->is('customer/*')),
            'restore' => RestoreBulkAction::make()
                ->visible(fn (): bool => ! request()->is('operator/*') && ! request()->is('customer/*')),
            'forceDelete' => ForceDeleteBulkAction::make()
                ->visible(fn (): bool => ! request()->is('operator/*') && ! request()->is('customer/*')),
        ];
    }
}
