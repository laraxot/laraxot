---
title: "Porting gestionale_commesse — AiAssistant (verifica flag true, modulo permanente separato)"
type: concept
tags: [import, gestionale-commesse, aiassistant, reshape, xotbase, bmad-readiness]
created: 2026-07-24
updated: 2026-07-24
qmd: "porting AiAssistant reshape XotBase modulo permanente separato audit BMAD flag true verifica"
related:
  - "./import-status.md"
  - "./visibility.md"
  - "../../../../docs/wiki/concepts/laraxot-module-canonical-structure.md"
  - "../../../../docs/wiki/concepts/porting-external-module-playbook.md"
  - "../../AI/docs/gestionale-commesse-port.md"
---

# Porting gestionale_commesse — AiAssistant (verifica, 2026-07-24)

## Contesto

Un BMAD readiness audit (2026-07-24) segnalava `AiAssistant` come registrato `true` in
`modules_statuses.json` ma con lo stato di reshape "non verificato/sospetto" (possibile flag
stantio da una sessione caotica precedente). Decisione finale dell'utente: `AiAssistant` resta
modulo **permanente e separato**, reshapato come gli altri moduli completati — **non** va fuso
in Tenant/Activity/Xot/AI.

## Verifica reale del flag `true`

Controllo diretto (non ci si è fidati solo del flag) di tutto il contenuto del modulo:

- `app/Models/{AiThread,AiMessage,AiActionProposal,AiToolLog}.php` — tutti estendono
  `Modules\AiAssistant\Models\BaseModel extends XotBaseModel`, con PHPDoc `@property` completo.
- `database/migrations/2026_07_24_0000{1..4}_*` — 4 migrazioni, una per modello, tutte
  `extends XotBaseMigration` con `tableCreate()`/`updateTimestamps()`.
- `app/Actions/{Create,Execute}AiActionProposalAction.php` — `use QueueableAction;`, singolo
  `execute()`.
- `app/Providers/{AiAssistantServiceProvider,RouteServiceProvider,EventServiceProvider}.php` —
  `XotBaseServiceProvider`/`XotBaseRouteServiceProvider`, pattern coerente col resto del repo.
- `module.json` + `composer.json` — PSR-4 → `app/`, provider dichiarato, autori con Davide
  Cavallini già presenti.
- `database/factories`, `database/seeders` — namespace corretti (`Modules\AiAssistant\Database\
  {Factories,Seeders}`), nessun bug di doppio backslash.
- `routes/{web,api}.php` presenti.
- Nessuna cartella `Domain/Application/Infrastructure` residua.

**Conclusione: il flag `true` era corretto**, non stantio. Il modulo era già stato reshapato in
una sessione precedente (vedi `docs/import-status.md`, checklist datata 2026-07-23). Il flag
resta `true`.

## Gap minori trovati e colmati in questa sessione

- **`lang/{it,en}/aiassistant.php`**: assenti, creati con le chiavi base per le 4 entità
  (`AiThread`, `AiMessage`, `AiActionProposal`, `AiToolLog`).
- **`database/Migrations/` (maiuscola) residua**: cartella con una singola migrazione monolitica
  vecchio-stile (`2026_02_25_000001_create_aiassistant_tables.php`, `Schema::create()` diretto
  per tutte e 4 le tabelle in un colpo solo) — superata dalle 4 migrazioni granulari già presenti
  in `database/migrations/` (minuscola, una per modello, `XotBaseMigration`). Cartella duplicata
  rimossa per evitare che un discovery case-insensitive (es. filesystem non case-sensitive) la
  esegua due volte o causi confusione.
- **`tests/`**: cartelle `Feature`/`Unit` vuote, nessun Pest test presente per questo modulo.
  Non aggiunti in questa sessione per restare nello scope di verifica/correzione flag; annotato
  come debito residuo.
- **PHPStan stub `NeuronAI\Agent\Agent`**: `setChatHistory()`/`toolMaxRuns()` erano tipizzati
  `: self` nello stub locale (`phpstan-stubs/NeuronAI/Agent/Agent.php`), che risolve al tipo
  della classe dichiarante (`Agent`) e non alla late-static-binding. Questo rompeva la chain
  fluente `(new OperatorAssistantAgent())->setChatHistory(...)->forSession(...)` in
  `AiChatStreamController`, perché PHPStan vedeva l'oggetto tornare come `Agent` (che non ha
  `forSession()`, definito solo sulla sottoclasse) invece che come `OperatorAssistantAgent`.
  Corretto lo stub a `: static` — nessuna modifica al codice applicativo, solo al type-hint del
  mock usato per l'analisi statica.

## Nota su `OperatorAssistantAgent` (NON deferred qui, a differenza di `Modules/AI`)

Il playbook per `Modules/AI` (`docs/gestionale-commesse-port.md` di quel modulo) documenta
l'esclusione deliberata della porting dell'agente hardcoded a tool-calling (~1200 righe) durante
il gap-fill dei modelli `AiThread`/`AiMessage`/`AiActionProposal`. In `AiAssistant`, invece,
`app/Neuron/OperatorAssistantAgent.php` (1579 righe) **è già presente per intero**, portato in
una sessione precedente a questa verifica — non è stato aggiunto né rimosso qui. Resta come
componente stabile del modulo: gestisce provider Groq/OpenAI, i tool NeuronAI e la sessione
(`forSession()`) usati da `AiChatStreamController`. Non essendo stato toccato in questa sessione
e non essendoci evidenza che sia rotto (PHPStan pulito), non viene retrocesso a "deferred": la
regola di esclusione di `Modules/AI` non si applica retroattivamente a codice già portato e
funzionante altrove.

## Verifica

- `./vendor/bin/phpstan analyse Modules/Platform Modules/AiAssistant --memory-limit=-1` → 0
  errori reali (1 riga di rumore per un ignore-pattern globale del `phpstan.neon` condiviso,
  non pertinente a questo scope).
- `modules_statuses.json`: `"AiAssistant": true` confermato dopo verifica reale del contenuto,
  non lasciato per fiducia cieca nel flag precedente.
