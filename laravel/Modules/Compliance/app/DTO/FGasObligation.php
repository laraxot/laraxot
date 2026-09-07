<?php

declare(strict_types=1);

namespace Modules\Compliance\DTO;

use DateTimeImmutable;

/**
 * What Regulation (EU) 517/2014 requires of one piece of equipment: whether it
 * has to be leak-checked at all, how often, and when the next check falls.
 */
final readonly class FGasObligation
{
    /** @var string */
    public const EXEMPT_BELOW_THRESHOLD = 'below_threshold';

    /** @var string */
    public const EXEMPT_HERMETIC = 'hermetically_sealed';

    /** @var string */
    public const UNKNOWN_REFRIGERANT = 'unknown_refrigerant';

    public function __construct(
        public string $refrigerantCode,
        public float $chargeKg,
        public ?float $co2EquivalentTonnes,
        public ?int $checkIntervalMonths,
        public ?DateTimeImmutable $nextCheckDueOn,
        public ?string $exemptionReason,
    ) {}

    public function requiresLeakChecks(): bool
    {
        return $this->checkIntervalMonths !== null;
    }

    /**
     * Equipment whose gas is not in the Regulation's tables. It is not exempt —
     * it simply cannot be assessed until someone identifies the refrigerant.
     */
    public function isUnassessable(): bool
    {
        return $this->exemptionReason === self::UNKNOWN_REFRIGERANT;
    }

    public function isOverdue(DateTimeImmutable $asOf): bool
    {
        if ($this->nextCheckDueOn === null) {
            return false;
        }

        return $this->nextCheckDueOn->setTime(0, 0) < $asOf->setTime(0, 0);
    }
}
