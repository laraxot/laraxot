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
use Illuminate\Database\Eloquent\Model;

final class DeadlinesRelationManager extends RelationManager
{
    protected static string $relationship = 'deadlines';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return 'Scadenze';
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('type')->required(),
            TextInput::make('title')->required(),
            Select::make('status')->options(['open' => 'Aperta', 'done' => 'Completata', 'waived' => 'Non necessaria'])->default('open'),
            Select::make('severity')->options(['info' => 'Info', 'warning' => 'Warning', 'blocking' => 'Bloccante'])->default('warning'),
            DateTimePicker::make('due_at'),
            DateTimePicker::make('completed_at'),
            Textarea::make('notes')->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('severity')->badge()->sortable(),
                Tables\Columns\TextColumn::make('due_at')->dateTime()->sortable(),
            ])
            ->headerActions([CreateAction::make()])
            ->actions([EditAction::make(), DeleteAction::make()]);
    }
}
