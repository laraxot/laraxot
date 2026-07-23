<?php

declare(strict_types=1);

namespace Modules\Quotation\Import;

/**
 * Adapts a quotation's commercial notes (validity, payment plans, promos) to a NEW
 * quotation: recomputes validity dates from today and installments from the new
 * total. AI-backed; returns null when it can't help so the caller keeps the notes.
 */
interface QuotationNotesRewriterInterface
{
    /**
     * @param  array<string, mixed>  $quotation  the new quotation data (today, total, ...)
     */
    public function rewrite(string $notes, array $quotation): ?string;
}
