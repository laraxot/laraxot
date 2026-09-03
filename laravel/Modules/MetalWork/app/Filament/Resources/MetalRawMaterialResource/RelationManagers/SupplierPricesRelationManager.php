<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources\MetalRawMaterialResource\RelationManagers;

use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

/**
 * What was actually paid for this material, newest first — the estimate reads
 * the top row.
 */
final class SupplierPricesRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'supplierPrices';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return (string) __('metalwork::metalwork.supplier_prices');
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('recorded_on', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('recorded_on')->date('d/m/Y')->sortable(),
                Tables\Columns\TextColumn::make('supplier_name')->searchable(),
                Tables\Columns\TextColumn::make('invoice_number')->placeholder('—')->searchable(),
                Tables\Columns\TextColumn::make('delivery_note_number')->placeholder('—'),
                Tables\Columns\TextColumn::make('quantity')->numeric(2),
                Tables\Columns\TextColumn::make('unit_price')->numeric(4)->suffix(' €'),
                Tables\Columns\TextColumn::make('line_total')->numeric(2)->suffix(' €'),
                Tables\Columns\TextColumn::make('source_file')->placeholder('—')->toggleable(isToggledHiddenByDefault: true),
            ]);
    }
}
