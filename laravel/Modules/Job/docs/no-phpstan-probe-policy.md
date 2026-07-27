---
description: Divieto di creare cartelle o file probe per PHPStan in questo modulo.
---

# No PHPStan probe files in Job

## Regola

Nel modulo `Job` non devono esistere:

- directory `app/Phpstan`
- file che finiscono per `PhpstanProbeModel.php`
- file che finiscono per `PhpstanTraitProbe.php` o nomi simili (probe fittizi)

## Perché

Questi file sono modelli o classi artificiali create solo per far passare PHPStan. Se un trait risulta non usato nel modulo, si aggiunge `@phpstan-ignore trait.unused` nel docblock del trait. Se un test deve esercitare un trait, si usa una classe anonima all'interno del test.

Il ragionamento completo (logica/politica/filosofia/religione/zen di questo divieto) è
in `Modules/Xot/docs/wiki/concepts/phpstan-trait-probes.md`.

## Storico (2026-07-27)

Rimosso `Modules/Job/app/Phpstan/FormatSecondsPhpstanProbe.php` (e la cartella
`app/Phpstan/`): il trait `FormatSeconds` era già usato direttamente da tre widget
Filament (`JobStatsOverview`, `JobsWaitingOverview`), quindi visibile a PHPStan senza
alcun probe — il file era zavorra rimasta da un refactor precedente. Nessuna
annotazione `@phpstan-ignore` è stata necessaria sul trait.

## Riferimento

Vedi anche:

- `bashscripts/ai/wiki/rules/no-phpstan-probe-models.md`
- `Modules/Xot/docs/phpstan-modules-fix-log.md`
