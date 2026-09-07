<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignPriceListItemResource\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Modules\Costing\Models\WorkCenter;
use Modules\Signage\Filament\Resources\SignPriceListItemResource;
use Modules\Signage\Support\SignageCosting;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class SignPriceListItemsTable extends XotBaseResourceTable
{
    protected static string $resource = SignPriceListItemResource::class;

    /**
     * @return array<string, TextColumn|IconColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'code' => TextColumn::make('code')->searchable()->sortable(),
            'name' => TextColumn::make('name')->searchable()->limit(50)->wrap(),
            'category_code' => TextColumn::make('category_code')
                ->formatStateUsing(fn (string $state): string => __('signage::signage.categories.'.$state))
                ->badge()
                ->sortable(),
            'work_center_code' => TextColumn::make('work_center_code')
                ->placeholder('—')
                ->badge()
                ->sortable(),
            'supplier_name' => TextColumn::make('supplier_name')->placeholder('—')->toggleable(),
            'unit_code' => TextColumn::make('unit_code'),
            'unit_cost' => TextColumn::make('unit_cost')->money('EUR', divideBy: 1)->sortable(),
            'is_active' => IconColumn::make('is_active')->boolean()->toggleable(),
        ];
    }

    /**
     * @return array<int|string, SelectFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'category_code' => SelectFilter::make('category_code')
                ->options(function (): array {
                    /** @var array<string, string> $categories */
                    $categories = (array) __('signage::signage.categories');

                    return $categories;
                }),
            'work_center_code' => SelectFilter::make('work_center_code')
                ->options(fn (): array => WorkCenter::query()
                    ->forContext(SignageCosting::CONTEXT)
                    ->orderBy('sort_order')
                    ->pluck('name', 'code')
                    ->all()),
        ];
    }

    /**
     * @return array<string, ViewAction|EditAction|DeleteAction>
     */
    public function getTableActions(): array
    {
        return [
            'view' => ViewAction::make(),
            'edit' => EditAction::make(),
            'delete' => DeleteAction::make(),
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

    public function getTableRecordTitleAttribute(): string
    {
        return 'name';
    }
}
