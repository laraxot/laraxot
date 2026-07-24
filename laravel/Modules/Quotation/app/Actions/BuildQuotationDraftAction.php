<?php

declare(strict_types=1);

namespace Modules\Quotation\Actions;

use Modules\Customer\Support\CustomerSimilarity;
use Modules\Quotation\AI\QuotationDraftExtractorInterface;
use Modules\Quotation\DTO\DictatedQuotationItem;
use Modules\Quotation\DTO\QuotationCustomerDraft;
use Modules\Quotation\DTO\QuotationDraft;
use Modules\Quotation\Models\Quotation;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\QueueableAction\QueueableAction;

/**
 * Orchestrates voice → quotation draft: runs the LLM extractor over the
 * transcript, then normalizes and validates its untrusted output into a typed
 * draft with catalog-matched line items.
 *
 * When the operator references another customer's quotation ("come quello di X"),
 * that customer's latest quotation is copied as the base (verbatim), WITHOUT
 * setting X as the new customer — the operator picks the real recipient.
 */
final class BuildQuotationDraftAction
{
    use QueueableAction;

    public function __construct(
        private readonly QuotationDraftExtractorInterface $extractor,
        private readonly MatchDictatedItemsToCatalogAction $matcher,
        private readonly ?DuplicateQuotationAsDraftAction $duplicate = null,
    ) {}

    public function execute(string $transcript): QuotationDraft
    {
        $raw = $this->extractor->extract($transcript);

        // "Like the one for customer X": X's quotation is the template. By default we
        // only add the dictated items (X supplies the style + attachment); we copy X's
        // line items too ONLY when the operator asks to keep them all (keep_source_items).
        $basedOn = $this->parseNullableString($raw['based_on_customer'] ?? null);
        $source = $this->findSourceQuotation($basedOn);
        $keepSourceItems = $this->parseBool($raw['keep_source_items'] ?? null) === true;
        $baseItems = ($source !== null && $keepSourceItems)
            ? ($this->duplicate ?? app(DuplicateQuotationAsDraftAction::class))->execute($source)->items
            : [];
        $newItems = $this->matcher->execute($this->parseItems($raw['items'] ?? null));

        // Inherit the source's commercial terms (IVA, special conditions/validity in
        // notes) when the operator didn't dictate them, so they're "kept" like asked.
        $taxPercent = $this->parseNullablePositiveFloat($raw['tax_percent'] ?? null);
        $notes = $this->parseNullableString($raw['notes'] ?? null);
        if ($source !== null) {
            $taxPercent ??= is_numeric($source->tax_percent) ? (float) $source->tax_percent : null;
            $notes ??= $this->parseNullableString($source->notes);
        }

        return new QuotationDraft(
            customer: $this->parseCustomer($raw['customer'] ?? null),
            title: $this->parseNullableString($raw['title'] ?? null),
            notes: $notes,
            items: $this->mergeWithoutDuplicates($baseItems, $newItems),
            taxPercent: $taxPercent,
            technicalParams: $this->parseTechnicalParams($raw['technical_params'] ?? null),
            basedOnQuotationId: $source?->id !== null ? (int) $source->id : null,
            aiResponse: $raw,
        );
    }

    /**
     * The referenced customer's quotation to copy as base. Prefers one that has a
     * document attachment (so the new quotation can carry a copied/modified file),
     * falling back to the latest.
     */
    private function findSourceQuotation(?string $customerName): ?Quotation
    {
        if ($customerName === null) {
            return null;
        }

        // Typo-tolerant: the operator may dictate "Pinco Pallino" while the
        // customer is stored as "Pico Pallino" (from the imported file).
        $customer = CustomerSimilarity::find($customerName);
        if ($customer === null) {
            return null;
        }

        $withAttachment = Quotation::query()
            ->where('customer_id', $customer->id)
            ->whereIn('id', Media::query()
                ->where('model_type', Quotation::class)
                ->where('collection_name', 'documents')
                ->select('model_id'))
            ->orderByDesc('id')
            ->first();

        return $withAttachment
            ?? Quotation::query()->where('customer_id', $customer->id)->orderByDesc('id')->first();
    }

    /**
     * Appends the new lines to the base, skipping any that are already present
     * (same catalog product or same description) so copying a quotation and adding
     * an article it already contains doesn't produce a duplicate line.
     *
     * @param  list<DictatedQuotationItem>  $base
     * @param  list<DictatedQuotationItem>  $new
     * @return list<DictatedQuotationItem>
     */
    private function mergeWithoutDuplicates(array $base, array $new): array
    {
        /** @var array<int, true> $seenProducts */
        $seenProducts = [];
        /** @var array<string, true> $seenDescriptions */
        $seenDescriptions = [];

        $remember = static function (DictatedQuotationItem $item) use (&$seenProducts, &$seenDescriptions): void {
            if ($item->matchedProductId !== null) {
                $seenProducts[$item->matchedProductId] = true;
            }
            $seenDescriptions[trim(mb_strtolower($item->description))] = true;
        };

        $result = [];
        foreach ($base as $item) {
            $result[] = $item;
            $remember($item);
        }

        foreach ($new as $item) {
            $isDuplicate = ($item->matchedProductId !== null && isset($seenProducts[$item->matchedProductId]))
                || isset($seenDescriptions[trim(mb_strtolower($item->description))]);
            if ($isDuplicate) {
                continue;
            }
            $result[] = $item;
            $remember($item);
        }

        return $result;
    }

    /**
     * Keeps only the known technical parameters (from config) and coerces each to
     * its declared type, so untrusted LLM output cannot inject arbitrary keys.
     *
     * @return array<string, mixed>
     */
    private function parseTechnicalParams(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        /** @var list<array<string, mixed>> $definitions */
        $definitions = array_values(array_filter((array) config('quotation_technical.params', []), 'is_array'));

        $params = [];
        foreach ($definitions as $definition) {
            $key = is_string($definition['key'] ?? null) ? $definition['key'] : '';
            if ($key === '' || ! array_key_exists($key, $value)) {
                continue;
            }

            $type = is_string($definition['type'] ?? null) ? $definition['type'] : 'text';
            $raw = $value[$key];

            $params[$key] = match ($type) {
                'number' => is_numeric($raw) ? (float) $raw : null,
                'bool' => $this->parseBool($raw),
                default => is_scalar($raw) ? trim((string) $raw) : null,
            };
        }

        return array_filter($params, static fn (mixed $v): bool => $v !== null);
    }

    private function parseBool(mixed $value): ?bool
    {
        if (is_bool($value)) {
            return $value;
        }

        if (is_string($value)) {
            $normalized = mb_strtolower(trim($value));
            if (in_array($normalized, ['1', 'true', 'si', 'sì', 'yes', 'presente'], true)) {
                return true;
            }
            if (in_array($normalized, ['0', 'false', 'no', 'assente'], true)) {
                return false;
            }
        }

        if (is_numeric($value)) {
            return (float) $value !== 0.0;
        }

        return null;
    }

    private function parseCustomer(mixed $value): ?QuotationCustomerDraft
    {
        if (! is_array($value)) {
            return null;
        }

        $legalName = $this->parseString($value['legal_name'] ?? '');
        $vat = $this->parseNullableString($value['vat_number'] ?? null);

        if ($legalName === '' && $vat === null) {
            return null;
        }

        return new QuotationCustomerDraft(
            legalName: $legalName,
            vatNumber: $vat,
            taxCode: $this->parseNullableString($value['tax_code'] ?? null),
            email: $this->parseNullableString($value['email'] ?? null),
            phone: $this->parseNullableString($value['phone'] ?? null),
            addressLine1: $this->parseNullableString($value['address_line1'] ?? null),
            city: $this->parseNullableString($value['city'] ?? null),
            postalCode: $this->parseNullableString($value['postal_code'] ?? null),
            province: $this->parseNullableString($value['province'] ?? null),
            notes: $this->parseNullableString($value['notes'] ?? null),
        );
    }

    /**
     * @return list<DictatedQuotationItem>
     */
    private function parseItems(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $items = [];
        foreach ($value as $item) {
            if (! is_array($item)) {
                continue;
            }

            $description = $this->parseString($item['description'] ?? '');
            if ($description === '') {
                continue;
            }

            $quantity = $this->parseNullablePositiveFloat($item['quantity'] ?? null) ?? 1.0;

            $items[] = new DictatedQuotationItem(
                description: $description,
                quantity: $quantity,
                unitPrice: $this->parseNullablePositiveFloat($item['unit_price'] ?? null),
            );
        }

        return $items;
    }

    private function parseString(mixed $value): string
    {
        return is_string($value) ? trim($value) : '';
    }

    private function parseNullableString(mixed $value): ?string
    {
        $string = $this->parseString($value);

        return $string === '' ? null : $string;
    }

    private function parseNullablePositiveFloat(mixed $value): ?float
    {
        if (! is_numeric($value)) {
            return null;
        }

        $float = (float) $value;

        return $float > 0 ? $float : null;
    }
}
