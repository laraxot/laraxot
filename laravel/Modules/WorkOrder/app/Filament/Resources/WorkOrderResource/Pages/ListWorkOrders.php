<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Resources\WorkOrderResource\Pages;

use App\Support\AccountFeatures;
use Carbon\CarbonImmutable;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\User\Models\User;
use Modules\WorkOrder\Actions\GenerateWorkOrderReportAction;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListWorkOrders extends XotBaseListRecords
{
    protected static string $resource = WorkOrderResource::class;

    /**
     * @return array<string, Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            'create' => Actions\CreateAction::make(),
        ];
    }

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'code' => Tables\Columns\TextColumn::make('code')
                ->searchable()
                ->sortable(),
            // Lo sforamento va visto in lista, non solo aprendo la commessa.
            'effort_variance' => Tables\Columns\TextColumn::make('effort_variance')
                ->state(fn (WorkOrder $record): string => WorkOrderResource::effortBadge($record))
                ->badge()
                ->color(fn (WorkOrder $record): string => WorkOrderResource::effortColor($record))
                ->toggleable(),
            'title' => Tables\Columns\TextColumn::make('title')
                ->searchable()
                ->sortable(),
            'customer.legal_name' => Tables\Columns\TextColumn::make('customer.legal_name')
                ->searchable()
                ->sortable(),
            'site.name' => Tables\Columns\TextColumn::make('site.name')
                ->searchable()
                ->toggleable(),
            'status.name' => Tables\Columns\TextColumn::make('status.name')
                ->badge()
                ->sortable(),
            'description' => Tables\Columns\TextColumn::make('description')
                ->limit(60)
                ->searchable()
                ->toggleable(),
            'notes' => Tables\Columns\TextColumn::make('notes')
                ->limit(60)
                ->searchable()
                ->toggleable(isToggledHiddenByDefault: true),
            'scheduled_at' => Tables\Columns\TextColumn::make('scheduled_at')
                ->dateTime()
                ->sortable()
                ->toggleable(),
            'progress_percent' => Tables\Columns\TextColumn::make('progress_percent')
                ->label(__('workorder::work_orders.progress.label'))
                ->badge()
                ->color(fn (?int $state): string => match (true) {
                    $state === null || $state === 0 => 'gray',
                    $state >= 100 => 'success',
                    default => 'warning',
                })
                ->formatStateUsing(fn (?int $state): string => ((int) $state).'%')
                ->sortable(),
            // I ritardi di consegna devono urlare dalla lista.
            'delivery_due_at' => Tables\Columns\TextColumn::make('delivery_due_at')
                ->label(__('workorder::work_orders.fields.delivery_due_at'))
                ->dateTime()
                ->sortable()
                ->color(fn (?string $state, WorkOrder $record): string => WorkOrderResource::deliveryColour($state, $record))
                ->weight(fn (?string $state, WorkOrder $record): ?string => WorkOrderResource::deliveryColour($state, $record) === 'danger' ? 'bold' : null),
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
            'customer_id' => SelectFilter::make('customer_id')
                ->relationship('customer', 'legal_name', fn (Builder $query): Builder => $query->whereNotNull('legal_name')),
            'customer_site_id' => SelectFilter::make('customer_site_id')
                ->relationship('site', 'name', fn (Builder $query): Builder => $query->whereNotNull('name')),
            'status_id' => SelectFilter::make('status_id')
                ->relationship('status', 'name', fn (Builder $query): Builder => $query->whereNotNull('name')),
            'scheduled_between' => Filter::make('scheduled_between')
                ->label(__('workorder::work_orders.fields.report_from').' / '.__('workorder::work_orders.fields.report_to'))
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
            'item' => WorkOrderResource::preInvoiceAction(),
            'view' => ViewAction::make(),
            'edit' => EditAction::make()
                ->visible(fn (): bool => ! request()->is('operator/*') && ! request()->is('customer/*')),
        ];
    }

    /**
     * @return array<string, BulkAction>
     */
    public function getTableBulkActions(): array
    {
        return [
            'deleteBulk' => DeleteBulkAction::make()
                ->visible(fn (): bool => ! request()->is('operator/*') && ! request()->is('customer/*')),
            'restoreBulk' => RestoreBulkAction::make()
                ->visible(fn (): bool => ! request()->is('operator/*') && ! request()->is('customer/*')),
            'forceDeleteBulk' => ForceDeleteBulkAction::make()
                ->visible(fn (): bool => ! request()->is('operator/*') && ! request()->is('customer/*')),
        ];
    }
}
