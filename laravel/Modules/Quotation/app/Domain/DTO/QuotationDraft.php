<?php

declare(strict_types=1);

namespace Modules\Quotation\Domain\DTO;

/**
 * A whole quotation reconstructed from voice (and optionally cloned from a source
 * quotation): the target customer, header fields and the line items with their
 * catalog matches. The Filament page lets the operator review it before it is
 * persisted into a new quotation.
 */
final readonly class QuotationDraft
{
    /**
     * @param  list<DictatedQuotationItem>  $items
     * @param  array<string, mixed>  $technicalParams
     * @param  array<string, mixed>|null  $aiResponse  raw LLM extraction (for transparency)
     */
    public function __construct(
        public ?QuotationCustomerDraft $customer,
        public ?string $title,
        public ?string $notes,
        public array $items,
        public ?float $taxPercent = null,
        public array $technicalParams = [],
        public ?int $basedOnQuotationId = null,
        public ?array $aiResponse = null,
    ) {}

    public function itemsTotal(): float
    {
        $total = 0.0;
        foreach ($this->items as $item) {
            $total += $item->lineTotal();
        }

        return round($total, 2);
    }

    public function hasUnmatchedItems(): bool
    {
        foreach ($this->items as $item) {
            if (! $item->isMatched()) {
                return true;
            }
        }

        return false;
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'customer' => $this->customer?->toCustomerAttributes(),
            'title' => $this->title,
            'notes' => $this->notes,
            'tax_percent' => $this->taxPercent,
            'technical_params' => $this->technicalParams,
            'items' => array_map(static fn (DictatedQuotationItem $item): array => $item->toArray(), $this->items),
            'items_total' => $this->itemsTotal(),
        ];
    }
}
