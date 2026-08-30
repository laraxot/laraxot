<?php

declare(strict_types=1);

namespace Modules\Intervention\Actions;

use DateTimeInterface;
use Modules\Intervention\Data\LabourQuote;
use Modules\Intervention\Data\LabourTariff;
use Spatie\QueueableAction\QueueableAction;

/**
 * Turns worked minutes into money, applying the out-of-hours surcharge that the
 * intervention actually falls into.
 *
 * This is the piece that was missing: the schema has carried an `amount` column
 * for labour since March, but nothing ever filled it, because no selling rate
 * existed anywhere in the codebase. Interventions therefore reached the
 * customer report with materials only, and the hours were given away.
 *
 * Two decisions worth knowing about:
 *
 * - **Surcharges do not stack.** A Sunday night job gets the higher of the two
 *   percentages, not their sum. Cumulative surcharges are hard to defend on an
 *   invoice and are not how the trade bills; taking the maximum is both simpler
 *   and closer to what an artisan would write by hand.
 * - **Public holidays are not handled.** Recognising Italian holidays needs a
 *   calendar (Easter moves, patron saints are municipal) and getting it half
 *   right would be worse than leaving it out: the operator can still raise the
 *   amount by hand on the draft.
 */
final class CalculateLabourAmountAction
{
    use QueueableAction;

    public function execute(int $workMinutes, LabourTariff $tariff, ?DateTimeInterface $performedAt = null): LabourQuote
    {
        if ($workMinutes <= 0 || ! $tariff->pricesLabour()) {
            return new LabourQuote(0.0);
        }

        // Cents throughout: minutes divided by 60 and multiplied by a rate is
        // exactly the shape that leaves 0.005 rounding errors on a float.
        $baseCents = (int) round($workMinutes * $tariff->hourlyPriceCents / 60);

        [$code, $percent] = $this->resolveSurcharge($tariff, $performedAt);

        $totalCents = $percent > 0
            ? (int) round($baseCents * (100 + $percent) / 100)
            : $baseCents;

        return new LabourQuote(
            amount: round($totalCents / 100, 2),
            surchargeCode: $percent > 0 ? $code : null,
            surchargePercent: $percent,
        );
    }

    /**
     * @return array{0: ?string, 1: int}
     */
    private function resolveSurcharge(LabourTariff $tariff, ?DateTimeInterface $performedAt): array
    {
        if ($performedAt === null) {
            return [null, 0];
        }

        $candidates = [];

        if ($tariff->isNightHour((int) $performedAt->format('G'))) {
            $candidates[LabourQuote::SURCHARGE_NIGHT] = $tariff->nightPercent;
        }

        // 6 = Saturday, 7 = Sunday in ISO-8601.
        if ($tariff->weekendPercent > 0 && (int) $performedAt->format('N') >= 6) {
            $candidates[LabourQuote::SURCHARGE_WEEKEND] = $tariff->weekendPercent;
        }

        if ($candidates === []) {
            return [null, 0];
        }

        $maxPercent = max($candidates);
        $code = (string) array_search($maxPercent, $candidates, true);

        return [$code, $maxPercent];
    }
}
