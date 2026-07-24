<?php

declare(strict_types=1);

namespace Modules\Bom\Filament\Resources\BomResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Tables;
use Filament\Tables\Table;
use Modules\Catalog\Models\Product;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

final class BomItemsRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'items';

    /**
     * @return array<int, Component>
     */
    public function getFormSchema(): array
    {
        return [
            Select::make('component_product_id')
                ->label(__('boms.fields.component_product_id'))
                ->options(fn () => Product::query()->orderBy('name')->pluck('name', 'id')->all())
                ->searchable()
                ->preload()
                ->required(),
            TextInput::make('quantity')
                ->label(__('boms.fields.quantity'))
                ->numeric()
                ->required(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('componentProduct.name')->label(__('boms.fields.component_product_id'))->searchable(),
                Tables\Columns\TextColumn::make('quantity')->label(__('boms.fields.quantity')),
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
