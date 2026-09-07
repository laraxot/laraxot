<?php

declare(strict_types=1);

namespace Modules\Bom\Filament\Resources\BomResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Modules\Bom\Filament\Resources\BomResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class BomsTable extends XotBaseResourceTable
{
    protected static string $resource = BomResource::class;

    /**
     * @return array<string, TextColumn|IconColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'id' => TextColumn::make('id')->label('#')->sortable(),
            'product.name' => TextColumn::make('product.name')
                ->label(__('boms.fields.product_id'))
                ->searchable()
                ->sortable(),
            'name' => TextColumn::make('name')
                ->label(__('boms.fields.name'))
                ->toggleable(),
            'is_active' => IconColumn::make('is_active')
                ->label(__('boms.fields.is_active'))
                ->boolean()
                ->sortable(),
            'updated_at' => TextColumn::make('updated_at')
                ->dateTime()
                ->toggleable(isToggledHiddenByDefault: true),
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
     * @return array<string, DeleteBulkAction>
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
