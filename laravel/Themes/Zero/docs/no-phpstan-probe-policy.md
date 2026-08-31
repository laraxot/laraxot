---
description: Divieto di creare cartelle o file probe per PHPStan anche nei temi.
---

# No PHPStan probe files in themes

## Regola

Nei temi (a partire da `Themes/Zero`) non devono esistere:

- directory `app/Phpstan`
- file che finiscono per `PhpstanProbeModel.php`
- file che finiscono per `PhpstanTraitProbe.php` o nomi simili (probe fittizi)

## Motivazione

I file probe sono scorciatoie artificiali per silenziare PHPStan. La regola del progetto richiede di risolvere il problema a monte: correggere i docblock, usare `@phpstan-ignore` giustificato o scrivere test reali con `XotBaseTestCase`.

Il ragionamento completo (logica/politica/filosofia/religione/zen di questo divieto) è
in `Modules/Xot/docs/wiki/concepts/phpstan-trait-probes.md`.

## Riferimento

Vedi anche:

- `bashscripts/ai/wiki/rules/no-phpstan-probe-models.md`
- `Modules/Xot/docs/phpstan-modules-fix-log.md`
- `bashscripts/ai/wiki/rules/no-phpstan-probe-models.md`
- `Modules/Xot/docs/phpstan-modules-fix-log.md`
- `@/var/www/_bases/base_ptvx_fila5/.windsurf/rules/no-phpstan-probe-models.md`
- `@/var/www/_bases/base_ptvx_fila5/laravel/Modules/Xot/docs/phpstan-modules-fix-log.md`
