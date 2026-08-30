<?php

declare(strict_types=1);

namespace Modules\Quotation\Actions;

use Illuminate\Support\Facades\DB;
use Modules\Intervention\Models\Intervention;
use Modules\Inventory\Models\InventoryMovement;
use Modules\Quotation\Models\Quotation;
use Spatie\QueueableAction\QueueableAction;

/**
 * Turns a finished intervention report into a draft quotation.
 *
 * The case it serves is the one that costs installers work: the technician
 * finds more than was expected, notes it in the report, and the extra never
 * gets quoted because retyping it in the evening is the job nobody does. Here
 * the report is already the list, so the quotation starts from it.
 *
 * It comes out as a draft, never sent and never confirmed. What was done and
 * what a customer is asked to pay are not the same document, and the person
 * who decides the difference is not the one who wrote the report.
 */
final class CreateQuotationFromInterventionAction
{
    use QueueableAction;

    private const DEFAULT_TAX_PERCENT = 22.0;

    public function __construct(
        private readonly BuildQuotationLinesFromInterventionAction $buildLines,
        private readonly RecalculateQuotationTotalsAction $recalculate,
    ) {}

    /**
     * Null when there is nothing to quote: an intervention with no materials,
     * no labour and no callout produces an empty document that somebody then
     * has to delete, which is worse than not creating it.
     */
    public function execute(Intervention $intervention): ?Quotation
    {
        $intervention->loadMissing([
            'materialLines',
            'inventoryMovements.product',
            'customer',
            'workOrder',
        ]);

        $customerId = $intervention->customer_id
            ?? $intervention->workOrder?->customer_id;

        if ($customerId === null) {
            return null;
        }

        $lines = $this->buildLines->execute(
            materials: $this->materials($intervention),
            labourAmount: (float) ($intervention->amount ?? 0),
            calloutFee: (float) ($intervention->callout_fee ?? 0),
            labourLabel: __('quotations.from_intervention.labour'),
            calloutLabel: __('quotations.from_intervention.callout'),
        );

        if ($lines === []) {
            return null;
        }

        return DB::transaction(function () use ($intervention, $customerId, $lines): Quotation {
            /** @var Quotation $quotation */
            $quotation = Quotation::query()->create([
                'customer_id' => $customerId,
                'work_order_id' => $intervention->work_order_id,
                'status_code' => 'draft',
                'title' => $this->title($intervention),
                'notes' => $intervention->notes,
                'tax_percent' => self::DEFAULT_TAX_PERCENT,
                'issued_at' => now(),
            ]);

            $sortOrder = 0;
            foreach ($lines as $line) {
                $quotation->items()->create([
                    'description' => $line->description,
                    'quantity' => $line->quantity,
                    'unit_price' => $line->unitPrice ?? 0.0,
                    'discount_percent' => 0,
                    'line_total' => 0,
                    'sort_order' => $sortOrder++,
                ]);
            }

            return $this->recalculate->execute($quotation->refresh());
        });
    }

    /**
     * Il materiale di un intervento arriva da due parti: le righe scritte a
     * mano e gli scarichi di magazzino. Vanno prese entrambe, altrimenti chi
     * lavora a magazzino si ritrova un preventivo con la sola manodopera.
     *
     * @return list<array{description: string, quantity: float, unitPrice: float}>
     */
    private function materials(Intervention $intervention): array
    {
        $materials = [];

        foreach ($intervention->materialLines as $line) {
            $materials[] = [
                'description' => (string) $line->description,
                'quantity' => (float) $line->quantity,
                'unitPrice' => (float) $line->unit_price,
            ];
        }

        foreach ($intervention->inventoryMovements as $movement) {
            if (! $movement instanceof InventoryMovement || $movement->movement_type !== 'scarico') {
                continue;
            }

            $product = $movement->product;

            $materials[] = [
                'description' => $product !== null ? (string) $product->name : '',
                'quantity' => (float) $movement->quantity,
                'unitPrice' => $this->sellingPrice($movement),
            ];
        }

        return $materials;
    }

    /**
     * Il movimento di magazzino porta il costo, non il prezzo: e' quello che
     * il materiale e' costato a chi lo ha comprato. Il prezzo al cliente sta
     * sul prodotto a listino, e va preso da li'.
     *
     * Il costo resta come ripiego per non lasciare una riga a zero, ma un
     * preventivo fatto sui costi si vende in perdita: per questo, quando
     * succede, la riga va guardata prima di mandarla.
     */
    private function sellingPrice(InventoryMovement $movement): float
    {
        $product = $movement->product;
        $listPrice = $product !== null ? $product->unit_price : null;

        if ($listPrice !== null && (float) $listPrice > 0.0) {
            return (float) $listPrice;
        }

        return (float) ($movement->unit_price ?? 0);
    }

    private function title(Intervention $intervention): string
    {
        $date = $intervention->started_at ?? $intervention->scheduled_at;

        return __('quotations.from_intervention.title', [
            'date' => $date?->format('d/m/Y') ?? '',
        ]);
    }
}
