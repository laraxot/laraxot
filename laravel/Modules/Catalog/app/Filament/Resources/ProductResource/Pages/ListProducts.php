<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\ProductResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Modules\Catalog\Filament\Resources\ProductResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListProducts extends XotBaseListRecords
{
    protected static string $resource = ProductResource::class;

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
            'name' => Tables\Columns\TextColumn::make('name')
                ->label(__('catalog.fields.name'))
                ->searchable()
                ->sortable(),
            'sku' => Tables\Columns\TextColumn::make('sku')
                ->label(__('catalog.fields.sku'))
                ->toggleable()
                ->searchable(),
            'unit_price' => Tables\Columns\TextColumn::make('unit_price')
                ->label(__('catalog.fields.unit_price'))
                ->money('EUR')
                ->sortable(),
            'is_active' => Tables\Columns\IconColumn::make('is_active')
                ->label(__('catalog.fields.is_active'))
                ->boolean()
                ->sortable(),
            'updated_at' => Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->toggleable(isToggledHiddenByDefault: true),
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
        ];
    }

    /**
     * @return array<string, BulkAction>
     */
    public function getTableBulkActions(): array
    {
        return [
            'deleteBulk' => DeleteBulkAction::make(),
            'restoreBulk' => RestoreBulkAction::make(),
            'forceDeleteBulk' => ForceDeleteBulkAction::make(),
        ];
    }
}
