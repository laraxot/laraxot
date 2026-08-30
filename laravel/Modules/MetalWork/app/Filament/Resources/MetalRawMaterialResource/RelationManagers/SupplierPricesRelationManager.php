<?php

declare(strict_types=1);

namespace Modules\MetalWork\Filament\Resources\MetalRawMaterialResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * What was actually paid for this material, newest first — the estimate reads
 * the top row.
 */
final class SupplierPricesRelationManager extends RelationManager
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
                Tables\Columns\TextColumn::make('recorded_on')->label(__('metalwork::metalwork.fields.recorded_on'))->date('d/m/Y')->sortable(),
                Tables\Columns\TextColumn::make('supplier_name')->label(__('metalwork::metalwork.fields.supplier_name'))->searchable(),
                Tables\Columns\TextColumn::make('invoice_number')->label(__('metalwork::metalwork.fields.invoice_number'))->placeholder('—')->searchable(),
                Tables\Columns\TextColumn::make('delivery_note_number')->label(__('metalwork::metalwork.fields.delivery_note_number'))->placeholder('—'),
                Tables\Columns\TextColumn::make('quantity')->label(__('metalwork::metalwork.fields.quantity'))->numeric(2),
                Tables\Columns\TextColumn::make('unit_price')->label(__('metalwork::metalwork.fields.unit_price'))->numeric(4)->suffix(' €'),
                Tables\Columns\TextColumn::make('line_total')->label(__('metalwork::metalwork.fields.line_total'))->numeric(2)->suffix(' €'),
                Tables\Columns\TextColumn::make('source_file')->label(__('metalwork::metalwork.fields.source_file'))->placeholder('—')->toggleable(isToggledHiddenByDefault: true),
            ]);
    }
}
