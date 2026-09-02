<?php

declare(strict_types=1);

namespace Modules\Signage\Filament\Resources\SignPriceListItemResource\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Tables;
use Filament\Tables\Columns\Column;
use Illuminate\Database\Eloquent\Model;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

/**
 * What was actually paid for this article, invoice by invoice, so the cost in
 * the list can be checked against reality. Filled by hand for now — whether it
 * should come from the purchase invoices is still open with the customer.
 */
final class PriceEntriesRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'priceEntries';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return (string) __('signage::signage.supplier_price_entries');
    }

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'invoice' => Grid::make(3)->components([
                TextInput::make('invoice_number')->label(__('signage::signage.catalog_fields.invoice_number')),
                DatePicker::make('recorded_on')->label(__('signage::signage.catalog_fields.recorded_on'))->required(),
                TextInput::make('amount')->label(__('signage::signage.catalog_fields.amount'))->numeric()->required(),
            ]),
            'notes' => Textarea::make('notes')->label(__('signage::signage.fields.notes'))->rows(2)->columnSpanFull(),
        ];
    }

    /**
     * @return array<string, Column>
     */
    public function getTableColumns(): array
    {
        return [
            'invoice_number' => Tables\Columns\TextColumn::make('invoice_number')->label(__('signage::signage.catalog_fields.invoice_number'))->placeholder('—'),
            'recorded_on' => Tables\Columns\TextColumn::make('recorded_on')->label(__('signage::signage.catalog_fields.recorded_on'))->date()->sortable(),
            'amount' => Tables\Columns\TextColumn::make('amount')->label(__('signage::signage.catalog_fields.amount'))->money('EUR')->sortable(),
            'notes' => Tables\Columns\TextColumn::make('notes')->label(__('signage::signage.fields.notes'))->placeholder('—')->limit(40),
        ];
    }

    public function getDefaultTableSortColumn(): string
    {
        return 'recorded_on';
    }

    public function getDefaultTableSortDirection(): string
    {
        return 'desc';
    }

    /**
     * @return array<string, Action>
     */
    public function getTableHeaderActions(): array
    {
        return [
            'create' => CreateAction::make(),
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
