<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignCatalogItemResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Modules\Signage\Filament\Resources\SignCatalogItemResource;
use Modules\Signage\Models\SignCatalogItem;
use Modules\Signage\Models\SignPriceList;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListSignCatalogItems extends XotBaseListRecords
{
    protected static string $resource = SignCatalogItemResource::class;

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
            'code' => Tables\Columns\TextColumn::make('code')->label(__('signage::signage.fields.code'))->searchable()->sortable(),
            'priceList.name' => Tables\Columns\TextColumn::make('priceList.name')->label(__('signage::signage.catalog_fields.price_list'))->searchable()->sortable(),
            'name' => Tables\Columns\TextColumn::make('name')->label(__('signage::signage.fields.name'))->searchable()->limit(50)->wrap(),
            'letter_height_min_mm' => Tables\Columns\TextColumn::make('letter_height_min_mm')
                ->label(__('signage::signage.catalog_fields.letter_height'))
                ->formatStateUsing(fn (SignCatalogItem $record): string => $record->letter_height_min_mm === null
                    ? '—'
                    : $record->letter_height_min_mm.'–'.$record->letter_height_max_mm.' mm')
                ->placeholder('—'),
            'led_temperature_code' => Tables\Columns\TextColumn::make('led_temperature_code')->label(__('signage::signage.catalog_fields.led_temperature_code'))->placeholder('—'),
            'components_count' => Tables\Columns\TextColumn::make('components_count')->label(__('signage::signage.components'))->counts('components'),
            'manual_sale_price' => Tables\Columns\TextColumn::make('manual_sale_price')
                ->label(__('signage::signage.catalog_fields.manual_sale_price'))
                ->money('EUR')
                ->placeholder('—'),
            'is_active' => Tables\Columns\IconColumn::make('is_active')->label(__('signage::signage.fields.is_active'))->boolean()->toggleable(),
        ];
    }

    public function getDefaultTableSortColumn(): string
    {
        return 'sort_order';
    }

    public function getDefaultTableSortDirection(): string
    {
        return 'asc';
    }

    /**
     * @return array<string, BaseFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'sign_price_list_id' => Tables\Filters\SelectFilter::make('sign_price_list_id')
                ->label(__('signage::signage.catalog_fields.price_list'))
                ->options(fn (): array => SignPriceList::query()->orderBy('code')->pluck('name', 'id')->all()),
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
            'replicate' => ReplicateAction::make()
                    // components_count rides along from the table's counts()
                    // aggregate and is not a column, so it must not be copied.
                ->excludeAttributes(['code', 'components_count'])
                ->beforeReplicaSaved(function (SignCatalogItem $replica, SignCatalogItem $record): void {
                    $replica->code = $record->code.'-COPY';
                })
                ->after(function (SignCatalogItem $replica, SignCatalogItem $record): void {
                    foreach ($record->components as $component) {
                        $copy = $component->replicate();
                        $copy->sign_catalog_item_id = $replica->id;
                        $copy->save();
                    }
                }),
            'delete' => DeleteAction::make(),
        ];
    }
}
