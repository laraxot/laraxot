<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

final class PriceItemsRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'priceItems';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return 'Computo / elenco prezzi';
    }

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'code' => TextInput::make('code'),
            'category' => TextInput::make('category'),
            'description' => Textarea::make('description')->required()->columnSpanFull(),
            'unit' => TextInput::make('unit')->default('cad')->required(),
            'planned_quantity' => TextInput::make('planned_quantity')->numeric()->default(0)->required(),
            'unit_price' => TextInput::make('unit_price')->numeric()->default(0)->required(),
            'planned_total' => TextInput::make('planned_total')->numeric()->default(0),
        ];
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
