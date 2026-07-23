<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

final class MeasurementsRelationManager extends RelationManager
{
    protected static string $relationship = 'measurements';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return 'Libretto misure';
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('price_item_id')
                ->label('Voce computo')
                ->options(fn (): array => $this->getOwnerRecord()->priceItems()->pluck('description', 'id')->all())
                ->searchable(),
            Select::make('status')->label('Stato')->options(['draft' => 'Bozza', 'approved' => 'Approvata', 'rejected' => 'Respinta'])->default('draft'),
            DatePicker::make('measured_on')->label('Data misura'),
            TextInput::make('quantity')->label('Quantità')->numeric()->default(0)->required(),
            TextInput::make('amount')->label('Importo')->numeric()->default(0)->required(),
            Textarea::make('description')->label('Descrizione')->columnSpanFull(),
            Textarea::make('notes')->label('Note')->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('status')->label('Stato')->badge()->sortable(),
                Tables\Columns\TextColumn::make('priceItem.code')->label('Voce'),
                Tables\Columns\TextColumn::make('description')->label('Descrizione')->limit(60),
                Tables\Columns\TextColumn::make('quantity')->label('Q.tà')->sortable(),
                Tables\Columns\TextColumn::make('amount')->label('Importo')->money('EUR')->sortable(),
                Tables\Columns\TextColumn::make('measured_on')->label('Data')->date()->sortable(),
            ])
            ->headerActions([CreateAction::make()])
            ->actions([EditAction::make(), DeleteAction::make()]);
    }
}
