<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\WorkOrderChangeOrderResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Modules\Compliance\Filament\Resources\WorkOrderChangeOrderResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class WorkOrderChangeOrdersTable extends XotBaseResourceTable
{
    protected static string $resource = WorkOrderChangeOrderResource::class;

    /**
     * @return array<string, TextColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'workOrder.title' => TextColumn::make('workOrder.title')->limit(40)->searchable(),
            'title' => TextColumn::make('title')->searchable()->sortable(),
            'estimated_amount' => TextColumn::make('estimated_amount')->money('EUR')->sortable(),
            'status' => TextColumn::make('status')->badge()->sortable(),
        ];
    }

    /**
     * @return array<string, SelectFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'status' => SelectFilter::make('status')->options(WorkOrderChangeOrderResource::statuses()),
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
