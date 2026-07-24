<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources;

use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Modules\Billing\Filament\Resources\SupplierResource\Pages;
use Modules\Billing\Models\Supplier;
use Modules\Xot\Filament\Resources\XotBaseResource;

/**
 * Anagrafica fornitori: la testa del ciclo passivo.
 */
final class SupplierResource extends XotBaseResource
{
    protected static ?string $model = Supplier::class;

    /**
     * Ricerca globale: l'operatore cerca un codice o un nome senza sapere in che
     * sezione si trova. I dettagli evitano di dover aprire il record per capire
     * se e' quello giusto.
     */
    protected static ?string $recordTitleAttribute = 'legal_name';

    /**
     * @return array<int, string>
     */
    public static function getGloballySearchableAttributes(): array
    {
        return ['legal_name', 'vat_number', 'email', 'phone'];
    }

    /**
     * @return array<string, string|null>
     */
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        /** @var Supplier $record */
        return array_filter([
            'P.IVA' => $record->vat_number,
            'Email' => $record->email,
        ]);
    }

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-storefront';

    public static function getNavigationSort(): int
    {
        return 1;
    }

    public static function getNavigationGroup(): string
    {
        return __('suppliers.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('suppliers.supplier');
    }

    public static function getPluralModelLabel(): string
    {
        return __('suppliers.suppliers');
    }

    /**
     * @return array<int, Component>
     */
    public static function getFormSchema(): array
    {
        return [
            Section::make()
                ->columnSpanFull()
                ->components([
                    TextInput::make('legal_name')
                        ->label(__('suppliers.fields.legal_name'))
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Grid::make(2)->components([
                        TextInput::make('vat_number')
                            ->label(__('suppliers.fields.vat_number'))
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label(__('suppliers.fields.phone'))
                            ->tel()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label(__('suppliers.fields.email'))
                            ->email()
                            ->maxLength(255),
                        Toggle::make('is_active')
                            ->label(__('suppliers.fields.is_active'))
                            ->default(true),
                    ]),
                ])
                ->columns(2),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('legal_name')
                    ->label(__('suppliers.fields.legal_name'))
                    ->weight(FontWeight::Bold)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('vat_number')
                    ->label(__('suppliers.fields.vat_number'))
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('suppliers.fields.email'))
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label(__('suppliers.fields.phone'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('delivery_notes_count')
                    ->label(__('suppliers.fields.delivery_notes_count'))
                    ->counts('deliveryNotes')
                    ->badge(),
                Tables\Columns\TextColumn::make('invoices_count')
                    ->label(__('suppliers.fields.invoices_count'))
                    ->counts('invoices')
                    ->badge(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('suppliers.fields.is_active'))
                    ->boolean()
                    ->sortable(),
            ])
            ->defaultSort('legal_name')
            ->filters([
                TernaryFilter::make('is_active')
                    ->label(__('suppliers.fields.is_active')),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
                RestoreBulkAction::make(),
                ForceDeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'view' => Pages\ViewSupplier::route('/{record}'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
