<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources\MetalRawMaterialResource\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Modules\MetalWork\Filament\Resources\MetalRawMaterialResource;
use Modules\MetalWork\Models\MetalRawMaterial;
use Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable;

final class MetalRawMaterialsTable extends XotBaseResourceTable
{
    protected static string $resource = MetalRawMaterialResource::class;

    /**
     * @return array<string, TextColumn>
     */
    public function getTableColumns(): array
    {
        return [
            'code' => TextColumn::make('code')->searchable()->sortable(),
            'description' => TextColumn::make('description')->searchable()->wrap(),
            'family_code' => TextColumn::make('family_code')
                ->formatStateUsing(fn (?string $state): string => (string) __('metalwork::metalwork.families.'.($state ?? 'other'))),
            'steel_grade' => TextColumn::make('steel_grade')->placeholder('—'),
            'unit_code' => TextColumn::make('unit_code'),
            'current_price' => TextColumn::make('current_price')
                ->placeholder('—')
                ->state(fn (MetalRawMaterial $record): ?string => $record->currentUnitPrice() === null
                    ? null
                    : number_format($record->currentUnitPrice(), 4, ',', '.').' EUR/'.$record->unit_code),
            'supplier_name' => TextColumn::make('supplier_name')->placeholder('—')->searchable(),
            'supplier_prices_count' => TextColumn::make('supplier_prices_count')
                ->counts('supplierPrices'),
        ];
    }

    /**
     * @return array<string, SelectFilter>
     */
    public function getTableFilters(): array
    {
        return [
            'family_code' => SelectFilter::make('family_code')->options(MetalRawMaterialResource::familyOptions()),
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
        return 'code';
    }

    protected function getDefaultTableSortDirection(): string
    {
        return 'asc';
    }
}
