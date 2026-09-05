# Architettura Modulo AiAssistant

Orchestrazione AI per dominio specifico: quotazioni, interventi, customer lookup, transcription vocale. **Non è modulo AI separato** — è consumatore dell'astrazione handler di AI.

## Scopo

Orchestrare capacità AI per business domains (Quotation, Intervention, Customer). Applica logica dominio-specifica (sicurezza, estrazione dati, generazione report).

## Componenti Principali

**Proposals:** Registra suggerimenti AI prima dell'approvazione
- `CreateAiActionProposalAction` → crea proposal con workflow approvazione
- `ExecuteAiActionProposalAction` → esegui dopo approvazione umana
- `CancelAiActionProposalAction` → rigetta e rollback

**Domain Extractors:** Parsa output LLM in risultati tipizzati
- `OpenAiQuotationDraftExtractor` → `QuotationDraftExtractorInterface`
- `OpenAiInterventionReportExtractor` → `InterventionReportExtractorContract`
- `OpenAiQuotationModificationExtractor` → `QuotationModificationExtractorInterface`

**Security Layers:**
- `PromptInjectionGuard` — rileva e blocca injection
- `AiOutputGuard` — valida output LLM per safety
- `SensitiveDataRedactor` — rimuove PII prima di inviare a LLM
- `AiActionPolicy` — autorizza chi può creare proposal

**Chat & Threads:** Conversazioni stateful
- `AiThread` — contenitore conversazione
- `AiMessage` — messaggio singolo
- `AiAssistantChatHistory` — recupera contesto per follow-up

## Dipendenze (Problema)

```
AiAssistant (questo modulo)
├── AI (LLM bridge) ← DOVREBBE usare ma non lo fa
├── openai-php/laravel (import diretto) ← PROBLEMA
├── Groq API (import diretto) ← PROBLEMA
├── Quotation, Intervention, Customer
└── User, Xot
```

**Problema critico:** Import diretto OpenAI/Groq. Dovrebbe delegare a AI module handler system.

## Best Practices

- **Approval workflow:** Umani approvano suggerimenti AI; audit trail intatto
- **Security first:** Guard input+output; previeni injection e data leak
- **Thread context:** Mantieni contesto per interazioni multi-turn
- **Delegation:** Delega LLM mechanics a AI module handler
- **Type-safe extraction:** Risultati tipizzati; preserva modelli dominio

## ⚠️ Bad Practices

- **Import diretto LLM provider** — causa tight coupling, impossibile swappare
- **Guard reattivi, non preventivi** — `PromptInjectionGuard` rileva dopo creazione. Dovrebbe pre-filtrare input
- **Hardcoded estrazione logica** — strategy diverse per ogni dominio. Dovrebbe essere strategy pattern
- **No context compression** — thread cresce infinito; dovrebbe comprare context vecchio
- **Estrattori bindati a provider** — `OpenAiQuotationDraftExtractor` importa Quotation diretto

## 🚨 False Friends

- **"Proposal = audit trail"** — NO: solo se approver/executor registrato. Se ignored, proposal è orphaned
- **"Guard su output = safe"** — NO: guard è heuristica. Output pericoloso può passare; user deve verificare
- **"Chat thread = memory** — NO: token budget finito; vecchi messaggi persi se non compressi. Documenta retention policy
- **"Delegazione a AI module = magico"** — NO: handler deve ancora essere implementato. Estrazione non automatica
- **"Più tentativi = miglior risultato"** — NO: retry su stessa prompt dà stesso risultato. Modificare prompt, non retry

## Problemi Noti & Debito Tecnico

1. **Coupling diretto a LLM Provider:** Import OpenAI/Groq bypass AI module. Dovrebbe delegare tutto a handler

2. **Duplication con AI module:** Implementazione parallela orchestrazione LLM, retry logic, response parsing

3. **Thread management incompleto:** `AiThread` esiste ma senza memory optimization. Dovrebbe comprare contesto vecchio come `ContextCompressorAction` di AI

4. **Guard preventivi vs reattivi:** Security guard rileva dopo creazione. Dovrebbe pre-filtrare user input prima proposal

5. **Hardcoded extraction logic:** `OpenAiQuotationDraftExtractor` importa Quotation diretto. Dovrebbe essere decoupled via `QuotationDraftExtractorInterface`

## Consolidamento: Non Merge, Delegation

**NON unire AI + AiAssistant.**

**Invece: Estrai handler a AI, AiAssistant delega**

**Fase 1 (AI):** Crea handler dominio
- `AI/Domain/QuotationDraftHandler`
- `AI/Domain/InterventionReportHandler`
- `AI/Domain/SpeechTranscriptionHandler`

**Fase 2 (AiAssistant):** Delega a handler
```php
// PRIMA
class OpenAiQuotationDraftExtractor {
    public function extract($document) {
        return OpenAI::client()->chat()->create([...]);
    }
}

// DOPO
class OpenAiQuotationDraftExtractor {
    public function __construct(AiActionHandlerRegistry $handlers) { }
    
    public function extract($document) {
        return $this->handlers->get('quotation-draft')->handle(['document' => $document]);
    }
}
```

**Impatto:** AiAssistant -600 LOC, zero duplication, AI diventa single source of truth

## Decisioni Architetturali

| Decisione | Razionale |
|-----------|-----------|
| Proposal prima esecuzione | Umani approvano; audit trail preservato |
| Security guard input+output | Previeni injection e unintended output leak |
| Thread-based conversations | Mantieni contesto per multi-turn |
| Delegation a AI module | DRY principle; una sola place per update LLM logic |
| Domain-specific extractor | Risultati type-safe; modelli dominio preservati |

## Vedi Anche

- `PHILOSOPHY.md` — principi e vincoli
- `TESTING.md` — test proposal, extractor, guard
- `docs/stories/01.refactor-to-use-ai-handlers.story.md` — story BMAD Phase 2
- `../AI/docs/consolidation-with-aiassistant.md` — roadmap consolidamento
- `../AI/ARCHITECTURE.md` — design layer handler AI
