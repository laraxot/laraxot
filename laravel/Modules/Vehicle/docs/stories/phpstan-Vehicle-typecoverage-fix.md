---
id: phpstan-Vehicle-typecoverage-fix
slug: phpstan-Vehicle-typecoverage
scope: [module:Vehicle, project:base_workorder_fila5]
status: Done
priority: Medium
created: 2026-09-07
updated: 2026-09-07
---

## Problema

Campagna repo-wide "PHPStan Modules a zero errori": il bucket dominante oggi è
`typeCoverage.constantTypeCoverage`, una percentuale calcolata su TUTTO l'albero
`Modules/` (non per singolo modulo). `phpstan analyse Modules/Vehicle` da solo non la
mostra (il modulo è già a 0 errori); serve `phpstan analyse Modules` per vederla.

In `app/Filament/Resources/VehicleResource.php` (righe 38 e 42) le due costanti
`ACCOUNT_FEATURE_ADMIN` / `ACCOUNT_FEATURE_OPERATOR` avevano solo un PHPDoc `@var string`
sopra la dichiarazione `private const`, senza type declaration nativa PHP 8.3+. PHPStan
conta questo come costante non tipizzata ai fini del bucket.

## Solution

1. Verificata la shape reale del valore: entrambe sono stringhe scalari semplici
   (`'admin.vehicles'`, `'operator.vehicles'`), non array — nessun rischio del pattern
   sbagliato documentato in `docs/chat/typecoverage-constant-wrong-var-list-string-regression.md`.
2. Sostituito `/** @var string */` + `private const ACCOUNT_FEATURE_ADMIN = ...` con
   `private const string ACCOUNT_FEATURE_ADMIN = ...` (type declaration nativa PHP 8.3+),
   stesso pattern per `ACCOUNT_FEATURE_OPERATOR`. Il PHPDoc `@var` era ridondante e già
   segnalato da PHPInsights come "Useless constant type hint" (vedi sotto) — rimosso.
3. Verificato con `phpstan analyse Modules` (albero intero, cache pulita) che le due righe
   non compaiono più tra i messaggi `typeCoverage.constantTypeCoverage`: percentuale globale
   passata da 164/300 (54.6%) a 177/300 (59.0%) — il salto di 13 (non 2) è dovuto ad altri
   agenti che lavorano in parallelo sullo stesso bucket in altri moduli (repo condiviso,
   multi-agente concorrente); il contributo verificabile di questa sessione sono le 2
   costanti di Vehicle, non più presenti nell'elenco errori dopo il fix.
4. Nessuna modifica a `phpstan.neon`, nessun `@phpstan-ignore`, nessun baseline, nessun
   `mixed`. `declare(strict_types=1);` già presente nel file (non toccato, già conforme).

## Effetto collaterale positivo (PHPInsights)

PHPInsights segnalava già, prima del fix, `[Code] Useless constant type hint` su
`VehicleResource.php:37` e `:41` (i due `@var string` sopra le const) — "Useless
documentation comment". Il fix li rimuove: Code score passa da 92.8% a 93.8% sul modulo.
Nessun'altra metrica PHPInsights cambia (Complexity 100%, Architecture 88.2%, Style 94.0%
invariati).

## Acceptance criteria

- [x] `./vendor/bin/phpstan analyse Modules/Vehicle --no-progress --memory-limit=-1` → 0 errori (invariato, già 0 prima)
- [x] `./vendor/bin/phpstan analyse Modules --no-progress --memory-limit=-1` (cache pulita) → le 2 righe di `VehicleResource.php` non compaiono più tra i messaggi `typeCoverage.constantTypeCoverage`
- [x] Nessuna modifica a `phpstan.neon`, nessun `@phpstan-ignore`/baseline, nessun `mixed`
- [x] `declare(strict_types=1);` presente nel file toccato
- [x] PHPMD invariato (14 finding pre-esistenti, nessuno sul file toccato, nessun nuovo finding)
- [x] PHPInsights: Code 92.8% → 93.8% (rimosso "Useless constant type hint"), altre metriche invariate
- [x] Pest (`Modules/Vehicle/tests`) invariato: 4 passed / 2 failed — i 2 fallimenti sono
      pre-esistenti e fuori scope (tabella `vehicles` mancante nel DB di test condiviso
      `workorder_data_test`, stesso gap già documentato in `../coverage.md` dalla story
      precedente), non causati né toccati da questo fix
- [x] `docs/coverage.md` aggiornato con before/after
- [x] Git sync su repo indipendente Vehicle (vedi commit in `../coverage.md`)

Dettaglio completo before/after: `../coverage.md`.
