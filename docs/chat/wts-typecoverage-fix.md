---
title: "Wts: typeCoverage.constantTypeCoverage fix — EcosOdbcConnection::CONNECT_TIMEOUT_SECONDS"
type: coordination
tags: [phpstan, typecoverage, wts, bmad]
created: 2026-09-07T14:00:00Z
agent: claude (sonnet 5) — dedicated Wts DoD subagent
status: done-partial-scope
issue: https://github.com/laraxot/base_workorder_fila5/issues/29
discussion: https://github.com/laraxot/base_workorder_fila5/discussions/23
---

# Wts — typeCoverage.constantTypeCoverage fix + full quality gate

Task ricevuto: portare `laravel/Modules/Wts` a Definition of Done piena sul fix specifico
del bucket `typeCoverage.constantTypeCoverage` (percentuale repo-wide, ~148/300 costanti
tipizzate, non visibile con `phpstan analyse Modules/Wts` da solo — serve
`phpstan analyse Modules` sull'intero albero).

## Cosa ho trovato all'avvio

- `Modules/Wts` era gia' a **0 errori PHPStan scoped** da una sessione precedente lo stesso
  giorno (`docs/stories/phpstan-Wts-fix.md`, chiuso, ultimi 2 errori chillerlan/php-qrcode
  risolti).
- `app/Ecos/EcosOdbcConnection.php` aveva gia' un WIP non committato e non pushato (di una
  sessione precedente) che aggiungeva `/** @var int */` sopra la const — pattern
  **insufficiente**: un PHPDoc non sposta `constantTypeCoverage`, serve il type declaration
  reale sulla dichiarazione `const` (PHP 8.3+). Confermato con `docs/chat/typecoverage-constant-wrong-var-list-string-regression.md`
  (stesso repo) e con l'esempio gia' verificato `Rating/app/Filament/Blocks/Rating.php`.
- Nessun'altra `const` nel modulo (`grep -rn "const " Modules/Wts/app`) — unico punto di
  fix.

## Fix applicato

```diff
- /** @var int */
- private const CONNECT_TIMEOUT_SECONDS = 8;
+ private const int CONNECT_TIMEOUT_SECONDS = 8;
```

## Quality gate (Modules/Wts)

- **PHPStan** (scoped): 0 -> 0 errori (nessuna regressione; il fix non e' misurabile a
  livello di modulo, solo sul rescan full-tree repo-wide, non ri-eseguito in questa sessione
  per costo — macchina condivisa con load average 70-138 per tutta la durata del task,
  decine di agenti concorrenti attivi, confermato con `ps aux`/`uptime`).
- **PHPMD** (`tools/phpmd.sh Modules/Wts/app text phpmd.xml`): nessuna nuova segnalazione sul
  file toccato.
- **PHPInsights** (`tools/phpinsights.sh analyse Modules/Wts/app --no-interaction`, exit 0):
  Code 88.7%, Complexity 72.5%, Architecture 82.4%, Style 86.7% — punteggi di modulo
  pre-esistenti, non impattati dalla riga toccata.
- **Pest** (`Modules/Wts/tests`, full run): **184 failed / 27 passed (84 assertions)**,
  2305s (~38 min, fortemente rallentato dalla contesa di sistema). Tutti i fallimenti
  visibili sono `SQLSTATE[42S02]: Base table or view not found` sul DB di test condiviso
  (`workorder_data_test.roles`, `.wts_technical_sheet_snapshots`, ecc.) — stesso gap
  pre-esistente gia' documentato per Thermo/Vehicle/Rating/Inventory nello stesso giorno
  (`docs/chat/2026-09-06-sonnet5-pest-fixes-plus-unclaimed-modules-wave.md`). Non e' una
  regressione del fix: nessun test esercita `CONNECT_TIMEOUT_SECONDS` direttamente (usato
  solo dentro `PDO::ATTR_TIMEOUT`, e ogni test Ecos-correlato mocka `EcosOdbcConnection`
  interamente). Non toccate migrazioni ne' eseguito `migrate`/`migrate:fresh` (regola
  "data e' sacra"). Dettaglio completo in `Modules/Wts/docs/coverage.md`.

## Debito preesistente NON toccato (fuori scope)

- Il gap di migrazioni sul DB di test condiviso (`workorder_data_test`) che causa i 184
  fallimenti Pest — pre-esistente, cross-modulo, richiede intervento del proprietario del
  DB, non del singolo modulo.
- Debito PHPMD/PHPInsights diffuso nel modulo (complessita' ciclomatica alta in diverse
  Action, liste parametri lunghe, ecc.) — nessuna regressione introdotta, non toccato
  perche' fuori dallo scope specifico di questo task (solo il fix typeCoverage + gate sul
  file toccato).
- Non ho ri-eseguito lo scan full-tree `phpstan analyse Modules` per misurare l'impatto
  esatto sulla percentuale `constantTypeCoverage` repo-wide (stimato +1 costante tipizzata
  su ~300) — costo/contesa eccessivi in questa sessione, pattern di fix identico e gia'
  verificato altrove nella stessa campagna.

## BMAD

Story: `Modules/Wts/docs/stories/phpstan-zero-Wts.story.md` (riutilizzato uno stub vuoto
pre-esistente invece di crearne uno nuovo, dedupe rule). Issue di tracking modulo:
https://github.com/laraxot/module_wts_fila5/issues/1 (Discussions disabilitate su quel
repo, `has_discussions:false` verificato via API — non abilitate unilateralmente).

## Git

Repo indipendente `laravel/Modules/Wts` (remote `laraxot` -> `module_wts_fila5.git`,
branch `dev`). Commit e push dettagliati a valle di questa nota (vedi commit hash nel
report finale dell'agente).
