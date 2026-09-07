<?php

declare(strict_types=1);

namespace Modules\HR\Filament\Resources\EmployeeResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Modules\HR\Filament\Resources\EmployeeResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class EmployeesTable extends XotBaseResourceTable
{
    protected static string $resource = EmployeeResource::class;

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'last_name' => TextColumn::make('last_name')
                ->label(__('hr.fields.last_name'))
                ->searchable()
                ->sortable(),
            'first_name' => TextColumn::make('first_name')
                ->label(__('hr.fields.first_name'))
                ->searchable()
                ->sortable(),
            'email' => TextColumn::make('email')
                ->label(__('hr.fields.email'))
                ->toggleable()
                ->searchable(),
            'is_active' => IconColumn::make('is_active')
                ->label(__('hr.fields.is_active'))
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
}
