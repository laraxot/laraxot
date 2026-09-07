<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\EquipmentResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Modules\Compliance\Filament\Resources\EquipmentResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class EquipmentsTable extends XotBaseResourceTable
{
    protected static string $resource = EquipmentResource::class;

    /**
     * @return array<string, TextColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'name' => TextColumn::make('name')->searchable()->sortable(),
            'type' => TextColumn::make('type')->searchable()->sortable(),
            'serial_number' => TextColumn::make('serial_number')->toggleable(),
            'status' => TextColumn::make('status')->badge()->sortable(),
            'next_check_due_on' => TextColumn::make('next_check_due_on')->date()->sortable(),
            'assigned_work_order_id' => TextColumn::make('assignedWorkOrder.title')->limit(40)->toggleable(),
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
