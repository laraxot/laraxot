<?php

declare(strict_types=1);

namespace Modules\Signage\Datas;

/**
 * The priced quotation for a sign spec: the list of lines plus the rolled-up
 * totals. `hasMissing` is true when at least one line still needs operator input.
 */
final readonly class SignQuotation
{
    /**
     * @param  list<SignQuotationLine>  $lines
     */
    public function __construct(
        public ?int $signSpecId,
        public string $currency,
        public array $lines,
        public float $totalCost,
        public float $markupPercent,
        public float $totalSell,
        public bool $hasMissing,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'sign_spec_id' => $this->signSpecId,
            'currency' => $this->currency,
            'lines' => array_map(static fn (SignQuotationLine $l): array => $l->toArray(), $this->lines),
            'total_cost' => $this->totalCost,
            'markup_percent' => $this->markupPercent,
            'total_sell' => $this->totalSell,
            'has_missing' => $this->hasMissing,
        ];
    }
}
