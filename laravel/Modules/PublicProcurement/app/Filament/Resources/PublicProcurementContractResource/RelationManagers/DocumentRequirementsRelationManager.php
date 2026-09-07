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
use Filament\Schemas\Components\Component;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

final class DocumentRequirementsRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'documentRequirements';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return 'Documenti richiesti';
    }

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'code' => TextInput::make('code')->required(),
            'title' => TextInput::make('title')->required(),
            'phase' => Select::make('phase')->options(['participation' => 'Partecipazione', 'execution' => 'Esecuzione', 'closing' => 'Chiusura'])->default('participation'),
            'status' => Select::make('status')->options(['missing' => 'Mancante', 'ok' => 'Ok', 'expired' => 'Scaduto', 'waived' => 'Non necessario'])->default('missing'),
            'severity' => Select::make('severity')->options(['info' => 'Info', 'warning' => 'Warning', 'blocking' => 'Bloccante'])->default('warning'),
            'is_required' => Toggle::make('is_required')->default(true),
            'due_on' => DatePicker::make('due_on'),
            'notes' => Textarea::make('notes')->columnSpanFull(),
        ];
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
