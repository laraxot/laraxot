<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\SupplierInvoiceResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Billing\Models\DeliveryNoteLine;
use Modules\Billing\Models\SupplierInvoice;
use Modules\Billing\Models\SupplierInvoiceLine;

final class LinesRelationManager extends RelationManager
{
    protected static string $relationship = 'lines';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('suppliers.supplier_invoice_lines');
    }

    /**
     * Solo le righe di DDT dello stesso fornitore: agganciare una riga di un
     * altro fornitore non vorrebbe dire niente.
     *
     * @return array<int, string>
     */
    private function deliveryNoteLineOptions(): array
    {
        $owner = $this->getOwnerRecord();

        if (! $owner instanceof SupplierInvoice) {
            return [];
        }

        return DeliveryNoteLine::query()
            ->whereHas('deliveryNote', fn ($query) => $query->where('supplier_id', $owner->supplier_id))
            ->with('deliveryNote')
            ->orderByDesc('id')
            ->limit(200)
            ->get()
            ->mapWithKeys(static fn (DeliveryNoteLine $line): array => [
                $line->id => sprintf(
                    '%s — %s (%s)',
                    (string) ($line->deliveryNote?->number ?? '—'),
                    (string) $line->description,
                    number_format((float) $line->quantity, 2, ',', '.'),
                ),
            ])
            ->all();
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('description')
                ->label(__('suppliers.fields.description'))
                ->required()
                ->maxLength(255)
                ->columnSpanFull(),
            TextInput::make('quantity')
                ->label(__('suppliers.fields.quantity'))
                ->numeric()
                ->minValue(0)
                ->step(0.01)
                ->default(1)
                ->required(),
            TextInput::make('unit_price')
                ->label(__('suppliers.fields.unit_price'))
                ->numeric()
                ->minValue(0)
                ->step(0.01)
                ->prefix('€')
                ->default(0)
                ->required(),
            Select::make('delivery_note_line_id')
                ->label(__('suppliers.fields.delivery_note_line'))
                ->options(fn (): array => $this->deliveryNoteLineOptions())
                ->searchable()
                ->preload()
                ->columnSpanFull(),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('description')
                    ->label(__('suppliers.fields.description'))
                    ->wrap()
                    ->searchable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label(__('suppliers.fields.quantity'))
                    ->numeric(decimalPlaces: 2)
                    ->sortable(),
                Tables\Columns\TextColumn::make('unit_price')
                    ->label(__('suppliers.fields.unit_price'))
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('line_total')
                    ->label(__('suppliers.fields.line_total'))
                    ->state(fn (SupplierInvoiceLine $record): float => round(
                        (float) $record->quantity * (float) $record->unit_price,
                        2,
                    ))
                    ->money('EUR'),
                Tables\Columns\TextColumn::make('deliveryNoteLine.deliveryNote.number')
                    ->label(__('suppliers.fields.delivery_note_line'))
                    ->badge()
                    ->placeholder('—')
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
