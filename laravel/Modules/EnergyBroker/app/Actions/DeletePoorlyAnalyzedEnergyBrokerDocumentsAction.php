<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Actions;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Modules\EnergyBroker\Models\EnergyBrokerDocument;
use Spatie\QueueableAction\QueueableAction;

final class DeletePoorlyAnalyzedEnergyBrokerDocumentsAction
{
    use QueueableAction;

    /**
     * @param  iterable<int, EnergyBrokerDocument>  $documents
     * @return array{deleted: int, skipped: int}
     */
    public function execute(iterable $documents, float $minimumConfidence = 0.60): array
    {
        $deleted = 0;
        $skipped = 0;

        foreach ($documents as $document) {
            if (! $document instanceof EnergyBrokerDocument || ! $this->isPoorlyAnalyzed($document, $minimumConfidence)) {
                $skipped++;

                continue;
            }

            $document->media?->delete();
            $document->delete();
            $deleted++;
        }

        return [
            'deleted' => $deleted,
            'skipped' => $skipped,
        ];
    }

    /**
     * @return array{deleted: int, skipped: int}
     */
    public function executeAll(float $minimumConfidence = 0.60): array
    {
        $deleted = 0;
        $skipped = 0;

        $this->poorlyAnalyzedQuery($minimumConfidence)
            ->with('media')
            ->chunkById(100, function (Collection $documents) use (&$deleted, &$skipped, $minimumConfidence): void {
                $result = $this->execute($documents, $minimumConfidence);
                $deleted += $result['deleted'];
                $skipped += $result['skipped'];
            });

        return [
            'deleted' => $deleted,
            'skipped' => $skipped,
        ];
    }

    /**
     * @return Builder<EnergyBrokerDocument>
     */
    public function poorlyAnalyzedQuery(float $minimumConfidence = 0.60): Builder
    {
        return EnergyBrokerDocument::query()
            ->where('analysis_status', '!=', 'imported')
            ->where(function (Builder $query) use ($minimumConfidence): void {
                $query
                    ->whereIn('analysis_status', ['failed', 'needs_review'])
                    ->orWhere('document_type', 'unknown')
                    ->orWhere('service_type', 'unknown')
                    ->orWhereNull('processed_at')
                    ->orWhereNull('raw_text')
                    ->orWhereNull('normalized_payload')
                    ->orWhere(function (Builder $confidenceQuery) use ($minimumConfidence): void {
                        $confidenceQuery
                            ->whereNotNull('confidence_score')
                            ->where('confidence_score', '<', $minimumConfidence);
                    });
            });
    }

    public function isPoorlyAnalyzed(EnergyBrokerDocument $document, float $minimumConfidence = 0.60): bool
    {
        if ($document->analysis_status === 'imported') {
            return false;
        }

        if (in_array($document->analysis_status, ['failed', 'needs_review'], true)) {
            return true;
        }

        if (in_array($document->document_type, ['', 'unknown'], true) || in_array((string) $document->service_type, ['', 'unknown'], true)) {
            return true;
        }

        if ($document->processed_at === null || trim((string) $document->raw_text) === '') {
            return true;
        }

        if (! is_array($document->normalized_payload) || $document->normalized_payload === []) {
            return true;
        }

        $confidence = $document->confidence_score;
        if ($confidence !== null && (float) $confidence < $minimumConfidence) {
            return true;
        }

        return false;
    }
}
