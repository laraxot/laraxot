<?php

declare(strict_types=1);

namespace Modules\AiAssistant\Import;

use Modules\AiAssistant\Llm\LlmChatJsonClient;
use Modules\Quotation\Import\QuotationDocumentRewriterInterface;

use function Safe\json_encode;

/**
 * AI-backed document rewriter. The model SEES the document part (large input is
 * fine) but returns only a small list of exact string REPLACEMENTS
 * {"replacements":[{"find","replace"}]} — never the whole rewritten document,
 * which would exceed the model's output-token limit and get truncated. We apply
 * the replacements deterministically; the caller validates the result.
 */
final class AiQuotationDocumentRewriter implements QuotationDocumentRewriterInterface
{
    public function __construct(
        private readonly LlmChatJsonClient $client,
    ) {}

    public function rewrite(string $content, string $partName, array $quotation): ?string
    {
        if (trim($content) === '' || ! $this->client->isConfigured()) {
            return null;
        }

        $system = 'Restituisci SOLO JSON valido nel formato {"replacements":[{"find":"...","replace":"..."}]}.';
        $prompt = $this->prompt($content, $quotation);

        // Retry once with the stronger "pro" model when the first attempt yields no
        // usable replacements. No deterministic fallback: only the AI decides edits.
        foreach ([null, $this->proModel()] as $model) {
            $decoded = $this->client->complete($system, $prompt, $model);
            $applied = $this->applyReplacements($content, $decoded['replacements'] ?? null);
            if ($applied !== null) {
                return $applied;
            }
        }

        return null;
    }

    /**
     * Applies the AI's exact-substring replacements to the content. Returns null
     * when there are none or nothing actually changed.
     */
    private function applyReplacements(string $content, mixed $replacements): ?string
    {
        if (! is_array($replacements)) {
            return null;
        }

        $search = [];
        $replace = [];
        foreach ($replacements as $entry) {
            if (! is_array($entry)) {
                continue;
            }
            $find = $entry['find'] ?? null;
            $with = $entry['replace'] ?? null;
            if (is_string($find) && $find !== '' && is_string($with)) {
                $search[] = $find;
                $replace[] = $with;
            }
        }

        if ($search === []) {
            return null;
        }

        $result = str_replace($search, $replace, $content);

        return $result !== $content ? $result : null;
    }

    private function proModel(): ?string
    {
        $model = config('services.llm.pro_model');

        return is_string($model) && trim($model) !== '' ? trim($model) : null;
    }

    /**
     * @param  array<string, mixed>  $quotation
     */
    private function prompt(string $content, array $quotation): string
    {
        $data = json_encode($quotation, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

        return <<<PROMPT
Sei un motore che adatta un documento di preventivo (foglio ODS/DOCX o testo) a un
NUOVO preventivo, mantenendo IDENTICO il layout. NON riscrivere il documento: restituisci
SOLO le sostituzioni di stringhe da applicare.

Formato risposta (SOLO questo, niente altro):
{"replacements": [{"find": "<sottostringa ESATTA presente nel contenuto>", "replace": "<nuovo testo>"}]}

Regole TASSATIVE:
- Ogni "find" DEVE essere una sottostringa ESATTA e univoca presente nel contenuto qui sotto
  (copiala pari pari, inclusi tag/attributi/spazi). Se non e presente, non includerla.
- Cliente: sostituisci il nome del vecchio cliente ("old_customer_name") con quello nuovo
  ("customer_name") ovunque compaia (anche se spezzato su piu celle: una find per pezzo).
- RIGHE ARTICOLO — LA PARTE PIU IMPORTANTE:
  Il NUOVO preventivo ha ESATTAMENTE le righe in "lines" (in ordine). Il documento contiene le
  righe del VECCHIO preventivo. Rendi le righe del documento UGUALI a "lines":
  1. RIGHE TENUTE (le prime N, con N = numero di righe in "lines"): per la riga i sostituisci
     SOLO la DESCRIZIONE (con lines[i].description) e i PREZZI (unitario e totale riga: testo
     "€ ..." E office:value, con lines[i].unit_price e lines[i].line_total).
     LA QUANTITA DELLA RIGA TENUTA NON VA MAI SVUOTATA. Deve valere lines[i].quantity (es. 1):
     se nel documento e' gia' quel numero LASCIALA COM'E' (non produrre find/replace per lei);
     se e' diversa, cambiala col nuovo valore (testo E office:value). Una quantita vuota fa
     diventare 0 il totale riga (formula quantita×prezzo) e quindi 0 tutti i totali: NON farlo.
     ATTENZIONE alle celle unite: spesso la DESCRIZIONE occupa piu righe (table:number-rows-spanned)
     e la QUANTITA/PREZZI stanno nella RIGA SOTTO. Quella riga sotto con la quantita fa parte
     della riga articolo TENUTA: NON svuotarla, e' la quantita da mantenere/impostare a 1.
  2. RIGHE IN ECCESSO (quelle OLTRE le prime N, cioe' gli articoli del VECCHIO preventivo che
     NON sono in "lines"): svuotale COMPLETAMENTE — descrizione, QUANTITA, prezzo unitario e
     totale riga diventano TUTTE celle vuote. NON deve restare NIENTE (nemmeno una quantita "1"
     o "0" orfana). NON mettere "0". Per ogni cella di quelle righe sostituisci l'INTERA cella
     con una cella vuota che mantiene solo lo stile. Esempi (celle di righe IN ECCESSO):
       "<table:table-cell table:style-name=\"ce50\" office:value-type=\"float\" office:value=\"1\"><text:p>1</text:p></table:table-cell>" -> "<table:table-cell table:style-name=\"ce50\"/>"
       "<table:table-cell table:style-name=\"ce61\" office:value-type=\"currency\" office:currency=\"EUR\" office:value=\"190\"><text:p>€ 190,00</text:p></table:table-cell>" -> "<table:table-cell table:style-name=\"ce61\"/>"
       "<text:p>-Fornitura e posa di canna fumaria...</text:p>" -> "<text:p></text:p>"
     ATTENZIONE: svuota la quantita SOLO nelle righe in eccesso; nella riga TENUTA la quantita
     deve restare (es. 1).
  3. Esempio COMPLETO (lines = 1 sola riga, "Scaldabagno elettrico 80 lt", qty 1, 210):
     - la 1a riga articolo: descrizione -> "Scaldabagno elettrico 80 lt", prezzo unit. e totale
       -> € 210,00 (testo E office:value=210). La sua QUANTITA resta 1 (se e' gia' 1, non toccarla).
     - TUTTE le altre righe articolo: descrizione e prezzi svuotati (celle vuote). La quantita
       della 1a riga NON si tocca.
- NUMERI nei fogli ODS/XLSX: per ogni valore cambiato aggiungi DUE find/replace: uno per il
  testo visualizzato (es. "€ 1.158,00") e UNO per l'attributo di valore (es.
  office:value="1158" -> office:value="210"). Altrimenti il programma mostra il numero vecchio.
- DATE/VALIDITA: ricalcola dalla data odierna ("today"): data preventivo = today; scadenza =
  today + i giorni indicati. Sostituisci sia il testo (es. "10/07/2026") sia eventuali
  office:date-value.
- PAGAMENTI/RATE: ricalcola gli importi sul nuovo "total" (a tasso zero: rata = total/N).
- TOTALI (imponibile, IVA, totale) — ATTENZIONE:
  - Se le celle dei totali sono FORMULE (contengono table:formula, es. table:formula="of:=SUM(...)"),
    NON toccarle: si ricalcolano DA SOLE dai valori delle righe. Devi solo assicurarti che le righe
    tenute abbiano quantita e prezzi giusti (con office:value) e che le righe in eccesso siano
    davvero VUOTE (una cella vuota vale 0 nella somma). NON mettere "0" fisso nei totali.
  - Se invece un totale NON e una formula (valore scritto a mano), sostituiscilo col valore dei
    dati: "subtotal" (imponibile), "tax_amount" (IVA), "total" (totale).
- NON inventare dati non presenti in "quotation".

Dati nuovo preventivo (quotation):
{$data}

Contenuto attuale (adatta SOLO i valori, restituendo le find/replace):
{$content}
PROMPT;
    }
}
