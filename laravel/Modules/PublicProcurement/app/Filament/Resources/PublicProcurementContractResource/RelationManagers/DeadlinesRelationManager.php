<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Filament\Resources\PublicProcurementContractResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

final class DeadlinesRelationManager extends RelationManager
{
    protected static string $relationship = 'deadlines';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return 'Scadenze';
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('type')->label('Tipo')->required(),
            TextInput::make('title')->label('Titolo')->required(),
            Select::make('status')->label('Stato')->options(['open' => 'Aperta', 'done' => 'Completata', 'waived' => 'Non necessaria'])->default('open'),
            Select::make('severity')->label('Gravità')->options(['info' => 'Info', 'warning' => 'Warning', 'blocking' => 'Bloccante'])->default('warning'),
            DateTimePicker::make('due_at')->label('Scadenza'),
            DateTimePicker::make('completed_at')->label('Completata il'),
            Textarea::make('notes')->label('Note')->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('status')->label('Stato')->badge()->sortable(),
                Tables\Columns\TextColumn::make('title')->label('Scadenza')->searchable(),
                Tables\Columns\TextColumn::make('severity')->label('Gravità')->badge()->sortable(),
                Tables\Columns\TextColumn::make('due_at')->label('Data')->dateTime()->sortable(),
            ])
            ->headerActions([CreateAction::make()])
            ->actions([EditAction::make(), DeleteAction::make()]);
    }
}
