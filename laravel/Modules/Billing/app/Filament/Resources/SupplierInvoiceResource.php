<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources;

use Filament\Actions\Action;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Modules\Billing\Actions\ReconcileSupplierInvoiceWithDeliveryNotesAction;
use Modules\Billing\Datas\ReconciliationDiscrepancy;
use Modules\Billing\Models\SupplierInvoice;
use Modules\Xot\Filament\Resources\XotBaseResource;

/**
 * Fatture fornitore, con la conciliazione contro i DDT: e' l'unico momento in
 * cui quello che il fornitore fattura in piu' del consegnato e' ancora
 * contestabile.
 *
 * Schema form → {@see SupplierInvoiceResource\Schemas\SupplierInvoiceForm}
 * Schema infolist → {@see SupplierInvoiceResource\Schemas\SupplierInvoiceInfolist}
 * Tabella → {@see SupplierInvoiceResource\Tables\SupplierInvoicesTable}
 */
final class SupplierInvoiceResource extends XotBaseResource
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
}
