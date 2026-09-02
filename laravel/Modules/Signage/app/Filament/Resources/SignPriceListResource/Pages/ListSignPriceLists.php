<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignPriceListResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Modules\Signage\Filament\Resources\SignPriceListResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListSignPriceLists extends XotBaseListRecords
{
    protected static string $resource = SignPriceListResource::class;

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
            'name' => Tables\Columns\TextColumn::make('name')->label(__('signage::signage.fields.name'))->searchable(),
            'brand' => Tables\Columns\TextColumn::make('brand')->label(__('signage::signage.catalog_fields.brand'))->placeholder('—')->searchable(),
            'items_count' => Tables\Columns\TextColumn::make('items_count')
                ->label(__('signage::signage.catalog_items'))
                ->counts('items'),
            'default_markup_percent' => Tables\Columns\TextColumn::make('default_markup_percent')
                ->label(__('signage::signage.catalog_fields.default_markup_percent'))
                ->placeholder('—')
                ->suffix('%'),
            'is_active' => Tables\Columns\IconColumn::make('is_active')->label(__('signage::signage.fields.is_active'))->boolean(),
        ];
    }

    /**
     * @return array<string, Action>
     */
    public function getTableActions(): array
    {
        return [
            'edit' => EditAction::make(),
            'delete' => DeleteAction::make(),
        ];
    }
}
