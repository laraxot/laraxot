<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Resources\InterventionResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

final class TimePunchesRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'timePunches';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('interventions.time_punches');
    }

    public function getFormSchema(): array
    {
        return [
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
        ];
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
