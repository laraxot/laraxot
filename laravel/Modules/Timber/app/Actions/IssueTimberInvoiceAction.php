<?php

declare(strict_types=1);

namespace Modules\Timber\Actions;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Timber\Models\TimberCustomer;
use Modules\Timber\Models\TimberInvoice;
use Modules\Timber\Models\TimberProduct;
use Modules\Timber\Support\TimberInvoiceNumberGenerator;
use RuntimeException;
use Spatie\QueueableAction\QueueableAction;

/**
 * Emette un documento fiscale: assegna il numero, ricalcola i totali IVA e,
 * per i tipi che movimentano il magazzino (vendita al banco, corrispettivo),
 * genera gli scarichi riusando l'azione di magazzino di app/Timber.
 */
final class IssueTimberInvoiceAction
{
    use QueueableAction;

    public function __construct(
        private readonly RecalculateTimberInvoiceTotalsAction $recalculateTotals,
        private readonly TimberInvoiceNumberGenerator $numberGenerator,
        private readonly RegisterTimberStockMovementAction $registerStockMovement,
    ) {}

    public function execute(TimberInvoice $invoice, ?int $userId = null): TimberInvoice
    {
        if ($invoice->issued_at !== null) {
            throw new RuntimeException('Il documento e gia stato emesso.');
        }

        return DB::transaction(function () use ($invoice, $userId): TimberInvoice {
            if (! is_string($invoice->number) || trim($invoice->number) === '') {
                $invoice->forceFill(['number' => $this->numberGenerator->next($invoice->kind)])->save();
            }

            $this->recalculateTotals->execute($invoice);

            if ($invoice->hasStockImpact() && $invoice->stock_posted_at === null) {
                $this->postStock($invoice, $userId);
                $invoice->forceFill(['stock_posted_at' => now()])->save();
            }

            $invoice->forceFill([
                'status_code' => 'issued',
                'issued_at' => now(),
            ])->save();

            return $invoice->refresh();
        });
    }

    private function postStock(TimberInvoice $invoice, ?int $userId): void
    {
        $directions = config('timber.invoice_stock_directions', []);
        $direction = is_array($directions) ? ($directions[$invoice->kind] ?? null) : null;

        if (! is_string($direction)) {
            return;
        }

        $customer = $invoice->customer_id !== null
            ? TimberCustomer::query()->find($invoice->customer_id)
            : null;
        $movementDate = Carbon::parse($invoice->invoice_date);

        foreach ($invoice->lines()->with('product')->get() as $line) {
            if ($line->product_id === null) {
                continue;
            }

            $timberProduct = TimberProduct::query()->find($line->product_id);
            if ($timberProduct === null) {
                continue;
            }

            $this->registerStockMovement->execute(
                product: $timberProduct,
                directionCode: $direction,
                quantity: (float) $line->quantity,
                unitOfMeasure: (string) $line->unit_of_measure,
                userId: $userId,
                customer: $customer,
                documentTypeCode: $invoice->kind,
                documentReference: $invoice->number,
                lotCode: $line->lot_code,
                movementDate: $movementDate,
            );
        }
    }
}
