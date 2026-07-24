<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Actions;

use Modules\PublicProcurement\Models\PublicProcurementAiExtraction;
use Modules\PublicProcurement\Models\PublicProcurementTenderOpportunity;
use Spatie\QueueableAction\QueueableAction;

final class ApplyTenderOpportunityDraftAction
{
    use QueueableAction;

    public function execute(PublicProcurementAiExtraction $draft): PublicProcurementTenderOpportunity
    {
        if ($draft->status !== 'draft') {
            throw new \RuntimeException('Bozza AI già applicata o non più valida.');
        }

        if ($draft->expires_at !== null && $draft->expires_at->isPast()) {
            throw new \RuntimeException('Bozza AI scaduta.');
        }

        $tender = is_array(data_get($draft->extracted_payload, 'tender'))
            ? data_get($draft->extracted_payload, 'tender')
            : [];

        /** @var PublicProcurementTenderOpportunity $opportunity */
        $opportunity = PublicProcurementTenderOpportunity::query()->create([
            'status' => 'to_evaluate',
            'contracting_authority' => $this->stringValue($tender['contracting_authority'] ?? null, 'Ente da verificare'),
            'title' => $this->stringValue($tender['title'] ?? null, 'Opportunità gara da verificare'),
            'cig' => $this->nullableString($tender['cig'] ?? null),
            'cup' => $this->nullableString($tender['cup'] ?? null),
            'category' => $this->nullableString($tender['category'] ?? null),
            'estimated_amount' => is_numeric($tender['estimated_amount'] ?? null) ? (float) $tender['estimated_amount'] : null,
            'submission_deadline_at' => $this->nullableString($tender['submission_deadline_at'] ?? null),
            'ai_summary' => [
                'document_type' => $draft->document_type,
                'required_documents' => data_get($draft->extracted_payload, 'required_documents', []),
                'recommendations' => data_get($draft->extracted_payload, 'recommendations', []),
                'confidence' => $draft->confidence,
            ],
            'raw_excerpt' => $draft->raw_text !== null ? mb_substr($draft->raw_text, 0, 2000) : null,
            'notes' => 'Creata da bozza AI '.$draft->public_id,
        ]);

        $draft->forceFill([
            'status' => 'applied',
            'applied_at' => now(),
        ])->save();

        return $opportunity;
    }

    private function stringValue(mixed $value, string $default): string
    {
        return is_string($value) && trim($value) !== '' ? trim($value) : $default;
    }

    private function nullableString(mixed $value): ?string
    {
        return is_string($value) && trim($value) !== '' ? trim($value) : null;
    }
}
