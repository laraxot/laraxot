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
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

final class DocumentRequirementsRelationManager extends RelationManager
{
    protected static string $relationship = 'documentRequirements';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return 'Documenti richiesti';
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('code')->label('Codice')->required(),
            TextInput::make('title')->label('Documento')->required(),
            Select::make('phase')->label('Fase')->options(['participation' => 'Partecipazione', 'execution' => 'Esecuzione', 'closing' => 'Chiusura'])->default('participation'),
            Select::make('status')->label('Stato')->options(['missing' => 'Mancante', 'ok' => 'Ok', 'expired' => 'Scaduto', 'waived' => 'Non necessario'])->default('missing'),
            Select::make('severity')->label('Gravità')->options(['info' => 'Info', 'warning' => 'Warning', 'blocking' => 'Bloccante'])->default('warning'),
            Toggle::make('is_required')->label('Obbligatorio')->default(true),
            DatePicker::make('due_on')->label('Scadenza'),
            Textarea::make('notes')->label('Note')->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('phase')->label('Fase')->badge(),
                Tables\Columns\TextColumn::make('title')->label('Documento')->searchable(),
                Tables\Columns\TextColumn::make('status')->label('Stato')->badge()->sortable(),
                Tables\Columns\TextColumn::make('severity')->label('Gravità')->badge()->sortable(),
                Tables\Columns\TextColumn::make('due_on')->label('Scadenza')->date()->sortable(),
            ])
            ->headerActions([CreateAction::make()])
            ->actions([EditAction::make(), DeleteAction::make()]);
    }
}
