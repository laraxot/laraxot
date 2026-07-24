<?php

declare(strict_types=1);

namespace Modules\Billing\Actions;

use Illuminate\Support\Str;
use Modules\Billing\Datas\ReconciliationDiscrepancy;
use Modules\Billing\Datas\SupplierLine;
use Spatie\QueueableAction\QueueableAction;

/**
 * Concilia una fattura fornitore con i DDT ricevuti.
 *
 * Serve a intercettare cosa viene fatturato oltre il consegnato: quantita' in
 * piu', prezzi diversi da quelli del documento di trasporto, o materiale mai
 * arrivato. E' l'unico punto in cui questi errori sono ancora contestabili.
 */
final class ReconcileSupplierInvoiceAction
{
    use QueueableAction;

    /**
     * Tolleranza sui confronti: i decimali di quantita' e prezzo arrivano da
     * documenti diversi e un centesimo di differenza non e' una contestazione.
     */
    private const EPSILON = 0.01;

    /**
     * @param  array<int, SupplierLine>  $invoiceLines
     * @param  array<int, SupplierLine>  $deliveryLines
     * @return array<int, ReconciliationDiscrepancy>
     */
    public function execute(array $invoiceLines, array $deliveryLines): array
    {
        $delivered = [];

        foreach ($deliveryLines as $line) {
            $key = $this->normalize($line->description);
            $delivered[$key] ??= ['quantity' => 0.0, 'unitPrice' => $line->unitPrice, 'description' => $line->description];
            $delivered[$key]['quantity'] += $line->quantity;
        }

        $discrepancies = [];

        foreach ($invoiceLines as $line) {
            $key = $this->normalize($line->description);

            if (! isset($delivered[$key])) {
                $discrepancies[] = new ReconciliationDiscrepancy(
                    typeCode: ReconciliationDiscrepancy::TYPE_NOT_DELIVERED,
                    description: $line->description,
                    deliveredQuantity: 0.0,
                    invoicedQuantity: $line->quantity,
                    deliveredUnitPrice: 0.0,
                    invoicedUnitPrice: $line->unitPrice,
                    amountDifference: round($line->quantity * $line->unitPrice, 2),
                );

                continue;
            }

            $deliveredQuantity = $delivered[$key]['quantity'];
            $deliveredUnitPrice = $delivered[$key]['unitPrice'];

            if ($line->quantity - $deliveredQuantity > self::EPSILON) {
                $excess = round($line->quantity - $deliveredQuantity, 2);

                $discrepancies[] = new ReconciliationDiscrepancy(
                    typeCode: ReconciliationDiscrepancy::TYPE_QUANTITY_OVER,
                    description: $line->description,
                    deliveredQuantity: $deliveredQuantity,
                    invoicedQuantity: $line->quantity,
                    deliveredUnitPrice: $deliveredUnitPrice,
                    invoicedUnitPrice: $line->unitPrice,
                    amountDifference: round($excess * $line->unitPrice, 2),
                );
            }

            if (abs($line->unitPrice - $deliveredUnitPrice) > self::EPSILON) {
                // La differenza si valuta sulla quantita' effettivamente consegnata:
                // quella eccedente e' gia' contestata dalla riga sopra.
                $comparableQuantity = min($line->quantity, $deliveredQuantity);

                $discrepancies[] = new ReconciliationDiscrepancy(
                    typeCode: ReconciliationDiscrepancy::TYPE_PRICE_MISMATCH,
                    description: $line->description,
                    deliveredQuantity: $deliveredQuantity,
                    invoicedQuantity: $line->quantity,
                    deliveredUnitPrice: $deliveredUnitPrice,
                    invoicedUnitPrice: $line->unitPrice,
                    amountDifference: round(($line->unitPrice - $deliveredUnitPrice) * $comparableQuantity, 2),
                );
            }

            $delivered[$key]['quantity'] = round(max(0.0, $deliveredQuantity - $line->quantity), 2);
        }

        foreach ($delivered as $residual) {
            if ($residual['quantity'] <= self::EPSILON) {
                continue;
            }

            // Non e' un errore del fornitore: e' merce arrivata e non ancora
            // fatturata, che va attesa su una fattura successiva.
            $discrepancies[] = new ReconciliationDiscrepancy(
                typeCode: ReconciliationDiscrepancy::TYPE_NOT_INVOICED,
                description: $residual['description'],
                deliveredQuantity: $residual['quantity'],
                invoicedQuantity: 0.0,
                deliveredUnitPrice: $residual['unitPrice'],
                invoicedUnitPrice: 0.0,
                amountDifference: round(-1 * $residual['quantity'] * $residual['unitPrice'], 2),
            );
        }

        return $discrepancies;
    }

    private function normalize(string $description): string
    {
        return Str::of($description)->trim()->lower()->squish()->toString();
    }
}
