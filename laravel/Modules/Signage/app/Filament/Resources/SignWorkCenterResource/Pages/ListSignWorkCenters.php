<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignWorkCenterResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Modules\Signage\Filament\Resources\SignWorkCenterResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListSignWorkCenters extends XotBaseListRecords
{
    protected static string $resource = SignWorkCenterResource::class;

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
            'code' => Tables\Columns\TextColumn::make('code')->searchable()->sortable(),
            'name' => Tables\Columns\TextColumn::make('name')->searchable(),
            'default_markup_percent' => Tables\Columns\TextColumn::make('default_markup_percent')
                ->placeholder('—')
                ->suffix('%')
                ->sortable(),
            'is_internal' => Tables\Columns\IconColumn::make('is_internal')->boolean(),
            'is_active' => Tables\Columns\IconColumn::make('is_active')->boolean(),
        ];
    }

    public function getDefaultTableSortColumn(): string
    {
        return 'sort_order';
    }

    public function getDefaultTableSortDirection(): string
    {
        return 'asc';
    }

    /**
     * @return array<string, Action>
     */
    public function getTableActions(): array
    {
        return [
            'edit' => EditAction::make(),
            'delete' => DeleteAction::make(),
        ];
    }
}
