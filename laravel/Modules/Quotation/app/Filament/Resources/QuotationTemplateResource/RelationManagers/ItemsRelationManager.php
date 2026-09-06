<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\QuotationTemplateResource\RelationManagers;

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
use Illuminate\Database\Eloquent\Model;

final class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
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
                    ->toggleable(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label(__('quotations.fields.sort_order'))
                    ->numeric()
                    ->toggleable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
            ]);
    }
}
