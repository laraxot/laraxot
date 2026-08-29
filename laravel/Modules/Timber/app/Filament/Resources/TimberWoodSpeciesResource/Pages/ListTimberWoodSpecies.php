<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberWoodSpeciesResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Modules\Timber\Filament\Resources\TimberWoodSpeciesResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListTimberWoodSpecies extends XotBaseListRecords
{
    protected static string $resource = TimberWoodSpeciesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => CreateAction::make()->label('Nuova specie'),
        ];
    }

    /** @return array<string, Column> */
    public function getTableColumns(): array
    {
        return [
            'name' => Tables\Columns\TextColumn::make('name')
                ->label('Nome')
                ->searchable()
                ->sortable(),
            'code' => Tables\Columns\TextColumn::make('code')
                ->label('Codice')
                ->searchable(),
            'sort_order' => Tables\Columns\TextColumn::make('sort_order')
                ->label('Ordine')
                ->sortable(),
            'is_active' => Tables\Columns\IconColumn::make('is_active')
                ->label('Attiva')
                ->boolean(),
        ];
    }

    /** @return array<string, BaseFilter> */
    public function getTableFilters(): array
    {
        return [
            'is_active' => Tables\Filters\TernaryFilter::make('is_active')->label('Attiva'),
        ];
    }

    /** @return array<string, Action|ActionGroup> */
    public function getTableActions(): array
    {
        return [
            'edit' => EditAction::make(),
        ];
    }
}
