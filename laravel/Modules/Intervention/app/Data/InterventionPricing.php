<?php

declare(strict_types=1);

namespace Modules\Intervention\Data;

/**
 * What the report wants to write on the economic fields of an intervention.
 *
 * A null means "leave it as it is", not "set it to zero": these values are
 * merged onto a record that a human may already have filled in, and silently
 * overwriting someone's figure with a derived one is the worst thing this code
 * could do.
 */
final readonly class InterventionPricing
{
    public function __construct(
        public ?float $amount = null,
        public ?float $calloutFee = null,
        public ?int $travelKm = null,
        public ?string $surchargeCode = null,
        public int $surchargePercent = 0,
        public bool $labourNeedsTariff = false,
    ) {}

    /**
     * Only the fields that carry a value, ready to be merged into the model.
     *
     * @return array<string, float|int>
     */
    public function attributes(): array
    {
        $attributes = [];

        if ($this->amount !== null) {
            $attributes['amount'] = $this->amount;
        }

        if ($this->calloutFee !== null) {
            $attributes['callout_fee'] = $this->calloutFee;
        }

        if ($this->travelKm !== null) {
            $attributes['travel_km'] = $this->travelKm;
        }

        return $attributes;
    }
}
