<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Resources\WarehouseLocationResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Modules\Inventory\Filament\Resources\WarehouseLocationResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

/**
 * Table per WarehouseLocationResource.
 *
 * NON ridefinisce table() — ereditato da HasXotTable.php:220.
 * Override consentiti: getTableColumns, getTableFilters, getTableActions, getTableBulkActions.
 */
final class WarehouseLocationsTable extends XotBaseResourceTable
{
    protected static string $resource = WarehouseLocationResource::class;

    /**
     * @return array<string, TextColumn|IconColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'warehouse.name' => TextColumn::make('warehouse.name')
                ->label(__('inventory.fields.warehouse'))
                ->searchable()
                ->sortable(),
            'code' => TextColumn::make('code')
                ->label(__('inventory.fields.code'))
                ->searchable()
                ->sortable(),
            'label' => TextColumn::make('label')
                ->label(__('inventory.fields.label'))
                ->placeholder('—')
                ->toggleable(),
            'row' => TextColumn::make('row')
                ->label(__('inventory.fields.row'))
                ->sortable()
                ->toggleable(),
            'column' => TextColumn::make('column')
                ->label(__('inventory.fields.column'))
                ->sortable()
                ->toggleable(),
            'level' => TextColumn::make('level')
                ->label(__('inventory.fields.level'))
                ->sortable()
                ->toggleable(),
            'is_active' => IconColumn::make('is_active')
                ->label(__('inventory.fields.is_active'))
                ->boolean()
                ->sortable(),
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

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'warehouse_id';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'asc';
    }
}
