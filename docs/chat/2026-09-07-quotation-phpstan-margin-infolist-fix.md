---
title: "Quotation: PHPStan 1 -> 0 (QuotationInfolist margin closure type), story 5.72.19"
type: handoff
tags: [phpstan, quotation, story-5.72.19, filament-canonical, multi-agent, shared-working-tree]
created: 2026-09-07T19:00:00Z
status: done
agent: claude (sonnet 5) — this session
blocking: false
---

# Quotation — PHPStan 1 -> 0 (QuotationInfolist margin closure), story 5.72.19

## Esito

`./vendor/bin/phpstan analyse Modules/Quotation --memory-limit=-1 --no-progress`
(cache pulita): prima **1 errore**, dopo **0**.

Errore: `QuotationResource/Schemas/QuotationInfolist.php:72` (`argument.type`) —
la closure `->state()` sulla riga `margin` non tipizzava il parametro
`$record` (inferito `mixed`), mentre `QuotationResource::marginSummary()`
richiede `?Quotation`. I file gemelli (`Tables/QuotationsTable.php`,
`Schemas/QuotationForm.php`) avevano gia' il tipo esplicito sulla stessa
closure — solo l'Infolist ne era privo. Fix: tipizzato `?Quotation $record`
+ aggiunto `use Modules\Quotation\Models\Quotation;`.

Dettaglio quality gate completo (pint/phpstan/phpmd/phpinsights/pest) in
`laravel/Modules/Quotation/docs/coverage.md`. Story aggiornata:
`laravel/Modules/Quotation/docs/stories/5.72.19.quotation-filament-canonical.story.md`
(status Closed).

## ATTENZIONE: working tree condiviso, ~28 file WIP non miei

All'avvio di questo intervento il modulo aveva gia' ~28 file modificati/nuovi
non committati (swarm Epic 4 "filament-canonical-structure": estrazione
`QuotationResource/Schemas/`, `QuotationResource/Tables/`,
`QuotationTemplateResource/Schemas/`, `QuotationTemplateResource/Tables/`,
`SiteSurveyResource/Schemas/`). Story correlate gia' presenti (non scritte da
me in questa sessione):
`docs/stories/4.13.sitesurvey-canonical-structure.story.md` (closed),
`docs/stories/4.14.12.quotationtemplate-canonical.story.md` (in_progress).

Verificato con `git ls-tree -r HEAD` che **niente** sotto
`QuotationResource/Schemas/` o `QuotationResource/Tables/` e' mai stato
committato — questo intero lavoro vive solo nel working tree. Il mio fix
(tipo su una closure) e' pero' indipendente dallo slimming del Resource:
`QuotationResource::marginSummary()` esiste gia' `public static` nell'HEAD
committato (usato dall'inline `form()`/`table()` pre-esistenti), quindi
`QuotationInfolist.php` funziona correttamente anche da solo, senza
richiedere che il resto del WIP sia committato.

Per non inglobare lavoro altrui non revisionato (stesso pattern gia'
documentato in `docs/chat/2026-09-07-quotation-typecoverage-fix.md`), il
commit di questa sessione include **solo**: `QuotationInfolist.php`,
`docs/coverage.md`, `docs/stories/5.72.19...story.md`,
`docs/stories/4.14.12...story.md` (nota aggiunta, non chiusa) — niente
`git add -A`. Gli altri ~27 file restano WIP di chi possiede la story 4.14.12
e l'estrazione Schemas/Tables in corso.

## Quality gate — dettagli

- PHPStan `Modules/Quotation`: `[OK] No errors` (era 1). Verificato con
  l'intero WIP presente sul disco.
- Pint `--dirty --test` (working tree completo, incluso il WIP altrui):
  `passed`.
- PHPMD (`tools/phpmd.sh Modules/Quotation text phpmd.xml`): nessuna
  violazione sul file toccato; violazioni pre-esistenti fuori scope elencate
  in coverage.md.
- PHPInsights (`--composer=<path assoluto composer.lock>`, bypassa il bug
  noto di risoluzione path-scoped): Code 87.6%, Complexity 80.7%,
  Architecture 76.5%, Style 83.1%. Nota: e' installato e funzionante
  (smentisce la dicitura "non installato" di doc precedenti nello stesso
  modulo — vedi second-brain `phpinsights_removed_use_exact_gate_commands`).
- Pest `Modules/Quotation/tests`: 31 passed / 76 failed (171 assertions).
  Fallimenti tutti `QueryException` "table not found" (roles, products SKU
  duplicate, metel_catalog_items, customers) sul DB `workorder_data_test` —
  blocco pre-esistente, stesso bucket del second-brain
  `project_test_db_missing_migrations_blocks_feature_tests`, non causato da
  questo fix. `tests/Unit` isolato: 1 passed (7 assertions), nessuna
  regressione.

## Riferimenti

- `laravel/Modules/Quotation/docs/coverage.md`
- `laravel/Modules/Quotation/docs/stories/5.72.19.quotation-filament-canonical.story.md`
- `laravel/Modules/Quotation/docs/stories/4.14.12.quotationtemplate-canonical.story.md`
- `docs/chat/2026-09-07-quotation-typecoverage-fix.md`
