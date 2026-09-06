<?php

declare(strict_types=1);

namespace Modules\Thermo\Filament\Pages;

use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Features\SupportFileUploads\WithFileUploads;

use function Safe\file_get_contents;
use function Safe\preg_match;
use function Safe\preg_match_all;
use function Safe\preg_replace;

final class ThermoAiDocumentsPage extends ThermoPage
{
    use WithFileUploads;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationLabel = 'UI-08 - AI e PDF';

    protected static ?string $title = 'UI-08 - AI, fatture ricevute e documenti PDF';

    protected static ?string $slug = 'ai-documents';

    protected static ?int $navigationSort = 8;

    protected static ?string $thermoFeature = 'thermo.ai_documents';

    protected string $view = 'thermo::filament.pages.ai-documents';

    public string $documentType = 'invoice';

    /**
     * Livewire materializza il file di wire:model come TemporaryUploadedFile:
     * l'API e' nota e non va indovinata con method_exists().
     */
    public ?TemporaryUploadedFile $documentFile = null;

    /** @var array<string, mixed>|null */
    public ?array $extractedDocument = null;

    public function extractDemoDocument(): void
    {
        $this->validate([
            'documentType' => ['required', 'in:invoice,contract,order'],
            'documentFile' => ['required', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        $file = $this->documentFile;
        if (! $file instanceof TemporaryUploadedFile) {
            $this->addError('documentFile', 'Nessun file da analizzare.');

            return;
        }

        $path = (string) $file->getRealPath();

        try {
            $this->extractedDocument = $this->openAiProposal(
                filename: $file->getClientOriginalName(),
                mimeType: $file->getMimeType(),
                fileData: $path !== '' ? base64_encode(file_get_contents($path)) : '',
                fallbackContent: $path !== '' ? $this->readPdfLikeText($path) : '',
            );
        } catch (\Throwable $e) {
            $this->addError('documentFile', $e->getMessage());

            Notification::make()
                ->title('Estrazione OpenAI non riuscita')
                ->body($e->getMessage())
                ->danger()
                ->send();

            return;
        }

        Notification::make()
            ->title('Dati estratti in modalita demo')
            ->body('La proposta e pronta: puoi mostrarla e spiegare che in produzione verrebbe collegata ad AI/OCR reali.')
            ->success()
            ->send();
    }

    public function loadDemoInvoice(): void
    {
        $this->documentType = 'invoice';
        $this->documentFile = null;
        $this->extractedDocument = $this->invoiceProposal(
            'fattura-demo-grossista-termoidraulica.pdf',
            "Fornitore: Grossista Termoidraulica Srl\nFattura: FT-2026-1842\nData: 07/05/2026\nTotale: 1248,70\n",
        );
    }

    public function loadDemoOrder(): void
    {
        $this->documentType = 'order';
        $this->documentFile = null;
        $this->extractedDocument = $this->orderProposal(
            'ordine-fornitore-demo-caldaia.pdf',
            "Fornitore: Centro Assistenza Caldaie Nord\nOrdine: ORD-2026-077\nData consegna: 10/05/2026\nTotale: 890,00\n",
        );
    }

    public function resetDemoExtraction(): void
    {
        $this->reset(['documentFile', 'extractedDocument']);
    }

    /**
     * @return array<string, mixed>
     */
    private function invoiceProposal(string $filename, string $content): array
    {
        return [
            'type_label' => 'Fattura ricevuta',
            'source_file' => $filename,
            'confidence' => 'Demo 92%',
            'fields' => [
                ['label' => 'Fornitore', 'value' => $this->matchText($content, '/(?:fornitore|supplier)\s*[:\-]\s*([^\n\r]+)/i') ?? 'Grossista Termoidraulica Srl'],
                ['label' => 'Numero documento', 'value' => $this->matchText($content, '/(?:fattura|invoice|numero|n\.)\s*[:#\-]?\s*([A-Z0-9\/\-]{3,})/i') ?? $this->documentCodeFromFilename($filename, 'FT-2026-1842')],
                ['label' => 'Data documento', 'value' => $this->matchText($content, '/(?:data|date)\s*[:\-]\s*(\d{1,2}[\/\-]\d{1,2}[\/\-]\d{2,4})/i') ?? now()->format('d/m/Y')],
                ['label' => 'Totale', 'value' => $this->matchText($content, '/(?:totale|total)\s*[:\-]?\s*(?:eur|euro)?\s*([\d\.,]+)/i') ?? '1.248,70 euro'],
                ['label' => 'Collegamento suggerito', 'value' => 'Commessa Bianchi - installazione caldaia'],
                ['label' => 'Azione proposta', 'value' => 'Collega costo a commessa e aggiorna controllo margine'],
            ],
            'line_items' => [
                'Caldaia murale condensazione - 1 pz',
                'Raccordi e kit scarico fumi',
                'Materiale idraulico extra preventivo',
            ],
            'raw_excerpt' => $this->excerpt($content),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function openAiProposal(string $filename, string $mimeType, string $fileData, string $fallbackContent): array
    {
        $apiKeyValue = config('services.openai.key');
        $apiKey = is_string($apiKeyValue) ? trim($apiKeyValue) : '';
        if ($apiKey === '' || $apiKey === 'invalid key') {
            throw new \RuntimeException('OPENAI_API_KEY mancante in configurazione.');
        }

        if ($fileData === '') {
            throw new \RuntimeException('PDF non leggibile o vuoto.');
        }

        $baseUrlValue = config('compliance.ocr.openai.base_url', 'https://api.openai.com/v1/');
        $modelValue = config('compliance.ocr.openai.model', 'gpt-4o-mini');
        $timeoutValue = config('compliance.ocr.openai.timeout', 90);
        $baseUrl = is_string($baseUrlValue) ? rtrim($baseUrlValue, '/').'/' : 'https://api.openai.com/v1/';
        $model = is_string($modelValue) ? $modelValue : 'gpt-4o-mini';
        $timeout = is_int($timeoutValue) ? $timeoutValue : 90;

        $response = Http::withToken($apiKey)
            ->acceptJson()
            ->timeout($timeout)
            ->post($baseUrl.'responses', [
                'model' => $model,
                'temperature' => 0,
                'input' => [[
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'input_text',
                            'text' => $this->openAiPrompt($filename, $mimeType, $fallbackContent),
                        ],
                        [
                            'type' => 'input_file',
                            'filename' => $filename,
                            'file_data' => "data:{$mimeType};base64,{$fileData}",
                        ],
                    ],
                ]],
            ]);

        if (! $response->successful()) {
            throw new \RuntimeException('Errore OpenAI: '.mb_substr($response->body(), 0, 500));
        }

        $text = $this->extractOpenAiOutputText($response->json());
        $parsed = $this->parseJsonObject($text);
        if ($parsed === []) {
            throw new \RuntimeException('OpenAI non ha restituito JSON valido.');
        }

        return $this->proposalFromOpenAiJson($filename, $parsed, $text);
    }

    private function openAiPrompt(string $filename, string $mimeType, string $fallbackContent): string
    {
        return <<<PROMPT
Analizza questo PDF per una demo di gestionale termoidraulico italiano.
Devi estrarre dati da fattura ricevuta, ordine fornitore o contratto.
Restituisci SOLO JSON valido, senza markdown, con questa forma:
{
  "type_label": "Fattura ricevuta|Ordine fornitore|Contratto / accordo cliente|Documento PDF",
  "confidence": "AI 0-100%",
  "fields": [
    {"label": "Fornitore/Cliente", "value": ""},
    {"label": "Numero documento", "value": ""},
    {"label": "Data documento", "value": ""},
    {"label": "Totale", "value": ""},
    {"label": "Collegamento suggerito", "value": ""},
    {"label": "Azione proposta", "value": ""}
  ],
  "line_items": ["massimo 5 righe o punti riconosciuti"],
  "raw_excerpt": "breve estratto leggibile del documento"
}
Non inventare dati che non sono nel documento: se mancano, usa stringa vuota.
Per il collegamento suggerito proponi una commessa, preventivo, intervento o magazzino plausibile solo se il documento lo suggerisce; altrimenti scrivi "Da collegare manualmente".
File: {$filename}
MIME: {$mimeType}
Testo grezzo letto localmente, se utile:
{$fallbackContent}
PROMPT;
    }

    private function extractOpenAiOutputText(mixed $decoded): string
    {
        if (! is_array($decoded)) {
            return '';
        }

        $outputText = $decoded['output_text'] ?? null;
        if (is_string($outputText) && trim($outputText) !== '') {
            return trim($outputText);
        }

        $chunks = [];
        $outputs = $decoded['output'] ?? [];
        foreach (is_iterable($outputs) ? $outputs : [] as $output) {
            if (! is_array($output)) {
                continue;
            }
            $contents = $output['content'] ?? [];
            foreach (is_iterable($contents) ? $contents : [] as $content) {
                if (is_array($content) && isset($content['text']) && is_string($content['text'])) {
                    $chunks[] = $content['text'];
                }
            }
        }

        return trim(implode("\n", $chunks));
    }

    /**
     * @param  array<string, mixed>  $parsed
     * @return array<string, mixed>
     */
    private function proposalFromOpenAiJson(string $filename, array $parsed, string $rawText): array
    {
        $fields = is_array($parsed['fields'] ?? null) ? $parsed['fields'] : [];
        $normalizedFields = [];
        foreach ($fields as $field) {
            if (! is_array($field)) {
                continue;
            }
            $label = isset($field['label']) && is_string($field['label']) ? trim($field['label']) : '';
            $value = isset($field['value']) && is_scalar($field['value']) ? trim((string) $field['value']) : '';
            if ($label !== '') {
                $normalizedFields[] = ['label' => $label, 'value' => $value];
            }
        }

        if ($normalizedFields === []) {
            $normalizedFields[] = ['label' => 'Dati estratti', 'value' => 'Nessun campo strutturato restituito'];
        }

        $lineItems = [];
        foreach ((array) ($parsed['line_items'] ?? []) as $item) {
            if (is_scalar($item) && trim((string) $item) !== '') {
                $lineItems[] = trim((string) $item);
            }
        }

        return [
            'type_label' => is_string($parsed['type_label'] ?? null) && trim($parsed['type_label']) !== '' ? trim($parsed['type_label']) : 'Documento PDF',
            'source_file' => $filename,
            'confidence' => is_string($parsed['confidence'] ?? null) && trim($parsed['confidence']) !== '' ? trim($parsed['confidence']) : 'AI',
            'fields' => $normalizedFields,
            'line_items' => $lineItems !== [] ? $lineItems : ['Nessuna riga riconosciuta da OpenAI.'],
            'raw_excerpt' => is_string($parsed['raw_excerpt'] ?? null) && trim($parsed['raw_excerpt']) !== ''
                ? Str::limit(trim($parsed['raw_excerpt']), 240)
                : Str::limit($rawText, 240),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function orderProposal(string $filename, string $content): array
    {
        return [
            'type_label' => 'Ordine fornitore',
            'source_file' => $filename,
            'confidence' => 'Demo 90%',
            'fields' => [
                ['label' => 'Fornitore', 'value' => $this->matchText($content, '/(?:fornitore|supplier)\s*[:\-]\s*([^\n\r]+)/i') ?? 'Centro Assistenza Caldaie Nord'],
                ['label' => 'Numero ordine', 'value' => $this->matchText($content, '/(?:ordine|order|numero|n\.)\s*[:#\-]?\s*([A-Z0-9\/\-]{3,})/i') ?? $this->documentCodeFromFilename($filename, 'ORD-2026-077')],
                ['label' => 'Data consegna', 'value' => $this->matchText($content, '/(?:consegna|delivery|data)\s*[:\-]\s*(\d{1,2}[\/\-]\d{1,2}[\/\-]\d{2,4})/i') ?? now()->addDays(3)->format('d/m/Y')],
                ['label' => 'Totale ordine', 'value' => $this->matchText($content, '/(?:totale|total)\s*[:\-]?\s*(?:eur|euro)?\s*([\d\.,]+)/i') ?? '890,00 euro'],
                ['label' => 'Collegamento suggerito', 'value' => 'Preventivo Bianchi - sostituzione caldaia'],
                ['label' => 'Azione proposta', 'value' => 'Genera distinta materiali e aggiorna stato ordine fornitore'],
            ],
            'line_items' => [
                'Caldaia condensazione 24 kW - 1 pz',
                'Kit raccordi e scarico fumi',
                'Smaltimento vecchio generatore',
            ],
            'raw_excerpt' => $this->excerpt($content),
        ];
    }

    private function readPdfLikeText(string $path): string
    {
        if (! is_file($path)) {
            return '';
        }

        $raw = file_get_contents($path, false, null, 0, 120000);
        if ($raw === '') {
            return '';
        }
        preg_match_all('/[A-Za-z0-9À-ÿ][A-Za-z0-9À-ÿ\s.,;:\/\-_€]{4,}/u', $raw, $matches);
        $found = $matches[0];

        return trim(implode("\n", array_slice($found, 0, 40)));
    }

    private function matchText(string $content, string $pattern): ?string
    {
        if ($content === '' || preg_match($pattern, $content, $matches) !== 1) {
            return null;
        }

        return Str::limit(trim((string) ($matches[1] ?? '')), 80, '');
    }

    private function documentCodeFromFilename(string $filename, string $fallback): string
    {
        if (preg_match('/(?:FT|FAT|INV|CTR|CON|ORD)[\s_\-\/]*\d[\d_\-\/]*/i', $filename, $matches) === 1 && isset($matches[0])) {
            return strtoupper(str_replace(['_', ' '], '-', $matches[0]));
        }

        return $fallback;
    }

    /**
     * @return array<string, mixed>
     */
    private function parseJsonObject(string $text): array
    {
        $clean = trim($text);
        $replaced = preg_replace('/^```(?:json)?\s*|\s*```$/', '', $clean);
        $clean = is_string($replaced) ? $replaced : $clean;

        try {
            $decoded = json_decode($clean, true, 512, JSON_THROW_ON_ERROR);
            if (! is_array($decoded)) {
                return [];
            }

            $object = [];
            foreach ($decoded as $key => $value) {
                if (is_string($key)) {
                    $object[$key] = $value;
                }
            }

            return $object;
        } catch (\Throwable) {
            return [];
        }
    }

    private function excerpt(string $content): string
    {
        $normalized = preg_replace('/\s+/', ' ', $content);

        return $content !== ''
            ? Str::limit(is_string($normalized) ? $normalized : $content, 240)
            : 'Anteprima testo non disponibile: il PDF potrebbe essere scansionato o compresso. In demo viene mostrata comunque una proposta AI realistica.';
    }
}
