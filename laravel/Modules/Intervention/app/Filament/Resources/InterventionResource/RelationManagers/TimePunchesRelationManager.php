<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Resources\InterventionResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

final class TimePunchesRelationManager extends RelationManager
{
    protected static string $relationship = 'timePunches';

    public static function getTitle(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): string
    {
        return __('interventions.time_punches');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('user_id')
                ->label(__('interventions.fields.user'))
                ->relationship('user', 'name')
                ->searchable()
                ->preload()
                ->required(),
            DateTimePicker::make('started_at')
                ->label(__('interventions.fields.started_at'))
                ->required(),
            DateTimePicker::make('ended_at')
                ->label(__('interventions.fields.ended_at')),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('interventions.fields.user'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('started_at')
                    ->label(__('interventions.fields.started_at'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ended_at')
                    ->label(__('interventions.fields.ended_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
