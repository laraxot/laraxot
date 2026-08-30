<?php

declare(strict_types=1);

namespace Modules\Signage\Actions;

use Illuminate\Support\Facades\DB;
use Modules\Customer\Models\Customer;
use Modules\Quotation\Actions\RecalculateQuotationTotalsAction;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Models\QuotationItem;
use Modules\Signage\Datas\SignQuotationLine;
use Modules\Signage\Models\SignSpec;

/**
 * Turns a priced sign spec into a real draft quotation of the gestionale, so the
 * drawing ends up in the same place as every other quotation (PDF, revisions,
 * confirmation).
 *
 * Lines the operator has not completed (no price-list match, or a quantity that
 * cannot be derived from the drawing) are skipped rather than guessed: an
 * incomplete line would silently under-quote the job.
 */
final class CreateQuotationFromSignSpecAction
{
    public function __construct(
        private readonly PriceSignSpecAction $price,
    ) {}

    /**
     * @param  array{markup_percent?: float, quantities?: array<string, float>, item_overrides?: array<string, string>}  $options
     */
    public function execute(SignSpec $spec, Customer $customer, array $options = []): Quotation
    {
        $priced = $this->price->execute($spec, $options);
        $markup = $priced->markupPercent;

        return DB::transaction(function () use ($spec, $customer, $priced, $markup): Quotation {
            $quotation = Quotation::query()->create([
                'customer_id' => $customer->id,
                'status_code' => 'draft',
                'title' => $this->title($spec),
                // The brand/shop is the project the sign belongs to: it is what
                // lets the dashboard read margins per brand later on.
                'project' => $spec->brand,
                'notes' => $this->notes($spec),
                'cost_total' => round($priced->totalCost, 2),
                'issued_at' => now(),
            ]);

            $sortOrder = 0;

            foreach ($priced->lines as $line) {
                if ($line->quantity === null || $line->unitCost === null) {
                    continue;
                }

                $unitPrice = round($line->unitCost * (1 + $markup / 100), 2);

                QuotationItem::query()->create([
                    'quotation_id' => $quotation->id,
                    'description' => $this->description($line),
                    'quantity' => $line->quantity,
                    'unit_price' => $unitPrice,
                    'line_total' => round($line->quantity * $unitPrice, 2),
                    'sort_order' => $sortOrder++,
                ]);
            }

            return app(RecalculateQuotationTotalsAction::class)->execute($quotation);
        });
    }

    private function title(SignSpec $spec): string
    {
        $parts = array_filter([
            $spec->brand,
            $spec->shop,
            $spec->sign_type_code,
        ], static fn (?string $part): bool => $part !== null && $part !== '');

        return $parts === []
            ? (string) $spec->source_file
            : implode(' — ', $parts);
    }

    private function notes(SignSpec $spec): string
    {
        $quantity = $spec->quantity !== null ? ' ×'.$spec->quantity : '';

        return trim(sprintf(
            '%s%s (%s)',
            (string) ($spec->drawing_title ?? $spec->source_file),
            $quantity,
            (string) $spec->source_file,
        ));
    }

    private function description(SignQuotationLine $line): string
    {
        $parts = array_filter([
            $line->label !== '' ? $line->label : $line->role,
            $line->priceListItemName,
        ], static fn (?string $part): bool => $part !== null && $part !== '');

        $description = implode(' — ', $parts);

        return $line->unit !== null && $line->unit !== ''
            ? $description.' ['.$line->unit.']'
            : $description;
    }
}
