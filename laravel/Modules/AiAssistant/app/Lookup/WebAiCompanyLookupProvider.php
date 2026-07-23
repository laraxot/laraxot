<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Lookup;

use Illuminate\Support\Facades\Http;
use Modules\AiAssistant\Llm\LlmChatJsonClient;
use Modules\Customer\Lookup\CompanyLookupProviderInterface;

/**
 * Online company lookup: fetches public web results for a business name / VAT and
 * lets the LLM extract structured company candidates from the snippets. No paid
 * API; a search engine's HTML results page is used as the (best-effort) source.
 * Any failure — network blocked, no LLM key, unparseable output — yields [].
 */
final class WebAiCompanyLookupProvider implements CompanyLookupProviderInterface
{
    public function __construct(
        private readonly LlmChatJsonClient $client,
    ) {}

    /**
     * @return list<array<string, mixed>>
     */
    public function search(string $query): array
    {
        $query = trim($query);
        if ($query === '' || ! $this->client->isConfigured()) {
            return [];
        }

        // No usable web snippets (blocked/antibot/empty) -> nothing to extract; skip
        // the LLM call entirely and let the composite fall to structured sources.
        $webText = $this->fetchWebSnippets($query);
        if ($webText === '') {
            return [];
        }

        $decoded = $this->client->complete(
            'Estrai i dati aziendali e rispondi SOLO con JSON valido.',
            $this->prompt($query, $webText),
        );

        $candidates = $decoded['candidates'] ?? null;
        if (! is_array($candidates)) {
            return [];
        }

        $out = [];
        foreach ($candidates as $candidate) {
            if (is_array($candidate)) {
                /** @var array<string, mixed> $candidate */
                $out[] = $candidate;
            }
        }

        return $out;
    }

    private function fetchWebSnippets(string $query): string
    {
        // Prefer Brave (clean JSON, no captcha) when an API key is configured;
        // otherwise fall back to best-effort DuckDuckGo HTML scraping.
        $braveKey = config('company_lookup.brave_key');
        if (is_string($braveKey) && trim($braveKey) !== '') {
            return $this->fetchBraveSnippets($query, trim($braveKey));
        }

        return $this->fetchDuckDuckGoSnippets($query);
    }

    private function fetchBraveSnippets(string $query, string $apiKey): string
    {
        $timeout = config('company_lookup.timeout', 15);

        try {
            $response = Http::withHeaders(['X-Subscription-Token' => $apiKey, 'Accept' => 'application/json'])
                ->timeout(is_numeric($timeout) ? (int) $timeout : 15)
                ->get('https://api.search.brave.com/res/v1/web/search', [
                    'q' => $query.' partita iva sede legale',
                    'count' => 10,
                    'country' => 'IT',
                    'search_lang' => 'it',
                    'ui_lang' => 'it-IT',
                    'result_filter' => 'web',
                    'safesearch' => 'moderate',
                ]);
        } catch (\Throwable) {
            return '';
        }

        if (! $response->successful()) {
            return '';
        }

        $results = $response->json('web.results');
        if (! is_array($results)) {
            return '';
        }

        $lines = [];
        foreach ($results as $result) {
            if (! is_array($result)) {
                continue;
            }
            $title = is_string($result['title'] ?? null) ? trim(strip_tags($result['title'])) : '';
            $description = is_string($result['description'] ?? null) ? trim(strip_tags($result['description'])) : '';
            if ($title === '' && $description === '') {
                continue;
            }
            $lines[] = trim($title.' — '.$description);
        }

        return mb_substr(trim(implode("\n", $lines)), 0, 6000);
    }

    private function fetchDuckDuckGoSnippets(string $query): string
    {
        $timeout = config('company_lookup.timeout', 15);

        try {
            $response = Http::withHeaders(['User-Agent' => 'Mozilla/5.0 (compatible; iTER7/1.0)'])
                ->timeout(is_numeric($timeout) ? (int) $timeout : 15)
                ->asForm()
                ->post('https://html.duckduckgo.com/html/', [
                    'q' => $query.' partita iva sede legale',
                ]);
        } catch (\Throwable) {
            return '';
        }

        if (! $response->successful()) {
            return '';
        }

        // Strip HTML to plain text and cap the size fed to the LLM.
        $text = trim(preg_replace('/\s+/', ' ', strip_tags($response->body())) ?? '');

        // Antibot/captcha challenge instead of real results: don't waste an LLM call
        // on it — return empty so the lookup degrades to the structured sources.
        if ($this->looksLikeAntibotChallenge($text)) {
            return '';
        }

        return mb_substr($text, 0, 6000);
    }

    private function looksLikeAntibotChallenge(string $text): bool
    {
        $needles = [
            'bots use duckduckgo',
            'complete the following challenge',
            'confirm this search was made by a human',
            'containing a duck',
            'unusual traffic',
            'captcha',
        ];
        $haystack = mb_strtolower($text);

        foreach ($needles as $needle) {
            if (str_contains($haystack, $needle)) {
                return true;
            }
        }

        return false;
    }

    private function prompt(string $query, string $webText): string
    {
        $web = $webText === '' ? '(nessun risultato web disponibile)' : $webText;

        return <<<PROMPT
Sei un assistente che identifica aziende italiane a partire da una ricerca (ragione sociale o P.IVA).
Ti do la query e degli estratti da pagine web. Restituisci SOLO JSON valido, senza markdown.

Regole:
- Usa SOLO informazioni presenti negli estratti web. NON inventare P.IVA, indirizzi o contatti.
- Se un dato non e presente, mettilo a null.
- La query puo contenere errori di battitura o essere incompleta: interpreta l'INTENZIONE e trova l'azienda reale piu probabile (es. "tonana spa" -> "Tognana", "tognana tetti" -> l'azienda Tognana che tratta tetti/coperture se presente).
- "legal_name" DEVE essere la ragione sociale CANONICA e corretta trovata negli estratti (con la forma societaria, es. "S.p.A."/"S.r.l."), non la query cosi come scritta.
- La query puo includere una P.IVA: usala per identificare/confermare l'azienda giusta.
- Ordina i candidati dal piu pertinente al meno; restituiscine al massimo 5.

Forma JSON:
{
  "candidates": [
    {"legal_name": "", "vat_number": null, "tax_code": null, "address_line1": null, "city": null, "postal_code": null, "province": null, "phone": null, "email": null}
  ]
}

Query: {$query}

Estratti web:
{$web}
PROMPT;
    }
}
