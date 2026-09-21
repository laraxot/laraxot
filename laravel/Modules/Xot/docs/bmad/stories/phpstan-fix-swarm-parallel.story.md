---
title: "PHPStan — fix swarm in parallelo su errori repo-wide"
type: story
module: Xot
story_id: "phpstan-fix-swarm-parallel"
slug: phpstan-fix-swarm-parallel
status: done
created: 2026-09-16
updated: 2026-09-16
repository: "git@github.com:festionali/base_ptvx_fila5.git"
owner_session: "quality-gates-exec-improve"
related:
  - bashscripts/docs/prompts/03-quality-gates.md
  - laravel/Modules/Xot/docs/phpstan-status.md
---

# PHPStan — fix swarm in parallelo su errori repo-wide

## Contesto

Esecuzione del gate PHPStan su `Modules` ha rilevato errori in più moduli. Fix in parallelo tramite swarm/subagents con lock per file.

## Scope

- IndennitaResponsabilita/lang/it/compila_indennita_responsabilita.php
- IndennitaResponsabilita/tests/Unit/CompilaPageReadonlyFieldsTest.php
- Rating/app/Models/Traits/HasRatingsTrait.php
- Rating/app/Models/BaseRating.php
- Rating/tests/Unit/HasRatingsTraitOtherOptionTest.php
- Xot/app/Actions/Array/SaveArrayAction.php
- Xot/app/Actions/Arrays/SaveArrayAction.php
- Xot/app/Filament/Resources/Tables/XotBaseResourceTable.php
- Xot/app/Filament/Traits/HasXotTable.php
- Xot/tests/Unit/HasXotTableSortHooksTest.php
- Xot/Tests/Feature/Filament/Traits/HasXotTableReorderingTest.php

## Acceptance criteria

1. PHPStan su `Modules` senza errori.
2. Nessun `@phpstan-ignore` o baseline aggiunta.
3. Fix root-cause, non silenziamento.
4. Test correlati verdi.

## Log

- 2026-09-16: rilevati errori PHPStan, avvio fix in parallelo tramite 3 subagent.
- 2026-09-16: verifica `phpstan analyse Modules` → `[OK] No errors`, exit 0 (17 moduli, 5300+ file). Tutti i file nello scope puliti (testati individualmente). AC soddisfatta.