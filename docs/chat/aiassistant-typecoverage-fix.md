---
title: AiAssistant constantTypeCoverage fix (story 2.8, wave B)
type: handoff
tags: [phpstan, typeCoverage, aiassistant, story-2.8]
created: 2026-09-07
---

# AiAssistant — typeCoverage.constantTypeCoverage completato

Story: `docs/stories/2.8.phpstan-constant-type-coverage.story.md` (Wave B).

## Esito

- 10 costanti di classe PHP senza tipo esplicito, tipizzate in 5 file:
  - `app/Security/PromptInjectionGuard.php:13` — `BLOCK_PATTERNS` -> `array`
  - `app/Security/AiOutputGuard.php:30` — `LEAK_MARKERS` -> `array`
  - `app/Security/AiActionPolicy.php:25,27` — `DENIED_UNKNOWN_TYPE`,
    `DENIED_RATE_LIMIT` -> `string`
  - `app/Security/SensitiveDataRedactor.php:25,33,52,88` — `MASK` (`string`),
    `SECRET_PATTERNS`, `PERSONAL_PATTERNS`, `SECRET_KEY_NAMES` (`array`)
  - `app/Speech/BuildSpokenSummaryAction.php:19,21` — `MAX_SENTENCES`,
    `MAX_CHARS` -> `int`
- Solo il token di tipo aggiunto; nessuna logica toccata (i file `Security/*`
  contengono guardrail prompt-injection e redazione dati, invariati).
- `phpstan analyse Modules/AiAssistant` (cache pulita): 10 -> 0 occorrenze
  `typeCoverage.constantTypeCoverage`.
- `phpmd` (`Modules/AiAssistant/phpmd.ruleset.xml`): nessuna violazione nuova
  sulle righe toccate; debito preesistente altrove nel modulo non correlato.
- `phpinsights` non installato in questo repo (rimosso, incompatibile con
  Pest 5) — non eseguito, come da hook post-edit del repo.
- Pest (`Modules/AiAssistant/tests --no-coverage`): 20/20 passati.
- Commit `d793b7c` su `laraxot/dev` (`refactor(aiassistant): type constants
  [story: 2.8]`), push pulito `62ec625..d793b7c`.

## Non toccato (di altri agenti)

- `app/Filament/Pages/AiCommunicationsMonitor.php` — WIP concorrente non mio
  (swap `Filament\Pages\Page` -> `Modules\Xot\Filament\Pages\XotBasePage`),
  lasciato invariato, escluso dal commit.
- `docs/stories/phpstan-fix-1788768735.story.md` — file vuoto non tracciato,
  non creato da questa sessione, non incluso.

## Blocco esterno (non causato qui, solo segnalato)

Dopo il push, un tentativo di ri-verifica con scan su altri moduli
(`Modules/Quotation`) e full-tree fallisce con `Application bootstrap failed`
— `Class "Modules\Xot\Filament\Resources\XotBaseResourceForm" not found`
(poi `XotBaseResourceInfolist not found` al retry pochi secondi dopo, segno
di refactor Xot in corso in tempo reale da un altro agente). Stesso incidente
gia' tracciato in
`docs/chat/2026-09-07-gatedxotbaseresource-migration-bootstrap-break.md`
("urgente, non risolto"): rompe il bootstrap Filament per l'intero
`Modules/`, quindi anche la scansione project-wide della metrica
`constantTypeCoverage`. Non riguarda `Modules/AiAssistant` ne' le costanti
tipizzate qui (verificate pulite prima che il crash esterno comparisse).
Chi lavora sulla story 2.8 su altri moduli: una verifica full-tree affidabile
del coverage project-wide va rimandata a quando Xot sara' stabile.

Dettaglio completo: `Modules/AiAssistant/docs/coverage.md`.
