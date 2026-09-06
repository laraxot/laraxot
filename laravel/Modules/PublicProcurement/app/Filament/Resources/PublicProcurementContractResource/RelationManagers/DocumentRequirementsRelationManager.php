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
use Illuminate\Database\Eloquent\Model;

final class DocumentRequirementsRelationManager extends RelationManager
{
    protected static string $relationship = 'documentRequirements';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return 'Documenti richiesti';
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('code')->required(),
            TextInput::make('title')->required(),
            Select::make('phase')->options(['participation' => 'Partecipazione', 'execution' => 'Esecuzione', 'closing' => 'Chiusura'])->default('participation'),
            Select::make('status')->options(['missing' => 'Mancante', 'ok' => 'Ok', 'expired' => 'Scaduto', 'waived' => 'Non necessario'])->default('missing'),
            Select::make('severity')->options(['info' => 'Info', 'warning' => 'Warning', 'blocking' => 'Bloccante'])->default('warning'),
            Toggle::make('is_required')->default(true),
            DatePicker::make('due_on'),
            Textarea::make('notes')->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('phase')->badge(),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('severity')->badge()->sortable(),
                Tables\Columns\TextColumn::make('due_on')->date()->sortable(),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }
}
