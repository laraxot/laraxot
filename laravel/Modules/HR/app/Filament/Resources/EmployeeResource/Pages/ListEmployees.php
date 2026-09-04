<?php

declare(strict_types=1);

namespace Modules\HR\Filament\Resources\EmployeeResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Modules\HR\Filament\Resources\EmployeeResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListEmployees extends XotBaseListRecords
{
    protected static string $resource = EmployeeResource::class;

    /**
     * @return array<string, Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            'create' => Actions\CreateAction::make(),
        ];
    }

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'last_name' => Tables\Columns\TextColumn::make('last_name')
                ->label(__('hr.fields.last_name'))
                ->searchable()
                ->sortable(),
            'first_name' => Tables\Columns\TextColumn::make('first_name')
                ->label(__('hr.fields.first_name'))
                ->searchable()
                ->sortable(),
            'email' => Tables\Columns\TextColumn::make('email')
                ->label(__('hr.fields.email'))
                ->toggleable()
                ->searchable(),
            'is_active' => Tables\Columns\IconColumn::make('is_active')
                ->label(__('hr.fields.is_active'))
                ->boolean()
                ->sortable(),
            'updated_at' => Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    /**
     * @return array<string, Action>
     */
    public function getTableActions(): array
    {
        return [
            'view' => ViewAction::make(),
            'edit' => EditAction::make(),
        ];
    }

    /**
     * @return array<string, BulkAction>
     */
    public function getTableBulkActions(): array
    {
        return [
            'deleteBulk' => DeleteBulkAction::make(),
            'restoreBulk' => RestoreBulkAction::make(),
            'forceDeleteBulk' => ForceDeleteBulkAction::make(),
        ];
    }
}
