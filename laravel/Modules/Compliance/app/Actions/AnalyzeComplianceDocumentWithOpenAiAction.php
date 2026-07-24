<?php

declare(strict_types=1);

namespace Modules\Compliance\Actions;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Modules\Compliance\Models\ComplianceDocument;
use Modules\Document\Models\Media;
use Spatie\QueueableAction\QueueableAction;

use function Safe\json_decode;
use function Safe\preg_replace;

final class AnalyzeComplianceDocumentWithOpenAiAction
{
    use QueueableAction;

    /**
     * @return array<string, mixed>
     */
    public function execute(ComplianceDocument $document): array
    {
        if (! $document->media instanceof Media) {
            throw new \RuntimeException('Documento senza file allegato.');
        }

        $apiKeyValue = config('services.openai.key');
        $apiKey = is_string($apiKeyValue) ? $apiKeyValue : '';
        if (trim($apiKey) === '' || $apiKey === 'invalid key') {
            throw new \RuntimeException('OPENAI_API_KEY mancante in configurazione.');
        }

        $media = $document->media;
        $disk = (string) ($media->disk ?: 'public');
        $path = (string) $media->path;
        if (! Storage::disk($disk)->exists($path)) {
            throw new \RuntimeException('File documento non trovato nello storage.');
        }

        $mimeType = (string) ($media->mime_type ?: Storage::disk($disk)->mimeType($path) ?: 'application/octet-stream');
        $fileData = base64_encode((string) Storage::disk($disk)->get($path));
        $filename = basename($path);

        $baseUrlValue = config('compliance.ocr.openai.base_url', 'https://api.openai.com/v1/');
        $modelValue = config('compliance.ocr.openai.model', 'gpt-4o-mini');
        $timeoutValue = config('compliance.ocr.openai.timeout', 90);
        $baseUrl = is_string($baseUrlValue) ? rtrim($baseUrlValue, '/').'/' : 'https://api.openai.com/v1/';
        $model = is_string($modelValue) ? $modelValue : 'gpt-4o-mini';
        $timeout = is_int($timeoutValue) ? $timeoutValue : 90;

        $client = new Client([
            'base_uri' => $baseUrl,
            'headers' => [
                'Authorization' => 'Bearer '.$apiKey,
                'Content-Type' => 'application/json',
            ],
            'http_errors' => false,
            'timeout' => $timeout,
        ]);

        $response = $client->post('responses', [
            'json' => [
                'model' => $model,
                'temperature' => 0,
                'input' => [[
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'input_text',
                            'text' => $this->prompt($filename, $mimeType),
                        ],
                        $this->filePart($filename, $mimeType, $fileData),
                    ],
                ]],
            ],
        ]);

        $status = $response->getStatusCode();
        $body = (string) $response->getBody();
        if ($status < 200 || $status >= 300) {
            throw new \RuntimeException('Errore OCR OpenAI: '.mb_substr($body, 0, 500));
        }

        $decoded = json_decode($body, true);
        $text = $this->extractOutputText(is_array($decoded) ? $decoded : []);
        $parsed = $this->parseJsonObject($text);

        $classification = app(ClassifyComplianceDocumentTextAction::class)->execute($filename, $text);

        $document->forceFill([
            'type' => is_string($parsed['type'] ?? null) && $parsed['type'] !== 'unknown' ? $parsed['type'] : $document->type,
            'title' => is_string($parsed['title'] ?? null) && trim($parsed['title']) !== '' ? $parsed['title'] : $document->title,
            'extracted_text' => is_string($parsed['extracted_text'] ?? null) ? $parsed['extracted_text'] : $text,
            'ai_classification' => $classification,
            'ai_extracted_fields' => is_array($parsed['fields'] ?? null) ? $parsed['fields'] : [],
            'ai_processed_at' => now(),
            'expires_on' => is_string($parsed['expires_on'] ?? null) && $parsed['expires_on'] !== '' ? $parsed['expires_on'] : $document->expires_on,
            'issued_on' => is_string($parsed['issued_on'] ?? null) && $parsed['issued_on'] !== '' ? $parsed['issued_on'] : $document->issued_on,
        ])->save();

        return [
            'ok' => true,
            'document_id' => $document->id,
            'type' => $document->type,
            'classification' => $classification,
            'fields' => $document->ai_extracted_fields ?? [],
        ];
    }

    private function prompt(string $filename, string $mimeType): string
    {
        return <<<PROMPT
Analizza questo documento per un gestionale italiano di commesse/cantieri.
Restituisci SOLO JSON valido con questa forma:
{
  "type": "durc|pos|psc|dvr|dico|building_permit|training|medical_fitness|insurance|equipment_manual|equipment_check|other|unknown",
  "title": "titolo breve",
  "issued_on": "YYYY-MM-DD oppure stringa vuota",
  "expires_on": "YYYY-MM-DD oppure stringa vuota",
  "extracted_text": "testo leggibile piu importante",
  "fields": {
    "azienda": "",
    "codice_fiscale_o_piva": "",
    "ente_o_emittente": "",
    "numero_documento": "",
    "note": ""
  }
}
Non inventare date o numeri. Se un dato non e visibile lascia stringa vuota.
File: {$filename}
MIME: {$mimeType}
PROMPT;
    }

    /**
     * @return array<string, string>
     */
    private function filePart(string $filename, string $mimeType, string $fileData): array
    {
        if (str_starts_with($mimeType, 'image/')) {
            return [
                'type' => 'input_image',
                'image_url' => "data:{$mimeType};base64,{$fileData}",
            ];
        }

        return [
            'type' => 'input_file',
            'filename' => $filename,
            'file_data' => "data:{$mimeType};base64,{$fileData}",
        ];
    }

    /**
     * @param  array<array-key, mixed>  $decoded
     */
    private function extractOutputText(array $decoded): string
    {
        $outputText = $decoded['output_text'] ?? null;
        if (is_string($outputText) && trim($outputText) !== '') {
            return trim($outputText);
        }

        $output = $decoded['output'] ?? [];
        $chunks = [];
        foreach (is_array($output) ? $output : [] as $item) {
            if (! is_array($item)) {
                continue;
            }
            $contents = $item['content'] ?? [];
            foreach (is_array($contents) ? $contents : [] as $content) {
                if (is_array($content) && isset($content['text']) && is_string($content['text'])) {
                    $chunks[] = $content['text'];
                }
            }
        }

        return trim(implode("\n", $chunks));
    }

    /**
     * @return array<string, mixed>
     */
    private function parseJsonObject(string $text): array
    {
        $clean = trim($text);
        $clean = preg_replace('/^```(?:json)?\s*|\s*```$/', '', $clean) ?? $clean;

        try {
            $decoded = json_decode($clean, true);

            if (! is_array($decoded)) {
                return [];
            }

            /** @var array<string, mixed> $stringKeyed */
            $stringKeyed = [];
            foreach ($decoded as $key => $value) {
                if (is_string($key)) {
                    $stringKeyed[$key] = $value;
                }
            }

            return $stringKeyed;
        } catch (\Throwable) {
            return [];
        }
    }
}
