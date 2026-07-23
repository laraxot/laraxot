---
title: "AiAssistant — fix reshape Laraxot (completamento)"
type: concept
tags: [aiassistant, laraxot, reshape, xotbase, fix]
created: 2026-07-24
updated: 2026-07-24
qmd: "AiAssistant XotBaseModel XotBaseServiceProvider migration reshape fix"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./README.md"
  - "./import-status.md"
  - "../../../../docs/gestionale-commesse-comparison/canonical-module-structure.md"
---

# AiAssistant — completamento reshape Laraxot

> Il reshape strutturale (cartelle `app/`, `database/migrations/`) era già stato spostato da un agente parallelo, ma il **contenuto** dei file non era stato riallineato: modelli su `Illuminate\Database\Eloquent\Model` invece di `XotBaseModel`, `App\Models\User` invece di `Modules\User\Models\User`, migrazione monolitica unica invece di 1-per-modello, e il `ServiceProvider` era stato sostituito con uno scaffold minimo che **perdeva** tutti i binding custom (speech provider, company lookup, estrattori AI, Gate `use-aiassistant`, rate limiter chat/stt).

## Fix applicati in questa sessione

1. **Modelli** (`app/Models/{AiThread,AiMessage,AiActionProposal,AiToolLog}.php`): `extends XotBaseModel` (namespace `Modules\Xot\Models\XotBaseModel`), relazioni `User` puntano a `Modules\User\Models\User` (non `App\Models\User`).
2. **Migrazioni**: la vecchia `2026_02_25_000001_create_aiassistant_tables.php` (4 tabelle in un file, `Schema::create` diretto) è stata rimossa e sostituita da 4 file `create_{tabella}_table.php`, ciascuno `extends XotBaseMigration` con `tableCreate()` + `updateTimestamps()`.
3. **`AiAssistantServiceProvider`**: torna a `extends XotBaseServiceProvider` (chiamando `parent::register()`/`parent::boot()`) **con tutti i binding originali ripristinati**: `SpeechTranscriptionProviderInterface`, `InterventionReportExtractorInterface`, `QuotationDraftExtractorInterface`, `QuotationModificationExtractorInterface`, `CompanyLookupProviderInterface` (composite VIES/OpenCorporates/OpenAPI/web-AI), `QuotationDocumentRewriterInterface`, `QuotationNotesRewriterInterface`, Gate `use-aiassistant`, rate limiter `aiassistant-chat`/`aiassistant-stt`. Import corretti ai namespace reshapati dagli altri moduli (`Modules\Customer\Lookup\*`, `Modules\Quotation\AI\*`, `Modules\Quotation\Import\*`, `Modules\Intervention\AI\*` — erano ancora `Application\...` nel file rotto).
4. **`Neuron/OperatorAssistantAgent.php`, `Filament/Pages/AiAssistantChat.php`**: `App\Models\User` → `Modules\User\Models\User`.

## Verificato ma non toccato (TODO)

- `app/Filament/Pages/{AiAssistantChat,AiCommunicationsMonitor}.php` estendono `Filament\Pages\Page` puro, non un eventuale `XotBasePage` — lasciato così perché non esiste un pattern XotBase-Page univoco già verificato in questo repo per pagine custom (Employee usa `XotBaseResource`/widget, non pagine standalone comparabili). Da rivedere quando si affrontano i Filament Resources del modulo.
- `app/Http/Controllers/*` estendono `Illuminate\Routing\Controller` — coerente con l'assenza di un controller-base Xot nel resto del repo (Employee fa lo stesso), non è un gap.
- `php -l` passato su tutti i file toccati; **PHPStan non eseguito** in questa sessione (bootstrap applicativo instabile per lo stesso motivo già documentato in `Customer/docs/gestionale-commesse-port.md` — tema `Two` mancante sotto `Themes/`).
