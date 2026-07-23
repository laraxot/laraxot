<?php

declare(strict_types=1);

namespace Modules\Quotation\AI;

/**
 * Turns a raw voice-note transcript into a loosely-structured quotation draft
 * (target customer, header, line items).
 *
 * Implementations call an LLM. The returned array is intentionally untyped and
 * untrusted: {@see \Modules\Quotation\Actions\BuildQuotationDraftAction}
 * validates and normalizes it into a typed draft.
 */
interface QuotationDraftExtractorInterface
{
    /**
     * @return array<string, mixed>
     */
    public function extract(string $transcript): array;
}
