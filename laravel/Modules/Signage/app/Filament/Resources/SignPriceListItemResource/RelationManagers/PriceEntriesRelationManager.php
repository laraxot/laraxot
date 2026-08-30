<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignPriceListItemResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

/**
 * What was actually paid for this article, invoice by invoice, so the cost in
 * the list can be checked against reality. Filled by hand for now — whether it
 * should come from the purchase invoices is still open with the customer.
 */
final class PriceEntriesRelationManager extends RelationManager
{
    protected static string $relationship = 'priceEntries';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return (string) __('signage::signage.supplier_price_entries');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Grid::make(3)->components([
                TextInput::make('invoice_number')->label(__('signage::signage.catalog_fields.invoice_number')),
                DatePicker::make('recorded_on')->label(__('signage::signage.catalog_fields.recorded_on'))->required(),
                TextInput::make('amount')->label(__('signage::signage.catalog_fields.amount'))->numeric()->required(),
            ]),
            Textarea::make('notes')->label(__('signage::signage.fields.notes'))->rows(2)->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('recorded_on', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')->label(__('signage::signage.catalog_fields.invoice_number'))->placeholder('—'),
                Tables\Columns\TextColumn::make('recorded_on')->label(__('signage::signage.catalog_fields.recorded_on'))->date()->sortable(),
                Tables\Columns\TextColumn::make('amount')->label(__('signage::signage.catalog_fields.amount'))->money('EUR')->sortable(),
                Tables\Columns\TextColumn::make('notes')->label(__('signage::signage.fields.notes'))->placeholder('—')->limit(40),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
