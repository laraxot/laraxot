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
use Illuminate\Database\Eloquent\Model;

final class OfficialRecordsRelationManager extends RelationManager
{
    protected static string $relationship = 'officialRecords';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return 'Verbali e atti';
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('type')->required(),
            TextInput::make('title')->required(),
            TextInput::make('protocol_number'),
            DatePicker::make('recorded_on'),
            Select::make('status')->options(['draft' => 'Bozza', 'sent' => 'Inviato', 'signed' => 'Firmato', 'archived' => 'Archiviato'])->default('draft'),
            Textarea::make('summary')->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')->badge(),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('protocol_number'),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('recorded_on')->date()->sortable(),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }
}
