<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources\MetalRawMaterialResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Filament\Tables\Filters\BaseFilter;
use Modules\MetalWork\Filament\Resources\MetalRawMaterialResource;
use Modules\MetalWork\Models\MetalRawMaterial;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListMetalRawMaterials extends XotBaseListRecords
{
    protected static string $resource = MetalRawMaterialResource::class;

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
            'description' => Tables\Columns\TextColumn::make('description')->searchable()->wrap(),
            'family_code' => Tables\Columns\TextColumn::make('family_code')
                ->formatStateUsing(fn (?string $state): string => (string) __('metalwork::metalwork.families.'.($state ?? 'other'))),
            'steel_grade' => Tables\Columns\TextColumn::make('steel_grade')->placeholder('—'),
            'unit_code' => Tables\Columns\TextColumn::make('unit_code'),
            'current_price' => Tables\Columns\TextColumn::make('current_price')
                ->placeholder('—')
                ->state(fn (MetalRawMaterial $record): ?string => $record->currentUnitPrice() === null
                    ? null
                    : number_format($record->currentUnitPrice(), 4, ',', '.').' €/'.$record->unit_code),
            'supplier_name' => Tables\Columns\TextColumn::make('supplier_name')->placeholder('—')->searchable(),
            'supplier_prices_count' => Tables\Columns\TextColumn::make('supplier_prices_count')
                ->counts('supplierPrices'),
        ];
    }

    public function getDefaultTableSortColumn(): string
    {
        return 'code';
    }

    public function getDefaultTableSortDirection(): string
    {
        return 'asc';
    }

    /**
     * @return array<string, BaseFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'family_code' => Tables\Filters\SelectFilter::make('family_code')
                ->options(MetalRawMaterialResource::familyOptions()),
        ];
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
