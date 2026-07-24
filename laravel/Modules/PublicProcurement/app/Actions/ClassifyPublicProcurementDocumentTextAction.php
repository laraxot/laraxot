<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Actions;

use Spatie\QueueableAction\QueueableAction;

final class ClassifyPublicProcurementDocumentTextAction
{
    use QueueableAction;

    /**
     * @return array{type: string, confidence: float, title: string, signals: array<int, string>}
     */
    public function execute(string $filename = '', string $text = ''): array
    {
        $haystack = mb_strtolower($filename.' '.$text);
        $rules = [
            'tender_notice' => ['bando', 'gara', 'procedura aperta', 'procedura negoziata', 'offerta economicamente'],
            'disciplinary' => ['disciplinare', 'criteri di aggiudicazione', 'requisiti di partecipazione'],
            'specifications' => ['capitolato', 'capitolato speciale', 'prestazionale'],
            'price_list' => ['elenco prezzi', 'prezzario', 'computo metrico', 'voce', 'quantita'],
            'progress_report' => ['stato avanzamento lavori', ' sal ', 'certificato di pagamento'],
            'official_record' => ['verbale', 'consegna lavori', 'ultimazione lavori', 'ordine di servizio'],
            'guarantee' => ['cauzione', 'fideiussione', 'garanzia provvisoria', 'garanzia definitiva'],
            'pec' => ['posta elettronica certificata', ' pec ', 'protocollo'],
        ];

        $bestType = 'other';
        $bestSignals = [];
        foreach ($rules as $type => $needles) {
            $signals = array_values(array_filter($needles, static fn (string $needle): bool => str_contains($haystack, $needle)));
            if (count($signals) > count($bestSignals)) {
                $bestType = $type;
                $bestSignals = $signals;
            }
        }

        return [
            'type' => $bestType,
            'confidence' => min(0.25 + (count($bestSignals) * 0.2), 0.95),
            'title' => $this->titleFor($bestType),
            'signals' => $bestSignals,
        ];
    }

    private function titleFor(string $type): string
    {
        return match ($type) {
            'tender_notice' => 'Bando di gara',
            'disciplinary' => 'Disciplinare di gara',
            'specifications' => 'Capitolato',
            'price_list' => 'Computo / elenco prezzi',
            'progress_report' => 'SAL / certificato',
            'official_record' => 'Verbale o atto ufficiale',
            'guarantee' => 'Cauzione / fideiussione',
            'pec' => 'PEC / protocollo',
            default => 'Documento appalto',
        };
    }
}
