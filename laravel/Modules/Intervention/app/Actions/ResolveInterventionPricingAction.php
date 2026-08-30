<?php

declare(strict_types=1);

namespace Modules\Intervention\Actions;

use DateTimeInterface;
use Modules\Intervention\Data\InterventionPricing;
use Modules\Intervention\Data\InterventionReportDraft;
use Modules\Intervention\Data\LabourTariff;
use Spatie\QueueableAction\QueueableAction;

/**
 * Decides what the voice report may write on the money fields of an
 * intervention, and what it must leave alone.
 *
 * Pulled out of the persistence action on purpose: this is the part with the
 * rules, and rules that decide what a customer gets charged deserve to be
 * testable without a database.
 *
 * The precedence, from strongest to weakest:
 *
 *   1. a figure already on the record — typed by a person, never overwritten;
 *   2. a figure dictated out loud — the operator was on site, we were not;
 *   3. a figure derived from the configured tariff.
 *
 * A surcharge is reported only when the amount actually came from the tariff:
 * announcing "+50% weekend" over a price the operator stated himself would be
 * describing something that did not happen.
 */
final class ResolveInterventionPricingAction
{
    use QueueableAction;

    public function __construct(private readonly CalculateLabourAmountAction $calculateLabour) {}

    public function execute(
        ?float $currentAmount,
        ?float $currentCalloutFee,
        ?int $currentTravelKm,
        InterventionReportDraft $draft,
        LabourTariff $tariff,
        ?DateTimeInterface $performedAt = null,
        int $fallbackMinutes = 0,
    ): InterventionPricing {
        $amount = null;
        $surchargeCode = null;
        $surchargePercent = 0;
        $labourNeedsTariff = false;

        if ($this->isEmpty($currentAmount)) {
            if ($draft->labourAmount !== null) {
                $amount = $draft->labourAmount;
            } else {
                $minutes = $draft->workMinutes ?? $fallbackMinutes;
                $quote = $this->calculateLabour->execute($minutes, $tariff, $performedAt);

                if ($quote->amount > 0.0) {
                    $amount = $quote->amount;
                    $surchargeCode = $quote->surchargeCode;
                    $surchargePercent = $quote->surchargePercent;
                } elseif ($minutes > 0) {
                    // There are hours to bill and no rate to bill them at. The
                    // caller shows this instead of leaving the field mysteriously
                    // blank, which is how the hours end up given away.
                    $labourNeedsTariff = true;
                }
            }
        }

        $calloutFee = null;
        if ($this->isEmpty($currentCalloutFee)) {
            if ($draft->calloutFee !== null) {
                $calloutFee = $draft->calloutFee;
            } elseif ($tariff->calloutFeeCents > 0) {
                $calloutFee = round($tariff->calloutFeeCents / 100, 2);
            }
        }

        $travelKm = null;
        if ($draft->travelKm !== null && ($currentTravelKm === null || $currentTravelKm <= 0)) {
            $travelKm = $draft->travelKm;
        }

        return new InterventionPricing(
            amount: $amount,
            calloutFee: $calloutFee,
            travelKm: $travelKm,
            surchargeCode: $surchargeCode,
            surchargePercent: $surchargePercent,
            labourNeedsTariff: $labourNeedsTariff,
        );
    }

    private function isEmpty(?float $value): bool
    {
        return $value === null || $value <= 0.0;
    }
}
