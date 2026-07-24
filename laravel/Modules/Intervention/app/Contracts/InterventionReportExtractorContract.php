<?php

declare(strict_types=1);

namespace Modules\Intervention\Contracts;

use Modules\Intervention\Actions\BuildInterventionReportDraftAction;

/**
 * Turns a raw voice-note transcript into a loosely-structured report draft.
 *
 * Implementations call an LLM. The returned array is intentionally untyped and
 * untrusted: {@see BuildInterventionReportDraftAction}
 * is responsible for validating and normalizing it into a typed draft.
 */
interface InterventionReportExtractorContract
{
    /**
     * @param  list<array<string, mixed>>  $pastInterventions  Few-shot examples taken from the customer's/work order's history.
     * @return array<string, mixed>
     */
    public function extract(string $transcript, array $pastInterventions): array;
}
