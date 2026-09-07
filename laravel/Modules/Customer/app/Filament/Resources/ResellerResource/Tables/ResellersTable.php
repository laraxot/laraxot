<?php

declare(strict_types=1);

namespace Modules\Customer\Filament\Resources\ResellerResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Modules\Customer\Filament\Resources\ResellerResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class ResellersTable extends XotBaseResourceTable
{
    protected static string $resource = ResellerResource::class;

    /**
     * @return array<string, TextColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'name' => TextColumn::make('name')
                ->label(__('resellers.fields.name'))
                ->searchable()
                ->sortable(),
            'user.name' => TextColumn::make('user.name')
                ->label(__('resellers.fields.user'))
                ->toggleable()
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
