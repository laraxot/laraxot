<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

final class PriceItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'priceItems';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return 'Computo / elenco prezzi';
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('code')->label('Codice voce'),
            TextInput::make('category')->label('Categoria'),
            Textarea::make('description')->label('Descrizione')->required()->columnSpanFull(),
            TextInput::make('unit')->label('UM')->default('cad')->required(),
            TextInput::make('planned_quantity')->label('Quantità prevista')->numeric()->default(0)->required(),
            TextInput::make('unit_price')->label('Prezzo unitario')->numeric()->default(0)->required(),
            TextInput::make('planned_total')->label('Totale')->numeric()->default(0),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->label('Codice')->searchable(),
                Tables\Columns\TextColumn::make('description')->label('Descrizione')->searchable()->limit(70),
                Tables\Columns\TextColumn::make('planned_quantity')->label('Q.tà')->sortable(),
                Tables\Columns\TextColumn::make('unit')->label('UM'),
                Tables\Columns\TextColumn::make('unit_price')->label('Prezzo')->money('EUR')->sortable(),
                Tables\Columns\TextColumn::make('planned_total')->label('Totale')->money('EUR')->sortable(),
            ])
            ->headerActions([CreateAction::make()])
            ->actions([EditAction::make(), DeleteAction::make()]);
    }
}
