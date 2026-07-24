<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Import;

use Modules\AiAssistant\Llm\LlmChatJsonClient;
use Modules\Quotation\Import\QuotationNotesRewriterInterface;

use function Safe\json_encode;

/**
 * AI-backed notes rewriter: recomputes validity dates (from today) and payment
 * plans (from the new total) inside the commercial notes, returning JSON
 * {"notes": "..."}. Null when nothing usable comes back (caller keeps the notes).
 */
final class AiQuotationNotesRewriter implements QuotationNotesRewriterInterface
{
    public function __construct(
        private readonly LlmChatJsonClient $client,
    ) {}

    public function rewrite(string $notes, array $quotation): ?string
    {
        if (trim($notes) === '' || ! $this->client->isConfigured()) {
            return null;
        }

        $system = 'Riscrivi le note del preventivo e rispondi SOLO con JSON valido {"notes": "..."}.';
        $prompt = $this->prompt($notes, $quotation);

        foreach ([null, $this->proModel()] as $model) {
            $decoded = $this->client->complete($system, $prompt, $model);
            $result = $decoded['notes'] ?? null;
            if (is_string($result) && trim($result) !== '') {
                return $result;
            }
        }

        return null;
    }

    private function proModel(): ?string
    {
        $model = config('services.llm.pro_model');

        return is_string($model) && trim($model) !== '' ? trim($model) : null;
    }

    /**
     * @param  array<string, mixed>  $quotation
     */
    private function prompt(string $notes, array $quotation): string
    {
        $data = json_encode($quotation, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

        return <<<PROMPT
Adatta le NOTE commerciali di un preventivo al NUOVO preventivo, mantenendo lo stile
e la lingua. Ti do le note attuali e i dati del nuovo preventivo.

Regole TASSATIVE:
- Rispondi SOLO JSON valido: {"notes": "<note adattate>"}.
- VALIDITA/DATE: se le note indicano una validita (es. "validita 15 giorni") o una data,
  ricalcola a partire da OGGI (campo "today"): scadenza = today + i giorni indicati.
  NON lasciare mai date vecchie.
- PAGAMENTI/RATE: se ci sono piani di pagamento (es. "10 rate a tasso zero da X, totale Y"),
  RICALCOLA gli importi sul NUOVO totale ("total"): a tasso zero rata = total/numero_rate,
  e aggiorna anche i totali del piano citati.
- Aggiorna ogni altro importo/percentuale DERIVATO coerentemente col nuovo totale.
- NON inventare condizioni non presenti nelle note originali.

Dati nuovo preventivo:
{$data}

Note attuali da adattare:
{$notes}
PROMPT;
    }
}
