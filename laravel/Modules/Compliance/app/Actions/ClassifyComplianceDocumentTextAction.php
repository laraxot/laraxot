<?php

declare(strict_types=1);

namespace Modules\Compliance\Actions;

final class ClassifyComplianceDocumentTextAction
{
    /**
     * @return array{type: string, confidence: float, title: string, signals: array<int, string>}
     */
    public function execute(string $filename = '', string $text = ''): array
    {
        $haystack = mb_strtolower($filename.' '.$text);
        $rules = [
            'durc' => ['durc', 'regolarita contributiva', 'inps', 'inail', 'cassa edile'],
            'pos' => ['piano operativo di sicurezza', ' pos ', 'impresa esecutrice'],
            'psc' => ['piano di sicurezza e coordinamento', ' psc ', 'coordinatore sicurezza'],
            'dvr' => ['documento di valutazione dei rischi', ' dvr ', 'valutazione rischi'],
            'dico' => ['dichiarazione di conformita', 'd.m. 37', 'dm 37', 'impianto realizzato'],
            'training' => ['attestato', 'formazione', 'corso sicurezza'],
            'medical_fitness' => ['idoneita sanitaria', 'medico competente', 'giudizio di idoneita'],
            'insurance' => ['polizza', 'assicurazione', 'responsabilita civile', 'massimale'],
            'building_permit' => ['cila', 'scia', 'permesso di costruire', 'titolo edilizio'],
        ];

        $bestType = 'other';
        $bestSignals = [];

        foreach ($rules as $type => $needles) {
            $signals = array_values(array_filter($needles, fn (string $needle): bool => str_contains($haystack, $needle)));
            if (count($signals) > count($bestSignals)) {
                $bestType = $type;
                $bestSignals = $signals;
            }
        }

        return [
            'type' => $bestType,
            'confidence' => min(0.25 + (count($bestSignals) * 0.25), 0.95),
            'title' => $this->titleFor($bestType),
            'signals' => $bestSignals,
        ];
    }

    private function titleFor(string $type): string
    {
        return match ($type) {
            'durc' => 'DURC',
            'pos' => 'POS',
            'psc' => 'PSC',
            'dvr' => 'DVR',
            'dico' => 'DICO',
            'training' => 'Attestato formazione',
            'medical_fitness' => 'Idoneita sanitaria',
            'insurance' => 'Polizza assicurativa',
            'building_permit' => 'Titolo edilizio',
            default => 'Documento compliance',
        };
    }
}
