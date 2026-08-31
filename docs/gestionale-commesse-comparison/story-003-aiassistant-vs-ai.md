---
title: "STORY-003 — AiAssistant (gestionale_commesse) vs AI (base_workorder_fila5)"
type: story
created: 2026-07-23
tags: [ai, aiassistant, gestionale-commesse, module-mapping, comparison]
related:
  - "./module-mapping.md"
  - "./EPIC.md"
  - "../../laravel/Modules/AI/docs/gestionale-aiassistant-mapping.md"
sources:
  - "/var/www/_bases/gestionale_commesse/Modules/AiAssistant"
  - "/var/www/_bases/base_workorder_fila5/laravel/Modules/AI"
---

# STORY-003 — AiAssistant vs AI

## Verdetto

**Stesso nome di dominio, concetti nettamente diversi allo stato attuale — overlap solo parziale/nominale, non funzionale.**

`AiAssistant` (gestionale_commesse) è un **assistente conversazionale operativo** (chat + voce) con persistenza di thread/messaggi, un motore di "action proposal" che permette all'AI di *proporre* azioni sul dominio (time punch, note, cambio stato work order, finalizzazione report) soggette a conferma umana, tool logging auditabile, integrazione stretta con i moduli di dominio (`Interventions`, `Quotations`, `Customers`, `WorkOrders`, `Inventory`, `Bom`, `Production`, `EnergyBroker`), STT/TTS, e un agente basato su `NeuronAI` (libreria agentica PHP) con provider Groq/OpenAI.

`AI` (base_workorder_fila5) è oggi un **modulo scaffold generico di utilities AI stateless**: completion di testo, analisi del sentiment (basic + placeholder "transformers"), generazione di bozze di "predictions" per un dominio di *prediction market* (del tutto estraneo al dominio work order/interventi), più una pagina Filament di "fine-tuning" che chiama un endpoint HTTP esterno. Non ha alcun modello Eloquent, nessuna migrazione, nessuna persistenza di conversazioni, nessun meccanismo di action-proposal/conferma, nessun collegamento ai moduli di dominio (WorkOrders, Interventions, ecc.).

Questo verdetto è già confermato da una nota esistente nel repo target, `laravel/Modules/AI/docs/gestionale-aiassistant-mapping.md`, che descrive `AI` come "oggi più prediction/sentiment" rispetto a `AiAssistant` come "assistant conversazionale con proposal eseguibili" — questa STORY ne è l'approfondimento strutturale/funzionale con evidenze puntuali.

## Analisi comparata

### AiAssistant — struttura (SRC)

Percorso: `/var/www/_bases/gestionale_commesse/Modules/AiAssistant`

| Area | Contenuto |
|---|---|
| Modelli (Infrastructure/Models) | `AiThread`, `AiMessage`, `AiActionProposal`, `AiToolLog` |
| Migrazione | `Database/Migrations/2026_02_25_000001_create_aiassistant_tables.php` — crea `ai_threads`, `ai_messages`, `ai_action_proposals`, `ai_tool_logs` con FK verso `users`, cascadeOnDelete, campi di stato (`pending|cancelled|confirmed|executed|failed`) |
| Application/Actions | `CreateAiActionProposalAction`, `ExecuteAiActionProposalAction` |
| Application/Neuron | `OperatorAssistantAgent` (1575 righe) — agente `NeuronAI\Agent\Agent`, tool-calling verso `Bom`, `Product`, `Customer`, `CustomerSite`, `EnergyBrokerDocument`, `Intervention`, `Warehouse`/stock query, `ProductionPhase`, `WorkOrder`; provider Groq (priorità) o OpenAI via `OpenAILike` |
| Application/Import | `AiQuotationDocumentRewriter`, `AiQuotationNotesRewriter` — riscrittura documenti/preventivi |
| Application/Lookup | `WebAiCompanyLookupProvider` — fallback "web + AI" nella catena di lookup aziende (dopo VIES/OpenCorporates/OpenApi) |
| Application/Reports | `OpenAiInterventionReportExtractor`, `OpenAiQuotationDraftExtractor`, `OpenAiQuotationModificationExtractor` — estrazione strutturata via LLM per report interventi e bozze/modifiche preventivo |
| Application/Security | `PromptInjectionGuard` |
| Application/Speech | `SpeechTranscriptionProviderInterface`, `GroqWhisperTranscriptionProvider`, `OpenAiWhisperTranscriptionProvider`, `BuildSpokenSummaryAction` — STT con provider intercambiabili |
| Application/Llm | `LlmChatJsonClient` |
| Filament/Pages | `AiAssistantChat` (chat operatore, contesto Intervention/Warehouse, proposte azione con ricerca work order/intervention), `AiCommunicationsMonitor` |
| Http/Controllers | `AiActionConfirmController`, `AiChatStreamController` (SSE), `AiProposalsController`, `AiSttController`, `AiTtsController` |
| Provider | `AiAssistantServiceProvider` — bind di `SpeechTranscriptionProviderInterface`, `InterventionReportExtractorInterface`, `QuotationDraftExtractorInterface`, `QuotationModificationExtractorInterface`, `CompanyLookupProviderInterface` (composite VIES→OpenCorporates→AI fallback), `QuotationDocumentRewriterInterface`, `QuotationNotesRewriterInterface`; gate `use-aiassistant` basato su feature flag account; rate limiter dedicato per chat (60/min) e STT (20/min) |
| module.json | keywords: `ai, assistant, stt, sse`; providers: solo `AiAssistantServiceProvider` |

### AI — struttura (target)

Percorso: `/var/www/_bases/base_workorder_fila5/laravel/Modules/AI`

| Area | Contenuto |
|---|---|
| Modelli | **assenti** (`app/Models` è vuota, solo `.gitkeep`) |
| Migrazioni | **assenti** (`database/migrations` vuota) |
| Actions | `CompletionAction` (OpenAI `completions()->create`, modello `gpt-3.5-turbo-instruct` **deprecato** — commento nel file stesso lo segnala), `SentimentAction`, `GeneratePredictionsAction` (genera bozze per un dominio di *prediction market*, non pertinente a work order/interventi), `Predict/GeneratePredictionDraftsAction`, `Predict/GetPredictionDraftFallbackTemplatesAction`, `Sentiment/BasicSentimentAnalyzer` (keyword-matching banale, hardcoded EN words), `Sentiment/TransformersSentimentAnalyzer` |
| Contracts | `SentimentAnalyzer` |
| Datas (spatie/laravel-data) | `CompletionData`, `PredictionData`, `SentimentData`, `OpenAiPredictionMapper` |
| Filament/Pages | `Dashboard` (vista vuota), `Completion` (form prompt → completion/sentiment), `FineTuning` (form iperparametri + upload dataset, POST a endpoint esterno configurabile) |
| Services/Support | `AIService.php.bak`, `AiJsonResponseDecoder.php.bak`, `PredictionDraftFallbackTemplates.php.bak`, `ScalarCaster.php.bak` — **tutti disattivati (estensione `.bak`)**, segno di codice legacy/scartato non ancora ripulito |
| Provider | `AIServiceProvider extends XotBaseServiceProvider` — nessun binding custom, nessuna gate, nessun rate limiter |
| Routes | `routes/api.php` — interamente **commentato** (bozza di endpoint `classify/sentiment/priority/solutions/auto-response/routing/patterns/improvements/process-ticket/stats` mai attivata); `routes/web.php` vuoto |
| Config | `config/ai.php` (model/chat_model/temperature/max_tokens/rate_limit/logging generici OpenAI), `config/config.php` (nome, icona, `fine_tuning_url`) |
| module.json | keywords: `[]`; providers: `AIServiceProvider`, `Filament\AdminPanelProvider` |
| docs/ | contiene già `gestionale-aiassistant-mapping.md` con verdetto preliminare coerente con questa STORY |

## Cosa ha AiAssistant che AI non ha

- **Persistenza conversazionale**: modelli/tabelle `ai_threads`, `ai_messages` (`Infrastructure/Models/AiThread.php`, `AiMessage.php`, migrazione `Database/Migrations/2026_02_25_000001_create_aiassistant_tables.php`). `AI` non ha nessun modello né migrazione.
- **Action proposal governate**: `AiActionProposal` con stati `pending|cancelled|confirmed|executed|failed`, `CreateAiActionProposalAction`/`ExecuteAiActionProposalAction`, controller di conferma (`AiActionConfirmController`). Pattern "AI propone, umano conferma, sistema esegue" completamente assente in `AI`.
- **Tool logging auditabile**: `AiToolLog` con FK a thread/proposal/user, per audit trail delle chiamate tool. Assente in `AI`.
- **Agente con tool-calling reale sul dominio**: `OperatorAssistantAgent` (`Application/Neuron/OperatorAssistantAgent.php`, 1575 righe) integrato con `WorkOrder`, `Intervention`, `Warehouse`/stock, `Bom`/`BomItem`, `Product`, `Customer`/`CustomerSite`, `ProductionPhase`, `EnergyBrokerDocument`. `AI` non conosce nessuno di questi moduli.
- **Chat UI realtime (SSE)**: `AiChatStreamController`, pagina `AiAssistantChat` con contesto (`contextInterventionId`, `contextWarehouseId`), quick prompts, ricerca work order/intervention. `AI` ha solo un form completion/sentiment sincrono.
- **STT/TTS**: `SpeechTranscriptionProviderInterface` con provider Groq/OpenAI Whisper intercambiabili, `AiSttController`, `AiTtsController`, `BuildSpokenSummaryAction`. Totalmente assente in `AI`.
- **Estrazione strutturata da documenti di dominio**: `OpenAiInterventionReportExtractor`, `OpenAiQuotationDraftExtractor`, `OpenAiQuotationModificationExtractor` — casi d'uso specifici (report intervento, bozze/modifiche preventivo). Assente in `AI`.
- **Riscrittura contenuti**: `AiQuotationDocumentRewriter`, `AiQuotationNotesRewriter`. Assente in `AI`.
- **Lookup aziende con fallback AI**: `WebAiCompanyLookupProvider` nella catena `CompositeCompanyLookupProvider` (VIES → OpenCorporates → OpenApi → web+AI). Assente in `AI`.
- **Sicurezza**: `PromptInjectionGuard`. Assente in `AI`.
- **Governance applicativa**: gate `use-aiassistant` basato su feature flag account (`AccountFeatures::hasFeature`), rate limiter dedicati per chat/STT. `AI` non ha alcun gate né rate limiting specifico.
- **Monitor comunicazioni**: pagina Filament `AiCommunicationsMonitor` (integrazione con `Email`/`WhatsApp`). Assente in `AI`.

## Cosa ha AI che AiAssistant non ha

- **Completion generica riusabile**: `CompletionAction` che avvolge `OpenAI::completions()->create()` (nota: usa `gpt-3.5-turbo-instruct`, marcato deprecato dal commento nel file stesso — debito tecnico da correggere se il modulo verrà mantenuto).
- **Generazione "predictions" per prediction market**: `GeneratePredictionsAction`, `Predict/GeneratePredictionDraftsAction`, `Predict/GetPredictionDraftFallbackTemplatesAction`, `PredictionData`, `OpenAiPredictionMapper` — un dominio funzionale (prediction market: title/category/tags/closed_at/liquidity_parameter/stocks_count/is_wagerable) **che non esiste nel dominio work order/gestionale commesse**. Sembra codice ereditato da un altro progetto/scaffold, non pertinente al target.
- **Sentiment analyzer intercambiabile via contratto**: `Contracts/SentimentAnalyzer` con implementazioni `BasicSentimentAnalyzer` (keyword matching, qualità bassa) e `TransformersSentimentAnalyzer` (verso libreria transformers locale). AiAssistant non ha un concetto di sentiment analysis dedicato.
- **Pagina Filament "Fine-tuning"**: form iperparametri (learning rate, batch size, epochs, dataset upload) che invia a un endpoint HTTP esterno configurabile (`config('ai.backend_api.fine_tuning_url')`). AiAssistant non ha nulla di analogo (nessuna gestione fine-tuning).
- **Bozza (mai attivata) di API REST generica per ticket/AI**: `routes/api.php` interamente commentato con endpoint ipotetici (classify, sentiment, priority, solutions, auto-response, routing, patterns, improvements, process-ticket, stats) — concetto orientato a ticketing/helpdesk, non a work order operativi.

## Gap prioritari / raccomandazione di naming

Il gap dominante è che **`AI` è un modulo scaffold/utility generico e in gran parte inattivo o dismesso** (Services/Support tutti `.bak`, routes commentate, nessun modello/migrazione), mentre **`AiAssistant` è un modulo applicativo maturo e integrato nel dominio**, costruito attorno a conversazione persistente + action-proposal governate + tool-calling sul dominio reale (WorkOrders, Interventions, Inventory, Bom, Production, Customers).

Raccomandazione:

1. **Non rinominare/fondere direttamente** `AI` in `AiAssistant`: sono concettualmente due cose diverse (utility AI generiche vs. assistente conversazionale di dominio). Rinominare `AI` non risolverebbe il gap funzionale.
2. **Percorso consigliato — evoluzione incrementale di `AI` verso il pattern `AiAssistant`** (come già indicato nella nota esistente `laravel/Modules/AI/docs/gestionale-aiassistant-mapping.md`), senza creare un nuovo modulo:
   - Portare in `AI` i modelli/migrazioni di persistenza thread/messaggi/proposal/tool-log (equivalenti a `AiThread`, `AiMessage`, `AiActionProposal`, `AiToolLog`).
   - Introdurre il pattern "action proposal + conferma umana + esecuzione tracciata" prima di qualunque azione AI-driven su `WorkOrder`/altri moduli di dominio in `base_workorder_fila5`.
   - Aggiungere tool-calling verso i moduli di dominio realmente presenti nel target (equivalente di `OperatorAssistantAgent`), quando esisteranno i moduli omologhi (vedi `module-mapping.md`: `WorkOrders`, `Inventory`, `Bom`, `Production` sono marcati "nuovo").
   - Valutare se mantenere `GeneratePredictionsAction`/prediction-market come **fuori scope** per questo modulo (dominio estraneo) — spostarlo in un modulo dedicato se serve altrove, o rimuoverlo se morto.
   - Ripulire i file `.bak` in `app/Services` e `app/Support`: o vengono riattivati con QueueableAction pattern, o rimossi (regola repo: no codice morto).
   - Aggiornare `CompletionAction` per rimuovere la dipendenza dal modello deprecato `gpt-3.5-turbo-instruct`.
   - Decidere se attivare o eliminare `routes/api.php` (oggi interamente commentato): se il concetto "ticket AI" non è nel dominio work order, va rimosso per evitare confusione futura.
3. **Manterrei il nome `AI`** come modulo "ombrello" di capacità AI trasversali (completion/sentiment/fine-tuning) e farei evolvere al suo interno una sotto-area equivalente ad `AiAssistant` (namespace `Application/Neuron`, `Infrastructure/Models`, ecc.) piuttosto che introdurre un modulo `AiAssistant` separato — per coerenza con la tabella di `module-mapping.md` che già marca `AiAssistant → AI` come "esistente + gap", non come "nuovo modulo".
4. Tracciare il lavoro di colmatura gap come epic/story dedicate (persistenza thread, action-proposal engine, tool logging, integrazione WorkOrders) collegate a `EPIC.md` e alle issue/discussion già referenziate nella nota esistente (`https://github.com/laraxot/base_workorder_fila5/issues/7`, discussion `#8`).
