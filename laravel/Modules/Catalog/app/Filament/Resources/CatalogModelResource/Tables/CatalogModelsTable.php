<?php

declare(strict_types=1);

namespace Modules\Catalog\Filament\Resources\CatalogModelResource\Tables;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Modules\Catalog\Filament\Resources\CatalogModelResource;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class CatalogModelsTable extends XotBaseResourceTable
{
    protected static string $resource = CatalogModelResource::class;

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
            'updated_at' => TextColumn::make('updated_at')
                ->dateTime()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    /**
     * @return array<int|string, TernaryFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'is_active' => TernaryFilter::make('is_active'),
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

    public function getTableRecordTitleAttribute(): string
    {
        return 'name';
    }
}
