<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberAgentResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Modules\Timber\Filament\Resources\TimberAgentResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class TimberAgentsTable extends XotBaseResourceTable
{
    protected static string $resource = TimberAgentResource::class;

    /**
     * @return array<string, TextColumn|IconColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'full_name' => TextColumn::make('full_name')->searchable()->sortable(),
            'email' => TextColumn::make('email')->searchable()->toggleable(),
            'phone' => TextColumn::make('phone')->toggleable(),
            'zone' => TextColumn::make('zone')->toggleable(),
            'commission_percent' => TextColumn::make('commission_percent')->suffix('%'),
            'is_active' => IconColumn::make('is_active')->boolean(),
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
