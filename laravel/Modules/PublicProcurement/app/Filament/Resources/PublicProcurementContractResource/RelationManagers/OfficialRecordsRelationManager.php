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

final class OfficialRecordsRelationManager extends RelationManager
{
    protected static string $relationship = 'officialRecords';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return 'Verbali e atti';
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('type')->label('Tipo')->required(),
            TextInput::make('title')->label('Titolo')->required(),
            TextInput::make('protocol_number')->label('Protocollo'),
            DatePicker::make('recorded_on')->label('Data'),
            Select::make('status')->label('Stato')->options(['draft' => 'Bozza', 'sent' => 'Inviato', 'signed' => 'Firmato', 'archived' => 'Archiviato'])->default('draft'),
            Textarea::make('summary')->label('Sintesi')->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')->label('Tipo')->badge(),
                Tables\Columns\TextColumn::make('title')->label('Titolo')->searchable(),
                Tables\Columns\TextColumn::make('protocol_number')->label('Protocollo'),
                Tables\Columns\TextColumn::make('status')->label('Stato')->badge(),
                Tables\Columns\TextColumn::make('recorded_on')->label('Data')->date()->sortable(),
            ])
            ->headerActions([CreateAction::make()])
            ->actions([EditAction::make(), DeleteAction::make()]);
    }
}
