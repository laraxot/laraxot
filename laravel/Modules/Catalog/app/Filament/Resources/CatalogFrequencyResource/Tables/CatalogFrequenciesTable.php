<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\CatalogFrequencyResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Modules\Catalog\Filament\Resources\CatalogFrequencyResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class CatalogFrequenciesTable extends XotBaseResourceTable
{
    protected static string $resource = CatalogFrequencyResource::class;

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

    /**
     * @return array<string, TextColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'name' => TextColumn::make('name')
                ->label(__('catalog.fields.name'))
                ->searchable()
                ->sortable(),
            'sort_order' => TextColumn::make('sort_order')
                ->label(__('catalog.fields.sort_order'))
                ->numeric()
                ->sortable(),
            'updated_at' => TextColumn::make('updated_at')
                ->dateTime()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }
}
