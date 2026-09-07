<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources\MetalWorkCenterResource\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Modules\MetalWork\Filament\Resources\MetalWorkCenterResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class MetalWorkCentersTable extends XotBaseResourceTable
{
    protected static string $resource = MetalWorkCenterResource::class;

    /**
     * @return array<string, TextColumn|IconColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'code' => TextColumn::make('code')->searchable()->sortable(),
            'name' => TextColumn::make('name')->searchable(),
            'hourly_rate' => TextColumn::make('hourly_rate')
                ->numeric(2)
                ->suffix(' EUR/h')
                ->sortable(),
            'default_markup_percent' => TextColumn::make('default_markup_percent')
                ->placeholder('—')
                ->suffix('%'),
            'is_internal' => IconColumn::make('is_internal')->boolean(),
            'is_active' => IconColumn::make('is_active')->boolean(),
        ];
    }

    /**
     * @return array<string, EditAction|DeleteAction>
     */
    public function getTableActions(): array
    {
        return [
            'edit' => EditAction::make(),
            'delete' => DeleteAction::make(),
        ];
    }

    protected function getDefaultTableSortColumn(): string
    {
        return 'sort_order';
    }

    protected function getDefaultTableSortDirection(): string
    {
        return 'asc';
    }
}
