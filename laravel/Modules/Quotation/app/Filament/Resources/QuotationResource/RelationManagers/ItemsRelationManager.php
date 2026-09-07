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
use Filament\Schemas\Components\Component;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Quotation\Actions\RecalculateQuotationTotalsAction;
use Modules\Quotation\Models\Quotation;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

final class ItemsRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'items';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('quotations.quotation_items');
    }

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'product_id' => Select::make('product_id')

                ->relationship('product', 'name')
                ->searchable()
                ->preload(),
            'description' => TextInput::make('description')

                ->required()
                ->columnSpanFull()
                ->maxLength(255),
            'quantity' => TextInput::make('quantity')

                ->numeric()
                ->minValue(0.01)
                ->default(1)
                ->required(),
            'unit_price' => TextInput::make('unit_price')

                ->numeric()
                ->minValue(0)
                ->default(0)
                ->required(),
            'discount_percent' => TextInput::make('discount_percent')

                ->numeric()
                ->minValue(0)
                ->maxValue(100)
                ->default(0),
            'line_total' => TextInput::make('line_total')

                ->numeric()
                ->disabled(),
            'sort_order' => TextInput::make('sort_order')

                ->numeric()
                ->minValue(0)
                ->default(0),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')

                    ->searchable()
                    ->wrap()
                    ->limit(80),
                Tables\Columns\TextColumn::make('quantity')

                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit_price')

                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('discount_percent')

                    ->suffix('%')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('line_total')

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
            ->recordActions([
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
            ->toolbarActions([
                DeleteBulkAction::make()
                    ->after(function (RelationManager $livewire): void {
                        /** @var Quotation $quotation */
                        $quotation = $livewire->getOwnerRecord();
                        app(RecalculateQuotationTotalsAction::class)->execute($quotation);
                    }),
            ]);
    }
}
