<?php

declare(strict_types=1);

namespace Modules\Quotation\Import;

/**
 * Rewrites one textual part of a quotation document (e.g. an ODS content.xml, a
 * DOCX word/document.xml, or a CSV/TXT body) so it reflects a NEW quotation —
 * customer, line items, prices and totals — while preserving the original layout.
 *
 * The concrete implementation is AI-backed: it sees the actual document content
 * (so it copes with text split across runs and numeric value attributes) and the
 * target quotation data. Returns the modified content, or null when it can't help
 * (no LLM configured, empty/blocked response) so the caller can fall back.
 */
interface QuotationDocumentRewriterInterface
{
    /**
     * @param  string  $content  the raw part content (XML or plain text)
     * @param  string  $partName  which part it is (e.g. "content.xml"), for context
     * @param  array<string, mixed>  $quotation  the new quotation data to inject
     */
    public function rewrite(string $content, string $partName, array $quotation): ?string;
}
