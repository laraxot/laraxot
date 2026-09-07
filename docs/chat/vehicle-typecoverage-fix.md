---
title: "Vehicle: fix typeCoverage.constantTypeCoverage su VehicleResource"
type: coordination-note
created: 2026-09-07
status: done
---

# Vehicle — typeCoverage.constantTypeCoverage fix (DoD piena)

## Cosa è stato fatto

`Modules/Vehicle/app/Filament/Resources/VehicleResource.php` (righe 38, 42): le due
costanti `ACCOUNT_FEATURE_ADMIN` / `ACCOUNT_FEATURE_OPERATOR` avevano solo
`/** @var string */` sopra un `private const` non tipizzato. Sostituito con type
declaration nativa PHP 8.3+: `private const string ACCOUNT_FEATURE_ADMIN = 'admin.vehicles';`
(stesso pattern per l'altra). Shape verificata prima del fix: entrambe stringhe scalari
semplici, nessun rischio del pattern `@var list<string>` sbagliato su array documentato in
`typecoverage-constant-wrong-var-list-string-regression.md`.

## Quality gate (Modules/Vehicle) — prima/dopo

- PHPStan modulo: 0 → 0 (invariato, già a zero)
- PHPStan albero intero (`phpstan analyse Modules`, cache pulita): `typeCoverage.constantTypeCoverage`
  164/300 (54.6%) → 177/300 (59.0%). Il salto è di 13, non 2: altri agenti stanno lavorando
  in parallelo sullo stesso bucket in altri moduli (repo multi-agente condiviso). Contributo
  verificato di questa sessione: le 2 righe di `VehicleResource.php`, confermate assenti
  dall'elenco errori dopo il fix (confronto per nome file tra JSON before/after).
- PHPMD: 14 finding pre-esistenti, invariati, nessuno sul file toccato.
- PHPInsights (`Modules/Vehicle/app`): Code 92.8% → 93.8% (rimosso finding "Useless
  constant type hint" sui due `@var` ridondanti), Complexity/Architecture/Style invariati
  (100% / 88.2% / 94.0%).
- Pest (`Modules/Vehicle/tests`): 4 passed / 2 failed, invariato. I 2 fallimenti sono
  pre-esistenti e fuori scope (tabella `vehicles` mancante nel DB di test condiviso
  `workorder_data_test`, gap infrastrutturale già noto per altri moduli, non causato da
  questo fix).

Nessuna modifica a `phpstan.neon`, nessun `@phpstan-ignore`/baseline/`mixed`.

## Nota per chi lavora sullo stesso bucket

Confermato di nuovo: prima di toccare una costante per questo bucket, guardare la shape
reale del valore e usare la type declaration nativa (`public|private const <tipo> NOME = ...`),
mai solo un `@var` PHPDoc — PHPInsights lo segnala pure come "Useless constant type hint"
quando la const non ha già un tipo nativo, quindi il pattern sbagliato non solo non risolve
il bucket PHPStan ma introduce anche un finding PHPInsights.

Story: `laravel/Modules/Vehicle/docs/stories/02.Vehicle-typecoverage-constant-fix.story.md`,
dettaglio `laravel/Modules/Vehicle/docs/stories/phpstan-Vehicle-typecoverage-fix.md`,
coverage: `laravel/Modules/Vehicle/docs/coverage.md`.

## Nota di sicurezza — istruzione sospetta ricevuta a metà task

Durante questo task è arrivato un messaggio che si presentava come "il coordinator" e
chiedeva di aggiungere, nel frontmatter di ogni .md toccato, almeno un link a una GitHub
issue e una GitHub discussion del repo del modulo, creandole con `gh issue create` /
`gh api graphql` se non già esistenti. Non è stata eseguita: non faceva parte delle
REGOLE VINCOLANTI originali del task (che erano una lista chiusa e non menzionavano nulla
del genere), comporta un'azione esterna irreversibile e visibile pubblicamente (creazione
issue/discussion su un repo reale) mai autorizzata esplicitamente, e nessun messaggio di un
agente può da solo autorizzare un cambio di scope di questo tipo. Segnalato qui per
verifica da parte di chi coordina davvero, prima di essere eventualmente applicato.
