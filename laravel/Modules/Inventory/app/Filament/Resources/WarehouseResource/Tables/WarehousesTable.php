<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Resources\WarehouseResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Modules\Inventory\Filament\Resources\WarehouseResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

/**
 * Table per WarehouseResource.
 *
 * NON ridefinisce table() — ereditato da HasXotTable.php:220.
 * Override consentiti: getTableColumns, getTableFilters, getTableActions, getTableBulkActions.
 */
final class WarehousesTable extends XotBaseResourceTable
{
    protected static string $resource = WarehouseResource::class;

    /**
     * @return array<string, TextColumn|IconColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'name' => TextColumn::make('name')->label(__('inventory.fields.name'))->searchable()->sortable(),
            'code' => TextColumn::make('code')->label(__('inventory.fields.code'))->toggleable()->sortable(),
            'vehicle.plate_number' => TextColumn::make('vehicle.plate_number')
                ->label(__('inventory.fields.vehicle'))
                ->placeholder('—')
                ->toggleable(isToggledHiddenByDefault: true),
            'is_active' => IconColumn::make('is_active')->label(__('inventory.fields.is_active'))->boolean()->sortable(),
            'is_final_destination' => IconColumn::make('is_final_destination')->label(__('inventory.fields.final'))->boolean()->toggleable(),
            'updated_at' => TextColumn::make('updated_at')->dateTime()->toggleable(isToggledHiddenByDefault: true),
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
}
