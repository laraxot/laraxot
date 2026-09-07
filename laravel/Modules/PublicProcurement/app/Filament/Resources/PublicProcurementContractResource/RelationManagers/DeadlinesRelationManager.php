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
use Filament\Schemas\Components\Component;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

final class DeadlinesRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'deadlines';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return 'Scadenze';
    }

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'type' => TextInput::make('type')->required(),
            'title' => TextInput::make('title')->required(),
            'status' => Select::make('status')->options(['open' => 'Aperta', 'done' => 'Completata', 'waived' => 'Non necessaria'])->default('open'),
            'severity' => Select::make('severity')->options(['info' => 'Info', 'warning' => 'Warning', 'blocking' => 'Bloccante'])->default('warning'),
            'due_at' => DateTimePicker::make('due_at'),
            'completed_at' => DateTimePicker::make('completed_at'),
            'notes' => Textarea::make('notes')->columnSpanFull(),
        ];
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
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }
}
