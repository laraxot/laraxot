---
title: "Story — tests/AuditCoverage reiniettata dal remote dopo il fix PHPStan (2026-09-24)"
type: story
module: Rating
epic: quality
story_id: "auditcoverage-reinjection-2026-09-24"
status: done
qmd: "AuditCoverage reiniettata merge laraxot dev staticMethod.notFound assertTrue AuditBridgeTest phpstan Rating"
related:
  - ../../../../Xot/docs/bmad/stories/phpstan-modules-2026-09-24.story.md
  - ../../../../Xot/docs/wiki/concepts/tests-audit-coverage-forbidden.md
  - ../architecture/rating-data-export-methods.md
---
# auditcoverage-reinjection-2026-09-24

## Perché

Richiesta utente: sistemare le segnalazioni PHPStan (partite da
`RatingData::criteriaToXlsFields()` mancante) analizzando a fondo, BMAD + second brain.

## Finding

1. `RatingData::criteriaToXlsFields()` e gli altri helper entità: già ripristinati
   da `phpstan-modules-2026-09-24` (claude-opus-phpstan). PHPStan sul test: `[OK]`.
2. Run completa `phpstan analyse Modules` delle 20:36: **16 file_errors**, tutti
   `staticMethod.notFound` in `tests/AuditCoverage/0NN-AuditBridgeTestNNN.php`
   (`self::assertTrue()` su classe `final` che non estende TestCase).
3. Root cause: i file sono **tracciati** (commit `223dde0` "Check & fix styling",
   07:42) e presenti su `laraxot/dev`. La story precedente li aveva rimossi con `rm`
   (done alle 20:32); il merge `laraxot/dev` delle 20:33:53 li ha reiniettati.
   `.gitignore` era già conforme ma non agisce sui file tracciati.
4. Alle 20:39:03 un processo concorrente ha committato `git rm` (`75b7527`),
   **non pushato** (`dev` ahead 1): il remote contiene ancora i 16 file.

## Fix

- Nessun edit PHP necessario (race già risolta dal commit `75b7527`).
- Canon corretto: `Xot/docs/wiki/concepts/tests-audit-coverage-forbidden.md` — `rm -rf`
  valido solo se non tracciata; se tracciata `git rm` + push al remote del modulo.

## Gate

```text
phpstan analyse Modules (json) → {"totals":{"errors":0,"file_errors":0}}  exit 0
ensure-audit-coverage-gitignore.sh --check → nessuna violazione
Pest: skip ambientale — DB 10.100.200.53 irraggiungibile (vedi story Xot); host 192.168.1.40
```

## Aperto

- Push di `75b7527` su `laraxot/module_rating_fila5` (outward-facing: richiede ok utente
  o il daemon di auto-commit). Finché non è pushato, altri cloni/merge la reiniettano.
- `docs/sprint-status.yaml` sotto lock `opencode-phpstan`: voce da aggiungere, e le
  righe `Rating/5.228…` / `Rating/rating-xls-fields-move-to-ratingdata` ("resta nel trait")
  sono superate — la sede canonica è `RatingData` (architecture `rating-data-export-methods.md`).

## Secondo incidente (20:40) — harness Xot cancellati

Rerun di verifica: **394 file_errors** in 14 moduli, 342 `class.notFound` su
`Modules\Xot\Tests\{FilamentSchemaCoverage,ModuleDeepCoverage,ModuleBusinessCoverage,ModuleExecuteCoverage,ModuleRemainingCoverage}`.
I 5 file erano cancellati nel working tree Xot (non committato, nessun lock, HEAD Xot
fermo alle 20:34 → non un'operazione git). Canon story Xot 5.28: helper **canonici,
non generati e non eliminabili**. Probabile confusione con lo scaffold vietato
`AuditCoverage` (nome simile, "coverage padding").

Fix: `git checkout HEAD --` dei 5 file (verbatim, regola recuperare-codice-cancellato),
lock/unlock per file, `php -l` OK.

Gate finale: `phpstan analyse Modules` → `{"errors":0,"file_errors":0}` exit 0.
