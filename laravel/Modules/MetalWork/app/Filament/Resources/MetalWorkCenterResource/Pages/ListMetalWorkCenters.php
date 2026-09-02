<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources\MetalWorkCenterResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Modules\MetalWork\Filament\Resources\MetalWorkCenterResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListMetalWorkCenters extends XotBaseListRecords
{
    protected static string $resource = MetalWorkCenterResource::class;

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
            'code' => Tables\Columns\TextColumn::make('code')->label(__('metalwork::metalwork.fields.code'))->searchable()->sortable(),
            'name' => Tables\Columns\TextColumn::make('name')->label(__('metalwork::metalwork.fields.name'))->searchable(),
            'hourly_rate' => Tables\Columns\TextColumn::make('hourly_rate')
                ->label(__('metalwork::metalwork.fields.hourly_rate'))
                ->numeric(2)
                ->suffix(' €/h')
                ->sortable(),
            'default_markup_percent' => Tables\Columns\TextColumn::make('default_markup_percent')
                ->label(__('metalwork::metalwork.fields.default_markup_percent'))
                ->placeholder('—')
                ->suffix('%'),
            'is_internal' => Tables\Columns\IconColumn::make('is_internal')->label(__('metalwork::metalwork.fields.is_internal'))->boolean(),
            'is_active' => Tables\Columns\IconColumn::make('is_active')->label(__('metalwork::metalwork.fields.is_active'))->boolean(),
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
