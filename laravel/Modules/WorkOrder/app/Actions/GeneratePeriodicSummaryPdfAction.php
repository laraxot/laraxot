<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Actions;

use Carbon\CarbonImmutable;
use Modules\WorkOrder\Support\DocumentExportBridge;
use Spatie\QueueableAction\QueueableAction;

/**
 * Renderizza il resoconto di periodo (clienti, commesse, interventi,
 * materiali, preventivi) come PDF orizzontale.
 */
final class GeneratePeriodicSummaryPdfAction
{
    use QueueableAction;

    /**
     * @return array{content: string, mime: string, filename: string}
     */
    public function execute(CarbonImmutable $from, CarbonImmutable $to): array
    {
        $summary = app(BuildPeriodicSummaryAction::class)->execute($from, $to);

        $content = DocumentExportBridge::pdfFromView(
            'workorder::reports.resoconto-periodico',
            $summary,
            paper: 'a4',
            orientation: 'landscape',
        );

        return [
            'content' => $content,
            'mime' => 'application/pdf',
            'filename' => sprintf(
                'resoconto_periodico_%s_%s.pdf',
                $from->format('Ymd'),
                $to->format('Ymd'),
            ),
        ];
    }
}
