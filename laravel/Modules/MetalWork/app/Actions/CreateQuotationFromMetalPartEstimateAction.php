<?php

declare(strict_types=1);

namespace Modules\MetalWork\Actions;

use Illuminate\Support\Facades\DB;
use Modules\Customer\Models\Customer;
use Modules\MetalWork\Models\MetalEstimateLine;
use Modules\MetalWork\Models\MetalPartEstimate;
use Modules\Quotation\Actions\RecalculateQuotationTotalsAction;
use Modules\Quotation\Models\Quotation;
use Modules\Quotation\Models\QuotationItem;

/**
 * Turns a stored estimate into a draft quotation of the gestionale, so a part
 * quoted from its drawing ends up in the same place as every other quotation —
 * PDF, revisions, confirmation.
 *
 * The estimate's rows are not copied one by one: the customer is quoted a part,
 * at a price per piece, and how the workshop split that price between steel and
 * machine time is its own business. The breakdown stays on the estimate, which
 * the quotation's notes point back to.
 */
final class CreateQuotationFromMetalPartEstimateAction
{
    public function execute(MetalPartEstimate $estimate, Customer $customer): Quotation
    {
        $estimate->loadMissing(['lines', 'drawing']);

        return DB::transaction(function () use ($estimate, $customer): Quotation {
            $quotation = Quotation::query()->create([
                'customer_id' => $customer->id,
                'status_code' => 'draft',
                'title' => $this->title($estimate),
                'notes' => $this->notes($estimate),
                'cost_total' => (float) $estimate->total_cost,
                'issued_at' => now(),
            ]);

            $quantity = max(1, (int) $estimate->quantity);
            $unitPrice = round((float) $estimate->sale_price / $quantity, 2);

            QuotationItem::query()->create([
                'quotation_id' => $quotation->id,
                'description' => $this->description($estimate),
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'line_total' => round($quantity * $unitPrice, 2),
                'sort_order' => 0,
            ]);

            $estimate->forceFill(['quotation_id' => $quotation->id])->save();

            return app(RecalculateQuotationTotalsAction::class)->execute($quotation);
        });
    }

    private function title(MetalPartEstimate $estimate): string
    {
        return trim(sprintf('%s — %s', (string) $estimate->part_code, (string) $estimate->name));
    }

    private function description(MetalPartEstimate $estimate): string
    {
        $weight = (float) $estimate->gross_weight_kg;

        return $weight > 0.0
            ? sprintf('%s — %s (%s kg/pz)', (string) $estimate->part_code, (string) $estimate->name, rtrim(rtrim(number_format($weight, 3, ',', '.'), '0'), ','))
            : $this->title($estimate);
    }

    /**
     * The note is what makes the price defensible six months later: which drawing
     * it came from, and what the workshop's own breakdown was.
     */
    private function notes(MetalPartEstimate $estimate): string
    {
        $parts = [];

        $drawing = $estimate->drawing;
        if ($drawing !== null) {
            $parts[] = sprintf('%s (%s)', (string) $drawing->part_code, (string) $drawing->source_file);
        }

        foreach ($estimate->lines as $line) {
            $parts[] = $this->lineSummary($line);
        }

        return implode("\n", $parts);
    }

    private function lineSummary(MetalEstimateLine $line): string
    {
        return sprintf(
            '%s: %s %s x %s = %s €',
            (string) $line->work_center_code,
            rtrim(rtrim(number_format((float) $line->quantity, 4, ',', '.'), '0'), ','),
            (string) $line->unit_code,
            number_format((float) $line->unit_cost, 4, ',', '.'),
            number_format((float) $line->line_cost, 2, ',', '.'),
        );
    }
}
