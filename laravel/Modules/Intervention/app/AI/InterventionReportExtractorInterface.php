<?php

declare(strict_types=1);

namespace Modules\Intervention\AI;

/**
 * Turns a raw voice-note transcript into a loosely-structured report draft.
 *
 * Implementations call an LLM. The returned array is intentionally untyped and
 * untrusted: {@see \Modules\Intervention\Actions\BuildInterventionReportDraftAction}
 * is responsible for validating and normalizing it into a typed draft.
 */
interface InterventionReportExtractorInterface
{
    /**
     * @param  list<array<string, mixed>>  $pastInterventions  Few-shot examples taken from the customer's/work order's history.
     * @return array<string, mixed>
     */
    public function extract(string $transcript, array $pastInterventions): array;
}
