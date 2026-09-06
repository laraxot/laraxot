<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources;

use Filament\Actions\Action;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Modules\Billing\Actions\ReconcileSupplierInvoiceWithDeliveryNotesAction;
use Modules\Billing\Datas\ReconciliationDiscrepancy;
use Modules\Billing\Filament\Resources\SupplierInvoiceResource\Pages;
use Modules\Billing\Filament\Resources\SupplierInvoiceResource\RelationManagers\LinesRelationManager;
use Modules\Billing\Models\SupplierInvoice;
use Modules\Platform\Filament\Resources\GatedXotBaseResource;

/**
 * Fatture fornitore, con la conciliazione contro i DDT: e' l'unico momento in
 * cui quello che il fornitore fattura in piu' del consegnato e' ancora
 * contestabile.
 */
final class SupplierInvoiceResource extends GatedXotBaseResource
{
    protected static ?string $model = SupplierInvoice::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-currency-euro';

    public static function getNavigationSort(): int
    {
        return 3;
    }

    public static function getNavigationGroup(): string
    {
        return __('billing::suppliers.navigation');
    }

    public static function getModelLabel(): string
    {
        return __('billing::suppliers.supplier_invoice');
    }

    public static function getPluralModelLabel(): string
    {
        return __('billing::suppliers.supplier_invoices');
    }

    /**
     * @return array<string, string>
     */
    public static function statusOptions(): array
    {
        return [
            'pending' => __('billing::suppliers.statuses.pending'),
            'reconciled' => __('billing::suppliers.statuses.reconciled'),
            'disputed' => __('billing::suppliers.statuses.disputed'),
        ];
    }

    public static function statusColor(string $status): string
    {
        return match ($status) {
            'reconciled' => 'success',
            'disputed' => 'danger',
            default => 'warning',
        };
    }

    /**
     * Concilia la fattura una sola volta per istanza: la vista della modale puo'
     * essere valutata piu' volte durante un render e la conciliazione scrive lo
     * stato della fattura.
     *
     * La memoria e' agganciata all'oggetto e non all'id: due fatture diverse con
     * lo stesso id (richieste diverse, database ricreato fra un test e l'altro)
     * si vedrebbero altrimenti restituire il risultato l'una dell'altra.
     *
     * @return array<int, ReconciliationDiscrepancy>
     */
    public static function reconcile(SupplierInvoice $invoice): array
    {
        /** @var \WeakMap<SupplierInvoice, array<int, ReconciliationDiscrepancy>>|null $computed */
        static $computed = null;

        $computed ??= new \WeakMap();

        if (! isset($computed[$invoice])) {
            $computed[$invoice] = app(ReconcileSupplierInvoiceWithDeliveryNotesAction::class)->execute($invoice);
        }

        /** @var array<int, ReconciliationDiscrepancy> $result */
        $result = $computed[$invoice];

        return $result;
    }

    /**
     * Azione di conciliazione, condivisa fra tabella e pagine di dettaglio.
     */
    public static function reconcileAction(): Action
    {
        return Action::make('reconcileWithDeliveryNotes')
            ->label(__('billing::suppliers.reconciliation.action'))
            ->icon('heroicon-o-scale')
            ->color('warning')
            ->modalHeading(__('billing::suppliers.reconciliation.title'))
            ->modalSubmitAction(false)
            ->modalCancelActionLabel(__('billing::suppliers.reconciliation.submit'))
            ->modalContent(function (SupplierInvoice $record): View {
                $discrepancies = self::reconcile($record);

                return view('billing::filament.reconciliation-result', [
                    'invoice' => $record->refresh(),
                    'discrepancies' => $discrepancies,
                ]);
            });
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
                    Grid::make(2)->components([
                        Select::make('supplier_id')
                            ->label(__('billing::suppliers.fields.supplier'))
                            ->options(fn (): array => DeliveryNoteResource::supplierOptions())
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('number')
                            ->label(__('billing::suppliers.fields.number'))
                            ->required()
                            ->maxLength(255),
                        DatePicker::make('issued_on')
                            ->label(__('billing::suppliers.fields.issued_on'))
                            ->displayFormat('d/m/Y'),
                        TextInput::make('total')
                            ->label(__('billing::suppliers.fields.total'))
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->prefix('€')
                            ->default(0),
                        Select::make('status_code')
                            ->label(__('billing::suppliers.fields.status'))
                            ->options(self::statusOptions())
                            ->default('pending')
                            ->required(),
                    ]),

                    Textarea::make('notes')
                        ->label(__('billing::suppliers.fields.notes'))
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label(__('billing::suppliers.fields.number'))
                    ->weight(FontWeight::Bold)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('supplier.legal_name')
                    ->label(__('billing::suppliers.fields.supplier'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('issued_on')
                    ->label(__('billing::suppliers.fields.issued_on'))
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->label(__('billing::suppliers.fields.total'))
                    ->money('EUR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_code')
                    ->label(__('billing::suppliers.fields.status'))
                    ->badge()
                    ->color(fn (string $state): string => self::statusColor($state))
                    ->formatStateUsing(fn (string $state): string => __("suppliers.statuses.{$state}"))
                    ->sortable(),
                Tables\Columns\TextColumn::make('lines_count')
                    ->label(__('billing::suppliers.fields.lines_count'))
                    ->counts('lines')
                    ->badge(),
            ])
            ->defaultSort('issued_on', 'desc')
            ->filters([
                SelectFilter::make('status_code')
                    ->label(__('billing::suppliers.fields.status'))
                    ->options(self::statusOptions()),
                SelectFilter::make('supplier_id')
                    ->label(__('billing::suppliers.fields.supplier'))
                    ->options(fn (): array => DeliveryNoteResource::supplierOptions())
                    ->searchable(),
            ])
            ->recordActions([
                self::reconcileAction(),
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                DeleteBulkAction::make(),
                RestoreBulkAction::make(),
                ForceDeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            LinesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSupplierInvoices::route('/'),
            'create' => Pages\CreateSupplierInvoice::route('/create'),
            'view' => Pages\ViewSupplierInvoice::route('/{record}'),
            'edit' => Pages\EditSupplierInvoice::route('/{record}/edit'),
        ];
    }
}
