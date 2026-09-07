<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\ProductResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Modules\Catalog\Filament\Resources\ProductResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class ProductsTable extends XotBaseResourceTable
{
    protected static string $resource = ProductResource::class;

    /**
     * @return array<string, TextColumn|IconColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'name' => TextColumn::make('name')
                ->label(__('catalog.fields.name'))
                ->searchable()
                ->sortable(),
            'sku' => TextColumn::make('sku')
                ->label(__('catalog.fields.sku'))
                ->toggleable()
                ->searchable(),
            'unit_price' => TextColumn::make('unit_price')
                ->label(__('catalog.fields.unit_price'))
                ->money('EUR')
                ->sortable(),
            'is_active' => IconColumn::make('is_active')
                ->label(__('catalog.fields.is_active'))
                ->boolean()
                ->sortable(),
            'updated_at' => TextColumn::make('updated_at')
                ->dateTime()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    /**
     * @return array<int|string, TernaryFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'is_active' => TernaryFilter::make('is_active'),
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
     * Product usa SoftDeletes, quindi servono Restore/ForceDelete bulk.
     *
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

    public function getTableRecordTitleAttribute(): string
    {
        return 'name';
    }
}
