---
title: "PHPStan typeCoverage.constantTypeCoverage — Catalog chiuso (story 2.8, Wave C)"
type: coordination
tags: [catalog, phpstan, type-coverage, story-2.8]
created: 2026-09-07T15:30:00Z
status: done
agent: claude (sonnet 5) — this session
related:
  - ../stories/2.8.phpstan-constant-type-coverage.story.md
  - ./INDEX.md
---

# Catalog: constantTypeCoverage chiuso

Eseguita la Wave C dello story `docs/stories/2.8.phpstan-constant-type-coverage.story.md`
per il modulo Catalog.

## Cosa e' stato fatto

12 costanti `protected const` senza tipo esplicito, tutte nel pattern
`HasAccountFeatureVisibility` (lette via `constant()` in
`app/Support/Concerns/HasAccountFeatureVisibility.php`), in 6 Filament
Resources:

- `app/Filament/Resources/CatalogModelResource.php` (righe 29, 32)
- `app/Filament/Resources/MetelCatalogResource.php` (righe 40, 43)
- `app/Filament/Resources/CatalogMeasureUnitResource.php` (righe 30, 33)
- `app/Filament/Resources/CatalogPartTypeResource.php` (righe 29, 32)
- `app/Filament/Resources/CatalogFrequencyResource.php` (righe 30, 33)
- `app/Filament/Resources/ProductResource.php` (righe 35, 38)

Fix: `protected const ACCOUNT_FEATURE_ADMIN = 'admin.catalog';` →
`protected const string ACCOUNT_FEATURE_ADMIN = 'admin.catalog';` (idem per
`ACCOUNT_FEATURE_OPERATOR`), rimosso il `@var string` PHPDoc ridondante.
Pura promozione di tipo, zero cambio di comportamento.

## Nota tecnica per gli altri agenti sulla stessa story

`typeCoverage.constantTypeCoverage` (tomasvotruba/type-coverage) e' una
regola **project-wide**: `ConstantTypeCoverageRule::processNode()` chiama
`ScopeConfigurationResolver::areFullPathsAnalysed($scope)` e ritorna array
vuoto se non stai analizzando l'intero `paths: ./Modules/` configurato in
`phpstan.neon`. Quindi:

```
./vendor/bin/phpstan analyse Modules/Catalog --no-progress --memory-limit=-1
```

ritorna SEMPRE "No errors" per questa regola, indipendentemente dallo stato
reale del modulo. L'unico modo per vedere gli errori reali e' un full-tree
scan:

```
./vendor/bin/phpstan clear-result-cache
./vendor/bin/phpstan analyse --no-progress --memory-limit=-1 --error-format=raw | grep constantTypeCoverage
```

(clear-result-cache prima, perche' con edit concorrenti di altri agenti la
result cache resta stale). Usato `grep -rnE` sui `const` del modulo per
trovare i candidati, poi confermato con lo scan full-tree.

## Verifica

- `php -l` pulito su tutti e 6 i file.
- Full-tree scan post-fix (cache pulita): 0 occorrenze su `Modules/Catalog/`,
  289 → 267/300 = 89.0% project-wide (dal 49% baseline di story), 33
  occorrenze residue fuori Catalog (Billing, Compliance, Platform,
  Quotation, WorkOrder — non miei, altri wave).
- `./tools/phpmd.sh Modules/Catalog text Modules/Catalog/phpmd.ruleset.xml`:
  violazioni pre-esistenti non correlate (CouplingBetweenObjects su 4
  Resources, UnusedFormalParameter su 5 Policy + 6 factories, LongVariable
  su `tests/TestCase.php`), nessuna sulle righe toccate da questo fix, fuori
  scope story 2.8.
- `./vendor/bin/pest Modules/Catalog/tests --no-coverage`: 6 failed / 5
  passed / 21 assertions. Tutti i fail sono
  `SQLSTATE[42S02] workorder_data_test.metel_catalogs` mancante — blocker DB
  test pre-esistente (vedi second-brain
  `project_test_db_missing_migrations_blocks_feature_tests`), non causato da
  questo fix. Verificato che `BaseModel.php` e `BasePivot.php` hanno gia'
  `$connection = 'catalog'` impostato correttamente.
- phpinsights: non installato in questo repo (rimosso, incompatibile con
  Pest 5).

## Anomalia trovata, non toccata

Al momento di iniziare, `git status` in `Modules/Catalog` mostrava modifiche
non staged pre-esistenti e non mie su `docs/coverage.md` (contenuto
retrocesso rispetto a HEAD — due sezioni intere rimosse, probabile lavoro in
corso di un altro agente, non un revert deliberato mio) e
`docs/prompts/01.md`, piu' un `phpmd.ruleset.xml` non tracciato. Non le ho
toccate ne' incluse nel mio commit per non sovrascrivere lavoro altrui
attivo (vedi anche `docs/chat/2026-09-07-metelcatalogitem-not-a-pivot-user-decision.md`
per il contesto di edit concorrenti in corso su questo modulo). Ho comunque
scritto la mia sezione di changelog in coda a `docs/coverage.md` (append,
nessuna rimozione), lasciata non committata insieme al resto del WIP
altrui — chi possiede quel WIP la trovera' li' quando fa il proprio commit.

## Git

- Commit: `6d8d95a` — "refactor(catalog): type constants [story: 2.8]"
- Push: `laraxot dev` (unico remote), fetch prima del push gia' allineato,
  nessun merge necessario.

## Stato

Catalog: **DONE** per story 2.8. Nessun blocco.
