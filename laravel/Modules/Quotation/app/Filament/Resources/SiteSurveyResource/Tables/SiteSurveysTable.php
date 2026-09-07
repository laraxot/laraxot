<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\SiteSurveyResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Modules\Quotation\Filament\Resources\SiteSurveyResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

/**
 * Table per SiteSurveyResource.
 *
 * NON ridefinisce table() — ereditato da HasXotTable.php:220.
 * Override consentiti: getTableColumns, getTableFilters, getTableActions, getTableBulkActions.
 */
final class SiteSurveysTable extends XotBaseResourceTable
{
    protected static string $resource = SiteSurveyResource::class;

    /**
     * @return array<string, TextColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'code' => TextColumn::make('code')
                ->weight(FontWeight::Bold)
                ->searchable()
                ->sortable(),
            'title' => TextColumn::make('title')
                ->limit(50)
                ->searchable(),
            'customer.legal_name' => TextColumn::make('customer.legal_name')
                ->searchable()
                ->sortable(),
            'status_code' => TextColumn::make('status_code')
                ->badge()
                ->color(fn (string $state): string => SiteSurveyResource::statusColor($state))
                ->formatStateUsing(fn (string $state): string => __("site_surveys.statuses.{$state}"))
                ->sortable(),
            'scheduled_at' => TextColumn::make('scheduled_at')
                ->dateTime('d/m/Y H:i')
                ->sortable(),
            'performed_at' => TextColumn::make('performed_at')
                ->dateTime('d/m/Y H:i')
                ->toggleable()
                ->sortable(),
            'media_count' => TextColumn::make('media_count')
                ->counts('media')
                ->badge()
                ->color(fn (mixed $state): string => is_numeric($state) && (int) $state > 0 ? 'success' : 'warning'),
        ];
    }

    /**
     * @return array<int|string, SelectFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'status_code' => SelectFilter::make('status_code')
                ->options(fn () => SiteSurveyResource::statusOptions()),
        ];
    }

    /**
     * @return array<string, ViewAction|EditAction>
     */
    public function getTableActions(): array
    {
        return [
            'view' => ViewAction::make(),
            'edit' => EditAction::make(),
        ];
    }

    /**
     * @return array<string, DeleteBulkAction|RestoreBulkAction|ForceDeleteBulkAction>
     */
    public function getTableBulkActions(): array
    {
        return [
            'delete' => DeleteBulkAction::make(),
            'restore' => RestoreBulkAction::make(),
            'forceDelete' => ForceDeleteBulkAction::make(),
        ];
    }

    protected function getDefaultTableSortColumn(): string
    {
        return 'scheduled_at';
    }
}
