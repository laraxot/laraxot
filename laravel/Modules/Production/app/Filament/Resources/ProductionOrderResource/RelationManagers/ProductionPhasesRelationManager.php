<?php

declare(strict_types=1);

namespace Modules\Production\Filament\Resources\ProductionOrderResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\User;

final class ProductionPhasesRelationManager extends RelationManager
{
    protected static string $relationship = 'phases';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label(__('production.fields.name'))
                ->required()
                ->maxLength(255),
            TextInput::make('sort_order')
                ->label(__('production.fields.sort_order'))
                ->numeric()
                ->default(0),
            Select::make('status')
                ->label(__('production.fields.status'))
                ->options([
                    'planned' => __('production.statuses.planned'),
                    'in_progress' => __('production.statuses.in_progress'),
                    'completed' => __('production.statuses.completed'),
                    'blocked' => __('production.statuses.blocked'),
                ])
                ->required(),
            Select::make('assigned_to_user_id')
                ->label(__('production.fields.assigned_to_user'))
                ->options(fn () => User::query()->orderBy('name')->pluck('name', 'id')->all())
                ->searchable(),
            DateTimePicker::make('started_at')->label(__('production.fields.started_at')),
            DateTimePicker::make('completed_at')->label(__('production.fields.completed_at')),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(__('production.phase'))->searchable(),
                Tables\Columns\TextColumn::make('status')->label(__('production.fields.status')),
                Tables\Columns\TextColumn::make('assignedTo.name')->label(__('production.fields.assigned_to_user'))->toggleable(),
                Tables\Columns\TextColumn::make('started_at')->label(__('production.fields.started_at'))->dateTime()->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('completed_at')->label(__('production.fields.completed_at'))->dateTime()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}

