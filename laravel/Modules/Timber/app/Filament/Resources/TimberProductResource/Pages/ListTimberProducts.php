<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberProductResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Modules\Timber\Filament\Resources\TimberProductResource;
use Modules\Timber\Support\TimberConfigMap;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListTimberProducts extends XotBaseListRecords
{
    protected static string $resource = TimberProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => CreateAction::make(),
        ];
    }

    /** @return array<string, Column> */
    public function getTableColumns(): array
    {
        return [
            'category' => Tables\Columns\TextColumn::make('category')
                ->label('Categoria')
                ->toggleable()
                ->sortable(),
            'code' => Tables\Columns\TextColumn::make('code')
                ->label('Codice')
                ->searchable()
                ->sortable(),
            'description' => Tables\Columns\TextColumn::make('description')
                ->label('Descrizione')
                ->searchable()
                ->wrap(),
            'unit_of_measure' => Tables\Columns\TextColumn::make('unit_of_measure')
                ->label('U.m.')
                ->toggleable(),
            'product_type_code' => Tables\Columns\TextColumn::make('product_type_code')
                ->label('Tipologia')
                ->formatStateUsing(fn (?string $state): string => TimberConfigMap::get('timber.product_types')[$state] ?? (string) $state)
                ->toggleable(),
            'is_active' => Tables\Columns\IconColumn::make('is_active')
                ->label('Attivo')
                ->boolean(),
        ];
    }

    /** @return array<string, BaseFilter> */
    public function getTableFilters(): array
    {
        return [
            'product_type_code' => Tables\Filters\SelectFilter::make('product_type_code')
                ->label('Tipologia')
                ->options(TimberConfigMap::get('timber.product_types')),
        ];
    }

    /** @return array<string, Action|ActionGroup> */
    public function getTableActions(): array
    {
        return [
            'view' => ViewAction::make(),
            'edit' => EditAction::make(),
        ];
    }
}
