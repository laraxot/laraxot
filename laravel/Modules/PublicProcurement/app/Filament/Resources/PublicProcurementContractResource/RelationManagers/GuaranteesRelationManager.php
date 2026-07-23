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

final class GuaranteesRelationManager extends RelationManager
{
    protected static string $relationship = 'guarantees';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return 'Cauzioni e garanzie';
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('type')->label('Tipo')->options(['provisional' => 'Provvisoria', 'definitive' => 'Definitiva', 'insurance' => 'Polizza'])->default('definitive'),
            TextInput::make('title')->label('Titolo')->required(),
            TextInput::make('provider')->label('Emittente'),
            TextInput::make('policy_number')->label('Numero polizza'),
            Select::make('status')->label('Stato')->options(['valid' => 'Valida', 'expiring' => 'In scadenza', 'expired' => 'Scaduta', 'released' => 'Svincolata'])->default('valid'),
            TextInput::make('amount')->label('Importo')->numeric(),
            DatePicker::make('issued_on')->label('Emissione'),
            DatePicker::make('expires_on')->label('Scadenza'),
            Textarea::make('notes')->label('Note')->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')->label('Tipo')->badge(),
                Tables\Columns\TextColumn::make('title')->label('Titolo')->searchable(),
                Tables\Columns\TextColumn::make('status')->label('Stato')->badge(),
                Tables\Columns\TextColumn::make('amount')->label('Importo')->money('EUR')->sortable(),
                Tables\Columns\TextColumn::make('expires_on')->label('Scadenza')->date()->sortable(),
            ])
            ->headerActions([CreateAction::make()])
            ->actions([EditAction::make(), DeleteAction::make()]);
    }
}
