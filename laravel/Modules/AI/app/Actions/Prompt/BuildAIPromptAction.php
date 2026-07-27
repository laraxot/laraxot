<?php

declare(strict_types=1);

namespace Modules\AI\Actions\Prompt;

use InvalidArgumentException;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

use function Safe\json_encode;

final class BuildAIPromptAction
{
    use QueueableAction;

    /**
     * @param  array<string, mixed>  $params
     */
    public function execute(string $type, array $params = []): string
    {
        return match ($type) {
            'classification' => $this->classification(
                $this->paramString($params, 'title'),
                $this->paramString($params, 'description'),
            ),
            'solutions' => $this->solutions(
                $this->paramString($params, 'title'),
                $this->paramString($params, 'description'),
                $this->paramString($params, 'category'),
            ),
            'sentiment' => $this->sentiment($this->paramString($params, 'text')),
            'priority' => $this->priority(
                $this->paramString($params, 'title'),
                $this->paramString($params, 'description'),
                $this->stringKeyMap(is_array($params['context'] ?? null) ? $params['context'] : []),
            ),
            'routing' => $this->routing(
                $this->ticketList(is_array($params['tickets'] ?? null) ? $params['tickets'] : []),
                $this->ticketList(is_array($params['agents'] ?? null) ? $params['agents'] : []),
            ),
            'auto_response' => $this->autoResponse(
                $this->paramString($params, 'ticket_content'),
                $this->paramString($params, 'category'),
                $this->paramString($params, 'priority'),
            ),
            'pattern_analysis' => $this->patternAnalysis(
                $this->ticketList(is_array($params['tickets'] ?? null) ? $params['tickets'] : []),
            ),
            'improvements' => $this->improvements(
                $this->stringKeyMap(is_array($params['data'] ?? null) ? $params['data'] : []),
            ),
            default => throw new InvalidArgumentException('Unsupported AI prompt type: '.$type),
        };
    }

    private function classification(string $title, string $description): string
    {
        return "Classifica il seguente ticket per il servizio di gestione ticket cittadini:

Titolo: {$title}
Descrizione: {$description}

Categorie disponibili:
- infrastruttura (strade, ponti, illuminazione, segnaletica)
- ambiente (rifiuti, inquinamento, verde pubblico)
- trasporti (trasporto pubblico, parcheggi, ciclabili)
- sicurezza (sicurezza urbana, emergenze)
- servizi (uffici pubblici, documenti, pratiche)
- altro

Rispondi in formato JSON con:
{
  \"category\": \"categoria_principale\",
  \"subcategory\": \"sottocategoria\",
  \"confidence\": 0.95,
  \"tags\": [\"tag1\", \"tag2\"],
  \"urgency_indicators\": [\"indicatore1\", \"indicatore2\"]
}";
    }

    private function solutions(string $title, string $description, string $category): string
    {
        return "Suggerisci soluzioni per questo ticket di {$category}:

Titolo: {$title}
Descrizione: {$description}

Fornisci 3-5 soluzioni pratiche e concrete, specifiche per il contesto italiano e le amministrazioni pubbliche.

Rispondi in formato JSON:
{
  \"solutions\": [
    {
      \"title\": \"Titolo soluzione\",
      \"description\": \"Descrizione dettagliata\",
      \"steps\": [\"passo1\", \"passo2\"],
      \"estimated_time\": \"2-3 giorni\",
      \"required_resources\": [\"risorsa1\", \"risorsa2\"],
      \"priority\": \"high|medium|low\"
    }
  ],
  \"preventive_measures\": [\"misura1\", \"misura2\"],
  \"follow_up_actions\": [\"azione1\", \"azione2\"]
}";
    }

    private function sentiment(string $text): string
    {
        return "Analizza il sentiment del seguente testo di un cittadino:

{$text}

Rispondi in formato JSON:
{
  \"sentiment\": \"positive|negative|neutral\",
  \"emotion\": \"soddisfazione|frustrazione|preoccupazione|rabbia|speranza\",
  \"confidence\": 0.85,
  \"key_phrases\": [\"frase1\", \"frase2\"],
  \"urgency_level\": \"low|medium|high|critical\",
  \"recommended_response_tone\": \"professionale|empatico|rassicurante|decisivo\"
}";
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function priority(string $title, string $description, array $context): string
    {
        $contextStr = json_encode($context, JSON_PRETTY_PRINT);

        return "Predici la priorità di questo ticket:

Titolo: {$title}
Descrizione: {$description}
Contesto: {$contextStr}

Considera:
- Impatto sulla sicurezza pubblica
- Numero di cittadini coinvolti
- Urgenza temporale
- Complessità di risoluzione
- Risorse disponibili

Rispondi in formato JSON:
{
  \"priority\": \"low|medium|high|urgent|critical\",
  \"confidence\": 0.90,
  \"reasoning\": \"motivazione dettagliata\",
  \"estimated_resolution_time\": \"1-2 giorni\",
  \"required_escalation\": true|false,
  \"risk_factors\": [\"fattore1\", \"fattore2\"]
}";
    }

    /**
     * @param  array<int, array<string, mixed>>  $tickets
     * @param  array<int, array<string, mixed>>  $agents
     */
    private function routing(array $tickets, array $agents): string
    {
        $ticketsStr = json_encode($tickets, JSON_PRETTY_PRINT);
        $agentsStr = json_encode($agents, JSON_PRETTY_PRINT);

        return "Ottimizza l'assegnazione di questi ticket agli agenti disponibili:

Ticket: {$ticketsStr}
Agenti: {$agentsStr}

Considera:
- Competenze degli agenti
- Carico di lavoro attuale
- Specializzazione per categoria
- Disponibilità temporale
- Precedenti performance

Rispondi in formato JSON:
".AIPromptTemplates::ROUTING_JSON;
    }

    private function autoResponse(string $ticketContent, string $category, string $priority): string
    {
        return "Genera una risposta automatica professionale per questo ticket:

Contenuto: {$ticketContent}
Categoria: {$category}
Priorità: {$priority}

La risposta deve essere:
- Professionale ma amichevole
- Rassicurante per il cittadino
- Specifica per la categoria
- Adatta alla priorità
- In italiano corretto
- Lunga 2-3 paragrafi

Rispondi solo con il testo della risposta, senza formattazione aggiuntiva.";
    }

    /**
     * @param  array<int, array<string, mixed>>  $tickets
     */
    private function patternAnalysis(array $tickets): string
    {
        $ticketsStr = json_encode($tickets, JSON_PRETTY_PRINT);

        return "Analizza i pattern in questi ticket per identificare:

Ticket: {$ticketsStr}

Identifica:
- Trend temporali
- Aree geografiche problematiche
- Categorie più frequenti
- Pattern stagionali
- Correlazioni tra fattori
- Opportunità di miglioramento

Rispondi in formato JSON:
".AIPromptTemplates::PATTERN_JSON;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function improvements(array $data): string
    {
        return 'Suggerisci miglioramenti per il servizio di gestione ticket basandoti su questi dati:

Dati: '.json_encode($data, JSON_PRETTY_PRINT).AIPromptTemplates::IMPROVEMENTS_JSON;
    }

    /**
     * @param  array<string, mixed>  $params
     */
    private function paramString(array $params, string $key): string
    {
        $value = $params[$key] ?? '';
        Assert::string($value);

        return $value;
    }

    /**
     * @param  array<mixed, mixed>  $input
     * @return array<string, mixed>
     */
    private function stringKeyMap(array $input): array
    {
        $output = [];
        foreach ($input as $key => $value) {
            if (is_string($key)) {
                $output[$key] = $value;
            }
        }

        return $output;
    }

    /**
     * @param  array<mixed, mixed>  $input
     * @return array<int, array<string, mixed>>
     */
    private function ticketList(array $input): array
    {
        $output = [];
        foreach ($input as $row) {
            if (! is_array($row)) {
                continue;
            }

            $output[] = $this->stringKeyMap($row);
        }

        return $output;
    }
}
