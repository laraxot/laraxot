<?php

declare(strict_types=1);

namespace Modules\PublicProcurement\Actions;

use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use Modules\PublicProcurement\Models\PublicProcurementAiExtraction;
use Spatie\QueueableAction\QueueableAction;

use function Safe\preg_match;
use function Safe\preg_split;

final class BuildTenderOpportunityDraftFromTextAction
{
    use QueueableAction;

    public function execute(string $filename, string $text, ?int $createdByUserId = null): PublicProcurementAiExtraction
    {
        $classification = app(ClassifyPublicProcurementDocumentTextAction::class)->execute($filename, $text);
        $payload = [
            'document' => $classification,
            'tender' => [
                'title' => $this->extractTitle($text, $filename),
                'contracting_authority' => $this->extractAuthority($text),
                'cig' => $this->match('/\bCIG[:\s]+([A-Z0-9]{8,12})\b/i', $text),
                'cup' => $this->match('/\bCUP[:\s]+([A-Z0-9]{10,20})\b/i', $text),
                'estimated_amount' => $this->extractAmount($text),
                'submission_deadline_at' => $this->extractDate($text),
                'category' => $this->extractCategory($text),
            ],
            'required_documents' => $this->requiredDocuments($text),
            'recommendations' => [
                'Verificare manualmente requisiti speciali, SOA e sopralluoghi obbligatori prima di partecipare.',
            ],
        ];

        /** @var PublicProcurementAiExtraction $draft */
        $draft = PublicProcurementAiExtraction::query()->create([
            'public_id' => (string) Str::uuid(),
            'created_by_user_id' => $createdByUserId,
            'document_type' => $classification['type'],
            'status' => 'draft',
            'confidence' => $classification['confidence'],
            'extracted_payload' => $payload,
            'raw_text' => mb_substr($text, 0, 20000),
            'expires_at' => now()->addDays(7),
        ]);

        return $draft;
    }

    private function extractTitle(string $text, string $filename): string
    {
        foreach (preg_split('/\R/', $text) as $line) {
            if (! is_string($line)) {
                continue;
            }

            $clean = trim($line);
            if (mb_strlen($clean) > 20) {
                return mb_substr($clean, 0, 180);
            }
        }

        return pathinfo($filename, PATHINFO_FILENAME) ?: 'Nuova opportunità gara';
    }

    private function extractAuthority(string $text): string
    {
        $authority = $this->match('/(?:ente|stazione appaltante|amministrazione)[:\s]+(.{3,120})/iu', $text);

        return $authority !== null ? trim($authority) : 'Ente da verificare';
    }

    private function extractAmount(string $text): ?float
    {
        $raw = $this->match('/(?:importo|base d.?asta|valore)[:\s€]+([0-9\.\,]+)/iu', $text);
        if ($raw === null) {
            return null;
        }

        $normalized = str_replace('.', '', $raw);
        $normalized = str_replace(',', '.', $normalized);

        return is_numeric($normalized) ? (float) $normalized : null;
    }

    private function extractDate(string $text): ?string
    {
        $raw = $this->match('/(?:scadenza|presentazione offerte|termine)[:\s]+([0-9]{1,2}[\/\-][0-9]{1,2}[\/\-][0-9]{4})/iu', $text);
        if ($raw === null) {
            return null;
        }

        try {
            $date = CarbonImmutable::createFromFormat(str_contains($raw, '/') ? 'd/m/Y' : 'd-m-Y', $raw);

            return $date instanceof CarbonImmutable ? $date->endOfDay()->toDateTimeString() : null;
        } catch (\Throwable) {
            return null;
        }
    }

    private function extractCategory(string $text): ?string
    {
        return $this->match('/\b(OS30|OG10|OG11)\b/iu', $text)
            ?? $this->match('/\b(impianti elettrici|illuminazione pubblica)\b/iu', $text);
    }

    /**
     * @return array<string, string>
     */
    private function requiredDocuments(string $text): array
    {
        $documents = [
            'durc' => 'DURC valido',
            'insurance' => 'Polizza assicurativa valida',
        ];

        if (str_contains(mb_strtolower($text), 'soa')) {
            $documents['soa'] = 'Attestazione SOA richiesta dal bando';
        }
        if (str_contains(mb_strtolower($text), 'cauzione provvisoria')) {
            $documents['provisional_guarantee'] = 'Cauzione provvisoria';
        }
        if (str_contains(mb_strtolower($text), 'sopralluogo')) {
            $documents['site_visit'] = 'Attestazione sopralluogo obbligatorio';
        }

        return $documents;
    }

    private function match(string $pattern, string $text): ?string
    {
        if (preg_match($pattern, $text, $matches) !== 1) {
            return null;
        }

        if (isset($matches[1])) {
            return trim($matches[1]);
        }

        return isset($matches[0]) ? trim($matches[0]) : null;
    }
}
