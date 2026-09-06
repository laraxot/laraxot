<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

final class PriceItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'priceItems';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return 'Computo / elenco prezzi';
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('code'),
            TextInput::make('category'),
            Textarea::make('description')->required()->columnSpanFull(),
            TextInput::make('unit')->default('cad')->required(),
            TextInput::make('planned_quantity')->numeric()->default(0)->required(),
            TextInput::make('unit_price')->numeric()->default(0)->required(),
            TextInput::make('planned_total')->numeric()->default(0),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')->searchable(),
                Tables\Columns\TextColumn::make('description')->searchable()->limit(70),
                Tables\Columns\TextColumn::make('planned_quantity')->sortable(),
                Tables\Columns\TextColumn::make('unit'),
                Tables\Columns\TextColumn::make('unit_price')->money('EUR')->sortable(),
                Tables\Columns\TextColumn::make('planned_total')->money('EUR')->sortable(),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }
}
