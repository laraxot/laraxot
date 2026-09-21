---
id: phpstan-workorder-fix
slug: phpstan-workorder
scope: [module:WorkOrder, project:base_workorder_fila5]
status: Done
priority: High
created: 2026-09-06
updated: 2026-09-07
---

## Problema (risolto)
75 errori PHPStan reali in Modules/WorkOrder: deprecazioni Filament v4→v5
(`Table::actions()/bulkActions()` → `recordActions()/toolbarActions()`,
`Action::form()`/`BaseFilter::form()` → `schema()`), `cast.*` su mixed, un
`generics.notGeneric` su `WorkOrder.php` (docblock generico obsoleto sul trait
condiviso `HasXotFactory`, non generico), e una cascata `mixed` cross-modulo in
`tests/Feature/PeriodicSummaryTest.php` (chiamava `Modules\User\Models\User::factory()`,
che risolve a `mixed` perche' `BaseUser` non ha un override esplicito di
`newFactory()` — vedi second-brain
`project_larastan_factory_mixed_needs_newfactory_override.md`). Risolto **dentro
WorkOrder** con un helper locale che chiama `UserFactory::new()->create()`
direttamente e ne narrowa il tipo, senza toccare il modello User (cross-repo).

## Fix applicati
1. `app/Models/WorkOrder.php`: rimosso `@phpstan-use HasXotFactory<WorkOrderFactory>`
   (il trait non e' generico), aggiunto `newFactory(): WorkOrderFactory` esplicito.
2. `app/Models/Profile.php`: rimosso `use Illuminate\Database\Eloquent\Factories\HasFactory;`
   ridondante (gia' fornito da `XotBaseModel`/`HasXotFactory`), causava un errore
   fatale di dichiarazione incompatibile dopo che `HasXotFactory::factory()` ha
   guadagnato i parametri `$count`/`$state`.
3. Tutte le deprecazioni Filament v4→v5 elencate sopra, in 12 file
   Resource/Page/RelationManager.
4. `tests/Feature/PeriodicSummaryTest.php`: helper `createSummaryUser()` locale,
   narrowing esplicito invece di cast/ignore.

## Verifica
- PHPStan: 75 → **0 errori** (`phpstan analyse Modules/WorkOrder`, cache pulita,
  verificato due volte)
- PHPMD: nessuna nuova segnalazione introdotta (dettaglio in `docs/coverage.md`)
- Pest: 7 passed / 45 failed / 15 skipped, **invariato** prima/dopo — i 45 fallimenti
  sono tutti blocker pre-esistenti di schema DB test mancante (`workorder_data_test`),
  non causati ne' risolvibili da questo fix (vedi `docs/coverage.md` +
  second-brain `project_test_db_missing_migrations_blocks_feature_tests.md`)
- Git: commit+push su `laraxot/dev`, repo dedicato `Modules/WorkOrder`

## Update 2026-09-21 — docblock relation per Compliance, nessun errore nuovo qui

Nell'ambito della campagna phpstan cross-modulo (vedi story gemella
`Modules/Compliance/docs/stories/xot-18.13-compliance-phpstan-22-errors.story.md`,
sezione Update 2026-09-21), aggiunti a `app/Models/WorkOrder.php` i tag
`@property-read Customer $customer` e `@property-read CustomerSite|null
$site` (documentazione IDE/PHPStan delle relation gia' esistenti, nessuna
relation nuova). Non risolve da solo il falso positivo `nullsafe.neverNull`
su `$site` visto da Compliance (bug noto Larastan su unioni nullable su
magic-property omonime a una relation, dettagliato nella story gemella): il
fix vero e' nel codice chiamante (Compliance), con guardia `instanceof`
invece di `?->`.

Verifica: `phpstan analyse Modules/WorkOrder --memory-limit=-1` cache pulita
→ 0 errori reali, nessuna regressione.
