<?php

declare(strict_types=1);

namespace Modules\Compliance\Actions;

use DateTimeImmutable;
use Modules\Compliance\DTO\FGasObligation;
use Modules\Compliance\DTO\ValueObjects\Refrigerant;

/**
 * Works out the leak-check duty for one piece of equipment under Regulation
 * (EU) 517/2014, article 4.
 *
 * The thresholds, all in tonnes of CO2 equivalent:
 *  - under 5: no check duty at all;
 *  - hermetically sealed and labelled as such, under 10: exempt (art. 4(2));
 *  - 5 to under 50: every 12 months;
 *  - 50 to under 500: every 6 months;
 *  - 500 and above: every 3 months.
 *
 * Article 4(4) doubles the interval for the last two bands when a leakage
 * detection system is fitted — and only those two. The 12-month band does not
 * stretch to 24, which is the mistake worth guarding against, because it would
 * let a whole year pass between checks that the law wants annual.
 */
final class CalculateFGasObligationAction
{
    public function execute(
        string $refrigerantCode,
        float $chargeKg,
        ?DateTimeImmutable $lastCheckedOn = null,
        bool $hasLeakDetectionSystem = false,
        bool $isHermeticallySealed = false,
    ): FGasObligation {
        $co2 = Refrigerant::co2EquivalentTonnes($refrigerantCode, $chargeKg);

        if ($co2 === null) {
            return new FGasObligation(
                refrigerantCode: $refrigerantCode,
                chargeKg: $chargeKg,
                co2EquivalentTonnes: null,
                checkIntervalMonths: null,
                nextCheckDueOn: null,
                exemptionReason: FGasObligation::UNKNOWN_REFRIGERANT,
            );
        }

        $exemption = $this->exemption($co2, $isHermeticallySealed);

        if ($exemption !== null) {
            return new FGasObligation(
                refrigerantCode: $refrigerantCode,
                chargeKg: $chargeKg,
                co2EquivalentTonnes: $co2,
                checkIntervalMonths: null,
                nextCheckDueOn: null,
                exemptionReason: $exemption,
            );
        }

        $interval = $this->intervalMonths($co2, $hasLeakDetectionSystem);

        return new FGasObligation(
            refrigerantCode: $refrigerantCode,
            chargeKg: $chargeKg,
            co2EquivalentTonnes: $co2,
            checkIntervalMonths: $interval,
            nextCheckDueOn: $lastCheckedOn?->setTime(0, 0)->modify('+'.$interval.' months'),
            exemptionReason: null,
        );
    }

    private function exemption(float $co2, bool $isHermeticallySealed): ?string
    {
        if ($isHermeticallySealed && $co2 < 10.0) {
            return FGasObligation::EXEMPT_HERMETIC;
        }

        if ($co2 < 5.0) {
            return FGasObligation::EXEMPT_BELOW_THRESHOLD;
        }

        return null;
    }

    private function intervalMonths(float $co2, bool $hasLeakDetectionSystem): int
    {
        if ($co2 < 50.0) {
            return 12;
        }

        if ($co2 < 500.0) {
            return $hasLeakDetectionSystem ? 12 : 6;
        }

        return $hasLeakDetectionSystem ? 6 : 3;
    }
}
