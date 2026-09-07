<?php

declare(strict_types=1);

namespace Modules\Inventory\Filament\Resources\InventoryMovementResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Modules\Inventory\Filament\Resources\InventoryMovementResource;
use Modules\Inventory\Filament\Resources\InventoryMovementResource\Schemas\InventoryMovementForm;
use Modules\Inventory\Models\InventoryMovement;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

/**
 * Table per InventoryMovementResource.
 *
 * NON ridefinisce table() — ereditato da HasXotTable.php:220.
 * Override consentiti: getTableColumns, getTableFilters, getTableActions, getTableBulkActions.
 */
final class InventoryMovementsTable extends XotBaseResourceTable
{
    protected static string $resource = InventoryMovementResource::class;

    /**
     * @return array<string, TextColumn>
     */
    public function getTableColumns(): array
    {
        $columns = [
            'id' => TextColumn::make('id')->label('#')->sortable(),
            'movement_type' => TextColumn::make('movement_type')->label(__('inventory.fields.movement_type'))->sortable(),
            'product.name' => TextColumn::make('product.name')->label(__('inventory.fields.product'))->searchable()->sortable(),
            'quantity' => TextColumn::make('quantity')->label(__('inventory.fields.quantity'))->sortable(),
            'unit_price' => TextColumn::make('unit_price')->label(__('inventory.fields.unit_price'))->money('EUR')->sortable(),
            'line_total' => TextColumn::make('line_total')->label(__('inventory.fields.line_total'))->money('EUR')->sortable(),
            'fromWarehouse.name' => TextColumn::make('fromWarehouse.name')->label(__('inventory.fields.from'))->toggleable(),
            'toWarehouse.name' => TextColumn::make('toWarehouse.name')->label(__('inventory.fields.to'))->toggleable(),
        ];

        if (InventoryMovementForm::storageZonesEnabled()) {
            $columns['fromLocation.code'] = TextColumn::make('fromLocation.code')
                ->label(__('inventory.fields.from_location'))
                ->placeholder('—')
                ->toggleable();
            $columns['toLocation.code'] = TextColumn::make('toLocation.code')
                ->label(__('inventory.fields.to_location'))
                ->placeholder('—')
                ->toggleable();
        }

        $columns['workOrder.code'] = TextColumn::make('workOrder.code')
            ->label(__('inventory.fields.work_order'))
            ->formatStateUsing(fn ($state, InventoryMovement $record): string => InventoryMovementForm::workOrderLabel($record->workOrder))
            ->toggleable(isToggledHiddenByDefault: true);
        $columns['intervention_id'] = TextColumn::make('intervention_id')
            ->label(__('inventory.fields.intervention'))
            ->formatStateUsing(fn ($state, InventoryMovement $record): string => InventoryMovementForm::interventionLabel($record->intervention))
            ->toggleable(isToggledHiddenByDefault: true);
        $columns['occurred_at'] = TextColumn::make('occurred_at')->label(__('inventory.fields.date'))->dateTime()->sortable();
        $columns['created_at'] = TextColumn::make('created_at')->dateTime()->toggleable(isToggledHiddenByDefault: true);

        return $columns;
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
