<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Reports;

use Modules\AiAssistant\Llm\LlmChatJsonClient;
use Modules\Quotation\AI\QuotationModificationExtractorInterface;

use function Safe\json_encode;

/**
 * LLM extractor for "copy an existing quotation and modify it": it is given the
 * source lines and the spoken request, and returns edit operations so the app can
 * change only what's needed while keeping the rest verbatim. Any failure yields an
 * empty array (no changes), which the caller treats as "keep the source as is".
 */
final class OpenAiQuotationModificationExtractor implements QuotationModificationExtractorInterface
{
    public function __construct(
        private readonly LlmChatJsonClient $client,
    ) {}

    public function extract(string $transcript, array $context): array
    {
        if (trim($transcript) === '') {
            return [];
        }

        return $this->client->complete(
            'Modifica il preventivo esistente secondo la richiesta e rispondi SOLO con JSON valido.',
            $this->prompt($transcript, $context),
        );
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function prompt(string $transcript, array $context): string
    {
        $items = json_encode($context['items'] ?? [], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $customer = json_encode($context['customer'] ?? null, JSON_UNESCAPED_UNICODE);

        return <<<PROMPT
Sei un assistente per idraulici. L'utente vuole partire da un preventivo ESISTENTE e modificarlo (di solito per un altro cliente e con alcuni articoli diversi). Parla in modo impreciso, come un idraulico.

Ti do le righe attuali del preventivo e la sua struttura: DEVI riconoscerla e cambiare SOLO cio che l'utente chiede, lasciando intatte le altre righe. Restituisci SOLO JSON valido con le operazioni, senza markdown.

Regole:
- "customer": i dati del nuovo cliente se l'utente lo cambia (legal_name, vat_number, city, ...), altrimenti null.
- "remove": elenco di parole chiave; ogni riga la cui descrizione le contiene va rimossa (es. "vecchia caldaia" -> ["caldaia"] solo se chiede di toglierla).
- "replace": righe da sostituire: {target: parola chiave della riga esistente da cambiare, description, quantity, unit_price}. Usa "target" per riconoscere la riga giusta (es. sostituire la caldaia -> target "caldaia").
- "add": nuove righe da aggiungere {description, quantity, unit_price}.
- NON riscrivere le righe che restano uguali: non metterle in nessuna lista.
- Metti unit_price solo se detto esplicitamente, altrimenti null.

Forma JSON:
{
  "customer": null,
  "remove": [],
  "replace": [{"target": "", "description": "", "quantity": 1, "unit_price": null}],
  "add": [{"description": "", "quantity": 1, "unit_price": null}]
}

Cliente attuale:
{$customer}

Righe attuali del preventivo:
{$items}

Richiesta a voce:
{$transcript}
PROMPT;
    }
}
