<?php

declare(strict_types=1);

namespace Modules\Intervention\Data;

/**
 * What the worked minutes are worth, and why.
 *
 * The surcharge is carried alongside the amount rather than folded silently
 * into it: an operator who sees "+50% notturno" on the draft can challenge it
 * before it reaches the customer, which is the whole point of the review step.
 */
final readonly class LabourQuote
{
    /** @var string */
    /** @var string */
    public const SURCHARGE_NIGHT = 'night';

    /** @var string */
    /** @var string */
    public const SURCHARGE_WEEKEND = 'weekend';

    public function __construct(
        public float $amount,
        public ?string $surchargeCode = null,
        public int $surchargePercent = 0,
    ) {}

    public function hasSurcharge(): bool
    {
        return $this->surchargeCode !== null && $this->surchargePercent > 0;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'surcharge_code' => $this->surchargeCode,
            'surcharge_percent' => $this->surchargePercent,
        ];
    }
}
