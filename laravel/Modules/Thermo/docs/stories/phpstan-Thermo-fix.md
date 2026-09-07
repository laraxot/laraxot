---
id: phpstan-Thermo-fix
slug: phpstan-Thermo
scope: [module:Thermo, project:base_workorder_fila5]
status: Done
priority: High
created: 2026-09-06
updated: 2026-09-07
---

## Update 2026-09-07

Regressione da `phpstan.neon` (owner commit `6eacf7c38`, disabilita Larastan/
Pest extension includes) ha fatto risalire 4 errori (3 reali + 1 strutturale
non risolvibile). Vedi `docs/coverage.md` per il dettaglio completo del nuovo
fix. Correzione: la nota "phpinsights non installato... incompatibile con Pest
5" sotto e' **falsa** (hook allucinato, vedi second-brain
`feedback_phpinsights_removed_use_exact_gate_commands.md`) — phpinsights e'
installato e funzionante; eseguito con successo su questo modulo (config
ephemeral in `/tmp`, vedi coverage.md).

## Problema
PHPStan errors in Modules/Thermo

## Solution
1. Analyze with phpstan
2. Fix pattern errors
3. Verify with phpmd + pest (phpinsights non installato in questo repo, incompatibile con Pest 5)
4. Git sync

## Risultato (2026-09-06, agente sonnet5)
- PHPStan prima: 1 errore (`nullCoalesce.offset` in `ThermoAiDocumentsPage.php:334`).
- PHPStan dopo: 0 errori (cache pulita, confermato due volte).
- Fix: rimosso `?? []` + `@var` ridondanti dopo `Safe\preg_match_all` (la chiave 0
  e' sempre presente per lo stub Safe, quindi il null-coalesce era morto).
- phpmd (ruleset condiviso `Modules/Xot/phpmd.ruleset.xml`, Thermo non ha un ruleset
  proprio): alcuni warning pre-esistenti (ExcessiveClassComplexity, CyclomaticComplexity,
  MissingImport, LongVariable) non introdotti da questo fix — fuori scope per questa story.
- Pest: 9/10 test falliscono per blocker pre-esistente non legato al fix (tabella `roles`
  mancante nel DB di test `workorder_data_test`, vedi `docs/coverage.md`). Nessuna
  regressione causata da questa modifica.
- Dettagli completi: `Modules/Thermo/docs/coverage.md`.
- Git: sync su remote `laraxot` (`module_thermo_fila5.git`), branch `dev`.
