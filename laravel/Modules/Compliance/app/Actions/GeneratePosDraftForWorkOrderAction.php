<?php

declare(strict_types=1);

namespace Modules\Compliance\Actions;

use Modules\Compliance\Models\ComplianceDocument;
use Modules\Compliance\Models\Equipment;
use Modules\WorkOrder\Models\WorkOrder;
use Spatie\QueueableAction\QueueableAction;

use function Safe\json_encode;

final class GeneratePosDraftForWorkOrderAction
{
    use QueueableAction;

    public function execute(WorkOrder $workOrder): ComplianceDocument
    {
        $workOrder->loadMissing(['customer', 'site', 'assignments.user']);

        $workers = $workOrder->assignments
            ->map(static fn ($assignment): string => trim((string) ($assignment->user->name ?? 'Operatore assegnato')))
            ->filter()
            ->values()
            ->all();

        $equipment = Equipment::query()
            ->where('assigned_work_order_id', $workOrder->id)
            ->orderBy('name')
            ->get(['name', 'type', 'serial_number'])
            ->map(static fn (Equipment $item): array => [
                'name' => $item->name,
                'type' => $item->type,
                'serial_number' => $item->serial_number,
            ])
            ->all();

        $draft = [
            'source' => 'D.I. 9 settembre 2014 - modello semplificato POS; contenuti minimi Allegato XV D.Lgs. 81/2008',
            'work_order' => [
                'id' => $workOrder->id,
                'title' => $workOrder->title,
                'customer' => $workOrder->customer?->legal_name,
                'site' => $workOrder->site?->name,
                'description' => $workOrder->description,
            ],
            'sections' => [
                'dati_impresa' => [
                    'title' => 'Dati identificativi impresa',
                    'fields' => ['datore_lavoro', 'sede_legale', 'recapiti', 'rspp', 'medico_competente', 'addetti_emergenze'],
                ],
                'cantiere_lavorazioni' => [
                    'title' => 'Cantiere, attivita e lavorazioni',
                    'fields' => ['descrizione_lavori', 'turni', 'modalita_organizzative', 'lavorazioni_specifiche'],
                    'prefill' => [
                        'descrizione_lavori' => $workOrder->description,
                    ],
                ],
                'lavoratori' => [
                    'title' => 'Lavoratori e mansioni',
                    'prefill' => $workers,
                ],
                'attrezzature' => [
                    'title' => 'Macchine, impianti, opere provvisionali e attrezzature',
                    'prefill' => $equipment,
                ],
                'sostanze' => [
                    'title' => 'Sostanze e miscele pericolose',
                    'fields' => ['nome', 'scheda_sicurezza', 'misure_prevenzione'],
                ],
                'rumore_dpi_formazione' => [
                    'title' => 'Rumore, DPI, informazione e formazione',
                    'fields' => ['valutazione_rumore', 'dpi_forniti', 'formazione_lavoratori'],
                ],
                'misure_integrative_psc' => [
                    'title' => 'Misure integrative e procedure richieste dal PSC',
                    'fields' => ['misure_preventive', 'procedure_complementari', 'rischi_interferenti'],
                ],
            ],
            'disclaimer' => 'Bozza operativa da verificare e firmare da soggetto competente. Non sostituisce valutazione professionale sicurezza.',
        ];

        /** @var ComplianceDocument $document */
        $document = ComplianceDocument::query()->updateOrCreate(
            [
                'work_order_id' => $workOrder->id,
                'type' => 'pos',
            ],
            [
                'title' => 'Bozza POS - '.$workOrder->title,
                'status' => 'missing',
                'is_required' => true,
                'meta' => ['pos_draft' => $draft],
                'notes' => $this->toMarkdown($draft),
            ],
        );

        return $document;
    }

    /**
     * @param  array<string, mixed>  $draft
     */
    private function toMarkdown(array $draft): string
    {
        $workOrder = is_array($draft['work_order'] ?? null) ? $draft['work_order'] : [];
        $sections = is_array($draft['sections'] ?? null) ? $draft['sections'] : [];
        $lines = [
            '# Bozza POS',
            '',
            '**Fonte struttura:** '.(string) ($draft['source'] ?? ''),
            '**Commessa:** '.(string) ($workOrder['title'] ?? ''),
            '**Cliente:** '.(string) ($workOrder['customer'] ?? ''),
            '',
        ];

        foreach ($sections as $section) {
            if (! is_array($section)) {
                continue;
            }
            $lines[] = '## '.(string) ($section['title'] ?? 'Sezione POS');
            $fields = is_array($section['fields'] ?? null) ? $section['fields'] : [];
            foreach ($fields as $field) {
                $lines[] = '- '.str_replace('_', ' ', (string) $field).':';
            }
            $prefill = $section['prefill'] ?? null;
            if (is_array($prefill) && $prefill !== []) {
                $lines[] = '- Dati precompilati: '.json_encode($prefill, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            } elseif (is_string($prefill) && trim($prefill) !== '') {
                $lines[] = '- Dati precompilati: '.$prefill;
            }
            $lines[] = '';
        }

        $lines[] = '> '.(string) ($draft['disclaimer'] ?? '');

        return implode("\n", $lines);
    }
}
