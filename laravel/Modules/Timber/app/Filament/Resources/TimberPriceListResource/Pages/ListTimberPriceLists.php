<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberPriceListResource\Pages;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Modules\Timber\Filament\Resources\TimberPriceListResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListTimberPriceLists extends XotBaseListRecords
{
    protected static string $resource = TimberPriceListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => CreateAction::make()->label('Nuovo listino'),
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
            'items_count' => Tables\Columns\TextColumn::make('items_count')
                ->label('Prezzi')
                ->counts('items'),
            'versions_count' => Tables\Columns\TextColumn::make('versions_count')
                ->label('Versioni')
                ->counts('versions')
                ->toggleable(),
            'is_active' => Tables\Columns\IconColumn::make('is_active')
                ->label('Attivo')
                ->boolean(),
        ];
    }

    /** @return array<string, Action|ActionGroup> */
    public function getTableActions(): array
    {
        return [
            'exportcsv' => TimberPriceListResource::exportCsvAction(),
            'importcsv' => TimberPriceListResource::importCsvAction(),
            'edit' => EditAction::make(),
        ];
    }
}
