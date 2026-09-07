<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\Tables;

use Carbon\CarbonImmutable;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\BaseFilter;
use Filament\Tables\Filters\SelectFilter;
use Modules\PublicProcurement\Actions\GenerateProgressReportFromMeasurementsAction;
use Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource;
use Modules\PublicProcurement\Models\PublicProcurementContract;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class PublicProcurementContractsTable extends XotBaseResourceTable
{
    protected static string $resource = PublicProcurementContractResource::class;

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'status' => TextColumn::make('status')
                ->label(__('public_procurement.fields.status'))
                ->badge()
                ->formatStateUsing(fn (string $state): string => PublicProcurementContractResource::statuses()[$state] ?? $state)
                ->sortable(),
            'code' => TextColumn::make('code')->label(__('public_procurement.fields.code'))->searchable()->sortable(),
            'title' => TextColumn::make('title')->label(__('public_procurement.fields.title'))->searchable()->limit(50),
            'contracting_authority' => TextColumn::make('contracting_authority')->label(__('public_procurement.fields.contracting_authority'))->searchable()->limit(34),
            'cig' => TextColumn::make('cig')->label(__('public_procurement.fields.cig'))->searchable()->toggleable(),
            'cup' => TextColumn::make('cup')->label(__('public_procurement.fields.cup'))->searchable()->toggleable(),
            'contract_amount' => TextColumn::make('contract_amount')->label(__('public_procurement.fields.contract_amount'))->money('EUR')->sortable(),
            'works_due_on' => TextColumn::make('works_due_on')->label(__('public_procurement.fields.works_due_on'))->date()->sortable(),
        ];
    }

    /**
     * @return array<string, BaseFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'status' => SelectFilter::make('status')->label(__('public_procurement.fields.status'))->options(PublicProcurementContractResource::statuses()),
        ];
    }

    /**
     * @return array<string, Action>
     */
    public function getTableActions(): array
    {
        return [
            'generateSal' => Action::make('generateSal')
                ->label(__('public_procurement.actions.generate_sal'))
                ->icon('heroicon-o-document-chart-bar')
                ->color('success')
                ->action(function (PublicProcurementContract $record): void {
                    $next = (string) ($record->progressReports()->count() + 1);
                    app(GenerateProgressReportFromMeasurementsAction::class)->execute(
                        $record,
                        $next,
                        CarbonImmutable::now()->startOfMonth(),
                        CarbonImmutable::now()->endOfMonth(),
                    );
                    Notification::make()->title(__('public_procurement.messages.sal_created'))->success()->send();
                }),
            'view' => ViewAction::make(),
            'edit' => EditAction::make(),
        ];
    }

    /**
     * @return array<string, BulkAction>
     */
    public function getTableBulkActions(): array
    {
        return [
            'delete' => DeleteBulkAction::make(),
        ];
    }

    protected function getDefaultTableSortColumn(): string
    {
        return 'id';
    }

    protected function getDefaultTableSortDirection(): string
    {
        return 'desc';
    }
}
