<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignPriceListItemResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Modules\Costing\Models\WorkCenter;
use Modules\Signage\Filament\Resources\SignPriceListItemResource;
use Modules\Signage\Support\SignageCosting;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListSignPriceListItems extends XotBaseListRecords
{
    protected static string $resource = SignPriceListItemResource::class;

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
            'code' => Tables\Columns\TextColumn::make('code')->searchable()->sortable(),
            'name' => Tables\Columns\TextColumn::make('name')->searchable()->limit(50)->wrap(),
            'category_code' => Tables\Columns\TextColumn::make('category_code')
                ->formatStateUsing(fn (string $state): string => __('signage::signage.categories.'.$state))
                ->badge()
                ->sortable(),
            'work_center_code' => Tables\Columns\TextColumn::make('work_center_code')
                ->placeholder('—')
                ->badge()
                ->sortable(),
            'supplier_name' => Tables\Columns\TextColumn::make('supplier_name')->placeholder('—')->toggleable(),
            'unit_code' => Tables\Columns\TextColumn::make('unit_code'),
            'unit_cost' => Tables\Columns\TextColumn::make('unit_cost')->money('EUR', divideBy: 1)->sortable(),
            'is_active' => Tables\Columns\IconColumn::make('is_active')->boolean()->toggleable(),
        ];
    }

    /**
     * @return array<string, BaseFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'category_code' => Tables\Filters\SelectFilter::make('category_code')
                ->options(function (): array {
                    /** @var array<string, string> $categories */
                    $categories = (array) __('signage::signage.categories');

                    return $categories;
                }),
            'work_center_code' => Tables\Filters\SelectFilter::make('work_center_code')
                ->options(fn (): array => WorkCenter::query()
                    ->forContext(SignageCosting::CONTEXT)
                    ->orderBy('sort_order')
                    ->pluck('name', 'code')
                    ->all()),
        ];
    }
}
