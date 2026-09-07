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
use Filament\Schemas\Components\Component;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

final class GuaranteesRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'guarantees';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return 'Cauzioni e garanzie';
    }

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'type' => Select::make('type')->options(['provisional' => 'Provvisoria', 'definitive' => 'Definitiva', 'insurance' => 'Polizza'])->default('definitive'),
            'title' => TextInput::make('title')->required(),
            'provider' => TextInput::make('provider'),
            'policy_number' => TextInput::make('policy_number'),
            'status' => Select::make('status')->options(['valid' => 'Valida', 'expiring' => 'In scadenza', 'expired' => 'Scaduta', 'released' => 'Svincolata'])->default('valid'),
            'amount' => TextInput::make('amount')->numeric(),
            'issued_on' => DatePicker::make('issued_on'),
            'expires_on' => DatePicker::make('expires_on'),
            'notes' => Textarea::make('notes')->columnSpanFull(),
        ];
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
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }
}
