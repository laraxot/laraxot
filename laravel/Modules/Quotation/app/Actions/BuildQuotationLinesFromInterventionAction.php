<?php

declare(strict_types=1);

namespace Modules\Quotation\Actions;

use Modules\Quotation\DTO\DictatedQuotationItem;
use Spatie\QueueableAction\QueueableAction;

/**
 * Turns what was actually done on site into the lines of a quotation.
 *
 * The order matters more than it looks: materials first, then labour, then the
 * callout fee. It is how a customer reads a quote — what you fitted, what it
 * took to fit it, and the trip. Putting labour on top makes the whole document
 * read as a bill for time.
 *
 * Lines with nothing in them are dropped rather than carried through as zeros:
 * a quotation that lists "0 x nothing" is a quotation someone has to tidy up
 * before sending, which defeats the point of generating it from the report.
 *
 * Pure on purpose — the labels arrive translated from the caller, so the rules
 * can be read and tested without a framework around them.
 */
final class BuildQuotationLinesFromInterventionAction
{
    use QueueableAction;

    /**
     * @param  list<array{description: string, quantity: float, unitPrice: float}>  $materials
     * @return list<DictatedQuotationItem>
     */
    public function execute(
        array $materials,
        float $labourAmount,
        float $calloutFee,
        string $labourLabel,
        string $calloutLabel,
    ): array {
        $lines = [];

        foreach ($materials as $material) {
            $description = trim($material['description']);

            if ($description === '' || $material['quantity'] <= 0.0) {
                continue;
            }

            $lines[] = new DictatedQuotationItem(
                description: $description,
                quantity: $material['quantity'],
                unitPrice: $material['unitPrice'],
            );
        }

        // Labour and the callout arrive already totalled, so they go in as a
        // single unit each: splitting them into hours would invent a rate that
        // nobody entered.
        if ($labourAmount > 0.0) {
            $lines[] = new DictatedQuotationItem(
                description: $labourLabel,
                quantity: 1.0,
                unitPrice: $labourAmount,
            );
        }

        if ($calloutFee > 0.0) {
            $lines[] = new DictatedQuotationItem(
                description: $calloutLabel,
                quantity: 1.0,
                unitPrice: $calloutFee,
            );
        }

        return $lines;
    }

    /**
     * @param  list<DictatedQuotationItem>  $lines
     */
    public function total(array $lines): float
    {
        $total = 0.0;

        foreach ($lines as $line) {
            $total += $line->lineTotal();
        }

        return round($total, 2);
    }
}
