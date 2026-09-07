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
use Modules\PublicProcurement\Models\PublicProcurementContract;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

final class MeasurementsRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'measurements';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return 'Libretto misure';
    }

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'price_item_id' => Select::make('price_item_id')
                ->options(function (): array {
                    $contract = $this->getOwnerRecord();

                    if (! $contract instanceof PublicProcurementContract) {
                        return [];
                    }

                    return $contract->priceItems()->pluck('description', 'id')->all();
                })
                ->searchable(),
            'status' => Select::make('status')->options(['draft' => 'Bozza', 'approved' => 'Approvata', 'rejected' => 'Respinta'])->default('draft'),
            'measured_on' => DatePicker::make('measured_on'),
            'quantity' => TextInput::make('quantity')->numeric()->default(0)->required(),
            'amount' => TextInput::make('amount')->numeric()->default(0)->required(),
            'description' => Textarea::make('description')->columnSpanFull(),
            'notes' => Textarea::make('notes')->columnSpanFull(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('status')->badge()->sortable(),
                Tables\Columns\TextColumn::make('priceItem.code'),
                Tables\Columns\TextColumn::make('description')->limit(60),
                Tables\Columns\TextColumn::make('quantity')->sortable(),
                Tables\Columns\TextColumn::make('amount')->money('EUR')->sortable(),
                Tables\Columns\TextColumn::make('measured_on')->date()->sortable(),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }
}
