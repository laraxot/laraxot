<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignCatalogItemResource\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Modules\Signage\Filament\Resources\SignCatalogItemResource;
use Modules\Signage\Models\SignCatalogItem;
use Modules\Signage\Models\SignPriceList;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

/**
 * Tabella per {@see SignCatalogItemResource}.
 *
 * Replica con copia dei componenti correlati: la colonna `components_count`
 * è un aggregato, non un attributo, e va esclusa da `excludeAttributes()`.
 */
final class SignCatalogItemsTable extends XotBaseResourceTable
{
    protected static string $resource = SignCatalogItemResource::class;

    /**
     * @return array<string, TextColumn|IconColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'code' => TextColumn::make('code')
                ->searchable()
                ->sortable(),
            'priceList.name' => TextColumn::make('priceList.name')
                ->searchable()
                ->sortable(),
            'name' => TextColumn::make('name')
                ->searchable()
                ->limit(50)
                ->wrap(),
            'letter_height_min_mm' => TextColumn::make('letter_height_min_mm')
                ->formatStateUsing(fn (SignCatalogItem $record): string => $record->letter_height_min_mm === null
                    ? '—'
                    : $record->letter_height_min_mm.'–'.$record->letter_height_max_mm.' mm')
                ->placeholder('—'),
            'led_temperature_code' => TextColumn::make('led_temperature_code')
                ->placeholder('—'),
            'components_count' => TextColumn::make('components_count')
                ->counts('components'),
            'manual_sale_price' => TextColumn::make('manual_sale_price')
                ->money('EUR')
                ->placeholder('—'),
            'is_active' => IconColumn::make('is_active')
                ->boolean()
                ->toggleable(),
        ];
    }

    /**
     * @return array<int|string, SelectFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'sign_price_list_id' => SelectFilter::make('sign_price_list_id')
                ->options(fn (): array => SignPriceList::query()->orderBy('code')->pluck('name', 'id')->all()),
        ];
    }

    /**
     * @return array<string, ViewAction|EditAction|ReplicateAction|DeleteAction>
     */
    public function getTableActions(): array
    {
        return [
            'view' => ViewAction::make(),
            'edit' => EditAction::make(),
            'replicate' => ReplicateAction::make()
                // components_count rides along from the table's counts()
                // aggregate and is not a column, so it must not be copied.
                ->excludeAttributes(['code', 'components_count'])
                ->beforeReplicaSaved(function (SignCatalogItem $replica, SignCatalogItem $record): void {
                    $replica->code = $record->code.'-COPY';
                })
                ->after(function (SignCatalogItem $replica, SignCatalogItem $record): void {
                    foreach ($record->components as $component) {
                        $copy = $component->replicate();
                        $copy->sign_catalog_item_id = $replica->id;
                        $copy->save();
                    }
                }),
            'delete' => DeleteAction::make(),
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

    protected function getDefaultTableSortColumn(): string
    {
        return 'sort_order';
    }

    public function getTableRecordTitleAttribute(): string
    {
        return 'name';
    }
}
