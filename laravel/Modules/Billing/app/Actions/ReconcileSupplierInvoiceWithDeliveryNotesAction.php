<?php

declare(strict_types=1);

namespace Modules\Billing\Actions;

use Modules\Billing\Domain\Actions\ReconcileSupplierInvoiceAction;
use Modules\Billing\Domain\DTO\ReconciliationDiscrepancy;
use Modules\Billing\Domain\DTO\SupplierLine;
use Modules\Billing\Models\DeliveryNote;
use Modules\Billing\Models\DeliveryNoteLine;
use Modules\Billing\Models\SupplierInvoice;
use Modules\Billing\Models\SupplierInvoiceLine;

/**
 * Concilia una fattura fornitore con i DDT dello stesso fornitore e aggiorna lo
 * stato della fattura in base a quello che emerge.
 */
final readonly class ReconcileSupplierInvoiceWithDeliveryNotesAction
{
    public function __construct(
        private ReconcileSupplierInvoiceAction $reconcileSupplierInvoice,
    ) {}

    /**
     * @return array<int, ReconciliationDiscrepancy>
     */
    public function execute(SupplierInvoice $invoice): array
    {
        $invoice->loadMissing('lines');

        $deliveryLines = DeliveryNoteLine::query()
            ->whereIn(
                'delivery_note_id',
                DeliveryNote::query()
                    ->where('supplier_id', $invoice->supplier_id)
                    ->select('id'),
            )
            ->get();

        $discrepancies = $this->reconcileSupplierInvoice->execute(
            invoiceLines: $invoice->lines
                ->map(static fn (SupplierInvoiceLine $line): SupplierLine => new SupplierLine(
                    description: (string) $line->description,
                    quantity: (float) $line->quantity,
                    unitPrice: (float) $line->unit_price,
                ))
                ->all(),
            deliveryLines: $deliveryLines
                ->map(static fn (DeliveryNoteLine $line): SupplierLine => new SupplierLine(
                    description: (string) $line->description,
                    quantity: (float) $line->quantity,
                    unitPrice: (float) $line->unit_price,
                ))
                ->all(),
        );

        $invoice->forceFill([
            'status_code' => $this->statusFor($discrepancies),
        ])->save();

        return $discrepancies;
    }

    /**
     * Il materiale consegnato e non ancora fatturato non e' un problema del
     * fornitore: la fattura resta conciliata, quella merce arrivera' dopo.
     *
     * @param  array<int, ReconciliationDiscrepancy>  $discrepancies
     */
    private function statusFor(array $discrepancies): string
    {
        foreach ($discrepancies as $discrepancy) {
            if ($discrepancy->typeCode !== ReconciliationDiscrepancy::TYPE_NOT_INVOICED) {
                return 'disputed';
            }
        }

        return 'reconciled';
    }
}
