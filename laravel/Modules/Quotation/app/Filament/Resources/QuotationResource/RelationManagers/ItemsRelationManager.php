<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\QuotationResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Quotation\Actions\RecalculateQuotationTotalsAction;
use Modules\Quotation\Models\Quotation;

final class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('quotations.quotation_items');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('product_id')
                ->label(__('quotations.fields.product'))
                ->relationship('product', 'name')
                ->searchable()
                ->preload(),
            TextInput::make('description')
                ->label(__('quotations.fields.description'))
                ->required()
                ->columnSpanFull()
                ->maxLength(255),
            TextInput::make('quantity')
                ->label(__('quotations.fields.quantity'))
                ->numeric()
                ->minValue(0.01)
                ->default(1)
                ->required(),
            TextInput::make('unit_price')
                ->label(__('quotations.fields.unit_price'))
                ->numeric()
                ->minValue(0)
                ->default(0)
                ->required(),
            TextInput::make('discount_percent')
                ->label(__('quotations.fields.discount_percent'))
                ->numeric()
                ->minValue(0)
                ->maxValue(100)
                ->default(0),
            TextInput::make('line_total')
                ->label(__('quotations.fields.line_total'))
                ->numeric()
                ->disabled(),
            TextInput::make('sort_order')
                ->label(__('quotations.fields.sort_order'))
                ->numeric()
                ->minValue(0)
                ->default(0),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->label(__('quotations.fields.description'))
                    ->searchable()
                    ->wrap()
                    ->limit(80),
                Tables\Columns\TextColumn::make('quantity')
                    ->label(__('quotations.fields.quantity'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit_price')
                    ->label(__('quotations.fields.unit_price'))
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount_percent')
                    ->label(__('quotations.fields.discount_percent'))
                    ->suffix('%')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('line_total')
                    ->label(__('quotations.fields.line_total'))
                    ->money('EUR')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->after(function (RelationManager $livewire): void {
                        /** @var Quotation $quotation */
                        $quotation = $livewire->getOwnerRecord();
                        app(RecalculateQuotationTotalsAction::class)->execute($quotation);
                    }),
            ])
            ->actions([
                EditAction::make()
                    ->after(function (RelationManager $livewire): void {
                        /** @var Quotation $quotation */
                        $quotation = $livewire->getOwnerRecord();
                        app(RecalculateQuotationTotalsAction::class)->execute($quotation);
                    }),
                DeleteAction::make()
                    ->after(function (RelationManager $livewire): void {
                        /** @var Quotation $quotation */
                        $quotation = $livewire->getOwnerRecord();
                        app(RecalculateQuotationTotalsAction::class)->execute($quotation);
                    }),
            ])
            ->bulkActions([
                DeleteBulkAction::make()
                    ->after(function (RelationManager $livewire): void {
                        /** @var Quotation $quotation */
                        $quotation = $livewire->getOwnerRecord();
                        app(RecalculateQuotationTotalsAction::class)->execute($quotation);
                    }),
            ]);
    }
}

