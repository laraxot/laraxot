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

final class GuaranteesRelationManager extends RelationManager
{
    protected static string $relationship = 'guarantees';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return 'Cauzioni e garanzie';
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('type')->options(['provisional' => 'Provvisoria', 'definitive' => 'Definitiva', 'insurance' => 'Polizza'])->default('definitive'),
            TextInput::make('title')->required(),
            TextInput::make('provider'),
            TextInput::make('policy_number'),
            Select::make('status')->options(['valid' => 'Valida', 'expiring' => 'In scadenza', 'expired' => 'Scaduta', 'released' => 'Svincolata'])->default('valid'),
            TextInput::make('amount')->numeric(),
            DatePicker::make('issued_on'),
            DatePicker::make('expires_on'),
            Textarea::make('notes')->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')->badge(),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('amount')->money('EUR')->sortable(),
                Tables\Columns\TextColumn::make('expires_on')->date()->sortable(),
            ])
            ->headerActions([CreateAction::make()])
            ->actions([EditAction::make(), DeleteAction::make()]);
    }
}
