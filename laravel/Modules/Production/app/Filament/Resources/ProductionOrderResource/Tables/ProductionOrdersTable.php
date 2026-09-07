<?php

declare(strict_types=1);

namespace Modules\Production\Filament\Resources\ProductionOrderResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\TextColumn;
use Modules\Production\Filament\Resources\ProductionOrderResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class ProductionOrdersTable extends XotBaseResourceTable
{
    protected static string $resource = ProductionOrderResource::class;

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'id' => TextColumn::make('id')->label('#')->sortable(),
            'code' => TextColumn::make('code')->label(__('production.fields.code'))->searchable(),
            'product.name' => TextColumn::make('product.name')->label(__('production.fields.product'))->searchable()->sortable(),
            'quantity' => TextColumn::make('quantity')->label(__('production.fields.quantity'))->sortable(),
            'status' => TextColumn::make('status')->label(__('production.fields.status'))->sortable(),
            'workOrder.code' => TextColumn::make('workOrder.code')->label(__('production.fields.work_order'))->toggleable(isToggledHiddenByDefault: true),
            'due_at' => TextColumn::make('due_at')->label(__('production.fields.due_at'))->dateTime()->toggleable(),
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

    protected function getDefaultTableSortColumn(): string
    {
        return 'id';
    }

    protected function getDefaultTableSortDirection(): string
    {
        return 'desc';
    }
}
