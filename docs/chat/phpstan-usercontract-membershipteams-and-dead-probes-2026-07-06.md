---
title: "PHPStan — UserContract::membershipTeams(), probe morti, duplicati case-only — 2026-07-06"
type: chat
created: 2026-07-06
updated: 2026-07-06
tags: [phpstan, modules, multi-agent, locks]
issue: "https://github.com/laraxot/base_techplanner_fila5/issues/34"
related:
  - ./phpstan-modules-progress-2026-07-06-pm.md
  - ./phpstan-pest-this-binding-fix-2026-07-06.md
---

# PHPStan — fix produzione + pulizia probe morti — 2026-07-06

Continua da [phpstan-modules-progress-2026-07-06-pm.md](./phpstan-modules-progress-2026-07-06-pm.md) e
[phpstan-pest-this-binding-fix-2026-07-06.md](./phpstan-pest-this-binding-fix-2026-07-06.md).

## Fix codice di produzione (non test)

- `Modules/Xot/app/Contracts/UserContract.php`: aggiunto `membershipTeams(): BelongsToMany`
  all'interfaccia. Era chiamato su `UserContract` in
  `Modules/User/app/Console/Commands/AssignTeamCommand.php` (`method.notFound` +
  `method.nonObject` a cascata) ma mai dichiarato nel contratto, solo aliasato a
  runtime su `BaseUser` via `use HasTeams { HasTeams::teams as membershipTeams; }`.
  Verificato: un altro agente ha poi raffinato la mia dichiarazione (generics
  `Pivot`, riordino `@property`) — non revertire.
- `Modules/Xot/app/Traits/HasCustomRelations.php`: **eliminato**. `trait.unused`,
  zero `use` in tutto `Modules/`, nessuna doc lo referenzia come vivo. Codice
  morto pre-esistente (link SO/GitHub in testa al file, mai integrato).

## Probe PHPStan morti — rimossi ovunque

`xotPhpstanTraitProbeClasses()` citato in `Modules/Xot/docs/wiki/concepts/phpstan-trait-probes.md`
**non esiste** in `Modules/Xot/helpers/Helper.php`, e `phpstan.neon` non scansiona
nessun probe. Tutte le classi `*PhpstanProbe`/`*PhpstanTraitProbe` erano quindi
file morti senza alcun effetto su PHPStan. Rimossi:

- `Modules/Geo/tests/Fixtures/Traits/*Probe*.php` (7 file)
- `Modules/Lang/app/Providers/TranslatorTraitPhpstanProbe.php`
- `Modules/Tenant/tests/Fixtures/Traits/*Probe*.php` (4 file)

Doc aggiornate: `Modules/Xot/docs/wiki/concepts/phpstan-trait-probes.md`,
`Modules/Job/docs/wiki/concepts/phpstan-format-seconds-trait-probe.md` (quest'ultimo
descriveva anche `Modules/Job/tests/Unit/Traits/FormatSecondsTest.php`, che
importava `Modules\Job\Phpstan\FormatSecondsPhpstanProbe` **inesistente su
disco** — test rotto, non probe da creare. Corretto testando il trait
`FormatSeconds` direttamente via classe anonima, PHPStan+Pest verde).

## Duplicati case-only (stesso bug pattern del `pest.php`/`Pest.php`)

Filesystem case-sensitive con due cartelle/file identici a case diverso —
sempre da eliminare la variante non-PSR-4 (il namespace dichiarato nei file
determina la case corretta):

- `Modules/Geo/tests/fixtures/` (minuscola, duplicato di `tests/Fixtures/`) — rimossa.
- `Modules/Blog/app/tests/` — **posizione sbagliata** (test sotto `app/`, non
  `tests/`), duplicato byte-identico di `Modules/Blog/tests/Unit/SumTest.php`
  (stessa `function sum()` globale dichiarata due volte → conflitto quando si
  analizza tutto `Modules/`). Rimossa la copia in `app/tests/`. (Nota: a fine
  sessione risulta cancellato anche l'altro path da attività concorrente di un
  altro agente — nessun conflitto, solo verificare che `Modules/Blog` resti a
  zero errori.)

## Lock — PestFunctionBridge.php

Ho trovato il lock di un altro agente su
`Modules/Xot/tests/Support/PestFunctionBridge.php` e stavo preparando un
rigeneratore alternativo (scan di tutti i namespace test correnti +
`@param-closure-this TestCase`) per includere Employee/TechPlanner/Blog/ecc.
mai aggiunti al bridge. **Ho abortito e rilasciato il lock**: l'altro agente
sta seguendo una strategia diversa e più pulita (rimuovere i `namespace` dai
file di test invece di far crescere all'infinito un generatore di stub). File
riportato esattamente allo stato originale (nessun diff). Se qualcuno riprende
il lavoro sul bridge: lo script di generazione che ho scritto (scan
`Modules/*/tests/**/*.php`, estrae `namespace`, risolve il `TestCase` più
vicino via filesystem) è in `/tmp/gen_bridge.php` di questa sessione, non
committato — disponibile se utile ma non applicato.

## Verifica

Tutti i file toccati in questa nota: `phpstan analyse` sul file/modulo
specifico → `[OK] No errors` (a parte l'artefatto noto "Ignored error pattern
... was not matched" quando si analizza un sotto-percorso invece di tutto
`Modules/`, non è un errore reale).

— Claude (`claude-sonnet-5`)
