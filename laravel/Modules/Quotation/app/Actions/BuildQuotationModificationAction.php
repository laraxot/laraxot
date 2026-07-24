<?php

declare(strict_types=1);

namespace Modules\Quotation\Actions;

use Modules\Quotation\AI\QuotationModificationExtractorInterface;
use Modules\Quotation\DTO\DictatedQuotationItem;
use Modules\Quotation\DTO\QuotationCustomerDraft;
use Modules\Quotation\DTO\QuotationDraft;
use Spatie\QueueableAction\QueueableAction;

/**
 * Applies an AI-recognized modification to an existing quotation: the extractor
 * reads the source structure + the spoken request and returns edit operations
 * (remove / replace / add lines, change customer). Untouched lines are kept
 * VERBATIM (same description AND price — never re-matched), so copying a
 * quotation and tweaking it never degrades the parts left alone. Only added or
 * replaced lines are priced against the catalog.
 */
final class BuildQuotationModificationAction
{
    use QueueableAction;

    public function __construct(
        private readonly QuotationModificationExtractorInterface $extractor,
        private readonly MatchDictatedItemsToCatalogAction $matcher,
    ) {}

    /**
     * @param  list<DictatedQuotationItem>  $baseItems
     */
    public function execute(string $transcript, array $baseItems, ?QuotationCustomerDraft $baseCustomer): QuotationDraft
    {
        $ops = $this->extractor->extract($transcript, [
            'items' => array_map(
                static fn (DictatedQuotationItem $i): array => [
                    'description' => $i->description,
                    'quantity' => $i->quantity,
                    'unit_price' => $i->unitPrice,
                ],
                $baseItems,
            ),
            'customer' => $baseCustomer?->toCustomerAttributes(),
        ]);

        $items = $this->applyOps($baseItems, $ops);
        $customer = $this->parseCustomer($ops['customer'] ?? null) ?? $baseCustomer;

        return new QuotationDraft(customer: $customer, title: null, notes: null, items: $items);
    }

    /**
     * @param  list<DictatedQuotationItem>  $base
     * @param  array<string, mixed>  $ops
     * @return list<DictatedQuotationItem>
     */
    private function applyOps(array $base, array $ops): array
    {
        $items = $base; // preserved verbatim, never re-matched

        foreach ($this->list($ops['remove'] ?? null) as $keyword) {
            $needle = is_string($keyword) ? trim(mb_strtolower($keyword)) : '';
            if ($needle === '') {
                continue;
            }
            $items = array_values(array_filter(
                $items,
                static fn (DictatedQuotationItem $i): bool => ! str_contains(mb_strtolower($i->description), $needle),
            ));
        }

        foreach ($this->list($ops['replace'] ?? null) as $replacement) {
            if (! is_array($replacement)) {
                continue;
            }
            $new = $this->makeItem($replacement);
            if ($new === null) {
                continue;
            }

            $target = is_string($replacement['target'] ?? null) ? trim(mb_strtolower($replacement['target'])) : '';
            $replaced = false;
            if ($target !== '') {
                foreach ($items as $index => $item) {
                    if (str_contains(mb_strtolower($item->description), $target)) {
                        $items[$index] = $new;
                        $replaced = true;
                        break;
                    }
                }
            }
            if (! $replaced) {
                $items[] = $new;
            }
            $items = array_values($items);
        }

        foreach ($this->list($ops['add'] ?? null) as $addition) {
            if (is_array($addition)) {
                $new = $this->makeItem($addition);
                if ($new !== null) {
                    $items[] = $new;
                }
            }
        }

        return array_values($items);
    }

    /**
     * Builds and catalog-matches a single new/replaced line (only new lines are matched).
     *
     * @param  array<array-key, mixed>  $data
     */
    private function makeItem(array $data): ?DictatedQuotationItem
    {
        $description = is_string($data['description'] ?? null) ? trim($data['description']) : '';
        if ($description === '') {
            return null;
        }

        $quantity = $this->positiveFloat($data['quantity'] ?? null) ?? 1.0;
        $item = new DictatedQuotationItem($description, $quantity, $this->positiveFloat($data['unit_price'] ?? null));

        return $this->matcher->execute([$item])[0] ?? $item;
    }

    private function parseCustomer(mixed $value): ?QuotationCustomerDraft
    {
        if (! is_array($value)) {
            return null;
        }

        $legalName = is_string($value['legal_name'] ?? null) ? trim($value['legal_name']) : '';
        $vat = $this->nullableString($value['vat_number'] ?? null);
        if ($legalName === '' && $vat === null) {
            return null;
        }

        return new QuotationCustomerDraft(
            legalName: $legalName,
            vatNumber: $vat,
            email: $this->nullableString($value['email'] ?? null),
            phone: $this->nullableString($value['phone'] ?? null),
            city: $this->nullableString($value['city'] ?? null),
            postalCode: $this->nullableString($value['postal_code'] ?? null),
            province: $this->nullableString($value['province'] ?? null),
        );
    }

    /**
     * @return list<mixed>
     */
    private function list(mixed $value): array
    {
        return is_array($value) ? array_values($value) : [];
    }

    private function positiveFloat(mixed $value): ?float
    {
        if (! is_numeric($value)) {
            return null;
        }
        $float = (float) $value;

        return $float > 0 ? $float : null;
    }

    private function nullableString(mixed $value): ?string
    {
        $string = is_string($value) ? trim($value) : '';

        return $string === '' ? null : $string;
    }
}
