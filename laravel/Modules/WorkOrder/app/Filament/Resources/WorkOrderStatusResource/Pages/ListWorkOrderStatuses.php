<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Resources\WorkOrderStatusResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Modules\WorkOrder\Filament\Resources\WorkOrderStatusResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListWorkOrderStatuses extends XotBaseListRecords
{
    protected static string $resource = WorkOrderStatusResource::class;

    /** @return array<string, Tables\Columns\Column> */
    public function getTableColumns(): array
    {
        return [
            'sort_order' => Tables\Columns\TextColumn::make('sort_order')
                ->label(__('workorder::work_orders.status_help.sort_order'))
                ->sortable(),
            'name' => Tables\Columns\TextColumn::make('name')
                ->label(__('workorder::work_orders.fields.title'))
                ->searchable(),
            'code' => Tables\Columns\TextColumn::make('code')
                ->label(__('workorder::work_orders.fields.code'))
                ->badge()
                ->searchable(),
            'is_terminal' => Tables\Columns\IconColumn::make('is_terminal')
                ->label(__('workorder::work_orders.status_help.is_terminal'))
                ->boolean(),
            'work_orders_count' => Tables\Columns\TextColumn::make('work_orders_count')
                ->label(__('workorder::work_orders.work_orders'))
                ->counts('workOrders'),
        ];
    }

    /** @return array<string, Action> */
    public function getTableActions(): array
    {
        return [
            'edit' => EditAction::make(),
        ];
    }

    /** @return array<string, Action> */
    protected function getHeaderActions(): array
    {
        return [
            'create' => CreateAction::make(),
        ];
    }
}
