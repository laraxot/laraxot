<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Actions;

use Carbon\CarbonImmutable;
use Modules\Compliance\Models\ComplianceDocument;
use Modules\PublicProcurement\Models\PublicProcurementDocumentRequirement;
use Modules\PublicProcurement\Models\PublicProcurementTenderEligibilityCheck;
use Modules\PublicProcurement\Models\PublicProcurementTenderOpportunity;

final class EvaluateTenderEligibilityAction
{
    /** @var array<string, string> */
    private const DEFAULT_DOCUMENTS = [
        'durc' => 'DURC valido',
        'insurance' => 'Polizza assicurativa valida',
    ];

    /**
     * @param array<string, string> $requiredDocuments
     */
    public function execute(
        PublicProcurementTenderOpportunity $opportunity,
        ?int $evaluatedByUserId = null,
        array $requiredDocuments = [],
    ): PublicProcurementTenderEligibilityCheck {
        $requirements = $requiredDocuments !== [] ? $requiredDocuments : $this->requiredDocumentsFor($opportunity);
        $missing = [];
        $expired = [];
        $warnings = [];
        $actions = [];

        foreach ($requirements as $type => $label) {
            $document = $this->latestComplianceDocument((string) $type);
            if (! $document instanceof ComplianceDocument) {
                $missing[] = ['type' => (string) $type, 'label' => (string) $label];
                $actions[] = 'Caricare o aggiornare: ' . (string) $label;
                $this->upsertRequirement($opportunity, (string) $type, (string) $label, 'missing');
                continue;
            }

            $status = $document->computedStatus();
            if ($status === 'expired') {
                $expired[] = ['type' => (string) $type, 'label' => (string) $label, 'document_id' => $document->id];
                $actions[] = 'Rinnovare documento scaduto: ' . (string) $label;
                $this->upsertRequirement($opportunity, (string) $type, (string) $label, 'expired', $document);
                continue;
            }

            if ($status === 'expiring') {
                $warnings[] = 'Documento in scadenza: ' . (string) $label;
            }

            $this->upsertRequirement($opportunity, (string) $type, (string) $label, 'ok', $document);
        }

        $deadline = $opportunity->submission_deadline_at;
        if ($deadline !== null && $deadline->isPast()) {
            $warnings[] = 'La scadenza di presentazione offerta è già superata.';
            $actions[] = 'Verificare se la gara è ancora partecipabile o archiviarla.';
        } elseif ($deadline !== null && $deadline->isBetween(now(), now()->addDays(7))) {
            $warnings[] = 'Scadenza gara entro 7 giorni.';
        }

        $requiredSoa = (bool) data_get($opportunity->ai_summary, 'requires_soa', false);
        if ($requiredSoa) {
            $warnings[] = 'Il bando sembra richiedere attestazione SOA/categoria lavori: verificare manualmente.';
            $actions[] = 'Verificare categoria SOA e classifica richiesta dal disciplinare.';
        }

        $blockingCount = count($missing) + count($expired);
        $status = match (true) {
            $blockingCount === 0 && $warnings === [] => 'eligible',
            $blockingCount === 0 => 'eligible_with_actions',
            default => 'not_eligible',
        };
        $score = max(0, 100 - ($blockingCount * 35) - (count($warnings) * 10));

        /** @var PublicProcurementTenderEligibilityCheck $check */
        $check = PublicProcurementTenderEligibilityCheck::query()->create([
            'tender_opportunity_id' => $opportunity->id,
            'evaluated_by_user_id' => $evaluatedByUserId,
            'status' => $status,
            'score' => $score,
            'required_documents' => $requirements,
            'missing_documents' => $missing,
            'expired_documents' => $expired,
            'warnings' => $warnings,
            'actions' => $actions,
            'evaluated_at' => CarbonImmutable::now(),
            'summary' => $this->summaryFor($status, $missing, $expired, $warnings),
        ]);

        $opportunity->forceFill([
            'status' => match ($status) {
                'eligible' => 'interesting',
                'eligible_with_actions' => 'needs_documents',
                default => 'blocked',
            },
        ])->save();

        return $check;
    }

    /**
     * @return array<string, string>
     */
    private function requiredDocumentsFor(PublicProcurementTenderOpportunity $opportunity): array
    {
        $fromAi = data_get($opportunity->ai_summary, 'required_documents');
        if (is_array($fromAi) && $fromAi !== []) {
            $documents = [];
            foreach ($fromAi as $key => $value) {
                if (is_string($key) && is_string($value)) {
                    $documents[$key] = $value;
                } elseif (is_string($value)) {
                    $documents[str($value)->slug('_')->toString()] = $value;
                }
            }

            if ($documents !== []) {
                return $documents;
            }
        }

        return self::DEFAULT_DOCUMENTS;
    }

    private function latestComplianceDocument(string $type): ?ComplianceDocument
    {
        /** @var ComplianceDocument|null $document */
        $document = ComplianceDocument::query()
            ->where('type', $type)
            ->whereIn('status', ['valid', 'expiring', 'expired'])
            ->latest('id')
            ->first();

        return $document;
    }

    private function upsertRequirement(
        PublicProcurementTenderOpportunity $opportunity,
        string $code,
        string $title,
        string $status,
        ?ComplianceDocument $document = null,
    ): void {
        PublicProcurementDocumentRequirement::query()->updateOrCreate(
            [
                'tender_opportunity_id' => $opportunity->id,
                'code' => $code,
                'phase' => 'participation',
            ],
            [
                'title' => $title,
                'status' => $status,
                'severity' => $status === 'ok' ? 'info' : 'blocking',
                'is_required' => true,
                'compliance_document_id' => $document?->id,
            ],
        );
    }

    /**
     * @param array<int, array<string, mixed>> $missing
     * @param array<int, array<string, mixed>> $expired
     * @param array<int, string> $warnings
     */
    private function summaryFor(string $status, array $missing, array $expired, array $warnings): string
    {
        return match ($status) {
            'eligible' => 'La gara risulta partecipabile con i documenti disponibili.',
            'eligible_with_actions' => 'La gara è partecipabile, ma richiede verifiche o integrazioni: ' . implode('; ', $warnings),
            default => sprintf('Non partecipabile finché non vengono risolti %d documenti mancanti e %d scaduti.', count($missing), count($expired)),
        };
    }
}
