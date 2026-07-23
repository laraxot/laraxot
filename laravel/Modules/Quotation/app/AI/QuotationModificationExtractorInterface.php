<?php

declare(strict_types=1);

namespace Modules\Quotation\AI;

/**
 * Given a spoken request AND the existing quotation structure, returns the edit
 * operations to apply (change customer, add/replace/remove lines). This lets the
 * AI recognize how the source quotation is made and modify only what's needed,
 * keeping the untouched lines verbatim — instead of recreating from scratch.
 *
 * The returned array is untyped/untrusted: {@see \Modules\Quotation\Actions\BuildQuotationModificationAction}
 * validates and applies it.
 */
interface QuotationModificationExtractorInterface
{
    /**
     * @param  array<string, mixed>  $context  ['items' => list<array>, 'customer' => array|null]
     * @return array<string, mixed>
     */
    public function extract(string $transcript, array $context): array;
}
