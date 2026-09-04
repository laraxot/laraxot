<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\DeliveryNoteResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Billing\Models\DeliveryNoteLine;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

final class LinesRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'lines';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('billing::suppliers.delivery_note_lines');
    }

    /**
     * @return array<string, Component>
     */
    public function getFormSchema(): array
    {
        return [
            'line' => Grid::make(2)->components([
                TextInput::make('description')
                    ->label(__('billing::suppliers.fields.description'))
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                TextInput::make('quantity')
                    ->label(__('billing::suppliers.fields.quantity'))
                    ->numeric()
                    ->minValue(0)
                    ->step(0.01)
                    ->default(1)
                    ->required(),
                TextInput::make('unit_price')
                    ->label(__('billing::suppliers.fields.unit_price'))
                    ->numeric()
                    ->minValue(0)
                    ->step(0.01)
                    ->prefix('€')
                    ->default(0)
                    ->required(),
            ]),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->label(__('billing::suppliers.fields.description'))
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label(__('billing::suppliers.fields.quantity'))
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit_price')
                    ->label(__('billing::suppliers.fields.unit_price'))
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('line_total')
                    ->label(__('billing::suppliers.fields.line_total'))
                    ->state(fn (DeliveryNoteLine $record): float => round(
                        (float) $record->quantity * (float) $record->unit_price,
                        2,
                    ))
                    ->money('EUR'),
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

    /**
     * @return array<string, Component>
     */
    protected function getInfolistSchema(): array
    {
        return [
            'number' => TextEntry::make('number'),
            'supplier.legal_name' => TextEntry::make('supplier.legal_name'),
            'received_on' => TextEntry::make('received_on'),
            'lines_count' => TextEntry::make('lines_count'),
            'notes' => TextEntry::make('notes'),
        ];
    }
}
