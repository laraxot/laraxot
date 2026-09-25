---
title: "PHPStan Modules — stato e fix"
type: troubleshooting
sources: ["phpstan analyse Modules"]
confidence: verified
updated: 2026-09-23
tags: [phpstan, modules, bootstrap, pest, seeders, xot, trait-probes]
related:
  - concepts/phpstan-cluster-map-and-false-friends.md
  - concepts/phpstan-level10.md
  - concepts/phpstan-trait-probes.md
  - concepts/xot-seed-model-once.md
  - concepts/phpstan-pest-bridge-discipline.md
qmd: "phpstan analyse Modules zero errori pest bridge xotSeedModelOnce"
---

# PHPStan su `Modules` — stato e fix

## Comando che certifica

```bash
cd laravel
php -d memory_limit=-1 ./vendor/bin/phpstan analyse --no-progress --memory-limit=-1
```

**Senza path CLI.** Un argomento `Modules` sovrascrive `parameters.paths` e spegne
`tomasvotruba/type-coverage`. Per lavorare su un modulo: `analyse Modules/<Nome>`.
Per dichiarare zero: i due conteggi (`analyse` e `analyse Modules`) devono coincidere
e `totals.file_errors` nel JSON deve essere 0.

`clear-result-cache` **non** accetta `--no-progress`.

Config: `laravel/phpstan.neon` livello **max**, baseline vuota. **Non passare mai `--level` da CLI** e **non modificare** `phpstan.neon` — fix solo su codice PHP/test.

## Stato attuale (2026-09-23)

- `php -d memory_limit=2G ./vendor/bin/phpstan analyse Modules --memory-limit=2G` (cache `/tmp/phpstan` svuotata) → **[OK] No errors**, 9421 file, exit 0. Story [5.224](../../bmad/stories/5.224-phpstan-analyse-modules.story.md).
- `phpstan.neon` immutato. Pest skip su `10.100.200.15`.
- Drift chiuso il 2026-09-21: story [18.59](../../stories/18.59.phpstan-repo-wide-zero-2026-09-21.story.md) (23 errori su 4 file → 0).
- SSoT modulo: [phpstan-status.md](../../phpstan-status.md).

## Storico (2026-07, bump framework)

`composer run go` aveva portato `laravel/framework` v12→v13.21.1, Pest v3→v4.7.5. La maggior parte dei 90 errori di quella settimana era fallout del bump, non bug applicativi.
- Modulo `Comment` **rimosso interamente** dal codebase (nessun file `namespace Modules\Comment\...` residuo). Il bridge Pest generato conteneva ancora 5 blocchi con riferimenti stale a `Modules\Comment\Tests(\Support)?\TestCase` → 25 errori `class.notFound` (28% del totale) risolti con una semplice rigenerazione (vedi sotto).
- Coordinamento multi-agente reale osservato: un secondo agente (`agent-composer`, stesso periodo, lock su `docs/chat/handoff-phpstan-modules.md` e su singoli file test) ha corretto in parallelo AI, Activity, Notify, Tenant, UI, `Xot/tests/Unit/Actions/Blade/RegisterBladeComponentsActionTest.php`, e ha consolidato `Modules/Media/tests/` da doppioni case-sensitive (`tests/unit/...` minuscolo vs `tests/Unit/...` PascalCase) in un unico albero corretto — vedi [no-case-only-variations](../../../../../bashscripts/ai/.agents/rules/no-case-only-variations.md).

## ⚠️ Trappola: `ide-helper:models --write-mixin` NON usare in questo repo

Durante la sessione, `php artisan ide-helper:models --nowrite --write-mixin` ha **scritto comunque** nei 142 file reali dei modelli applicativi (Employee, User, Xot e altri moduli), nonostante `--nowrite`, perché `-M/--write-mixin` **implica** la scrittura del tag `@mixin IdeHelper{Model}` nei file modello reali (il flag descrive esplicitamente "Write models to [file] **and adds @mixin to each model**"). `--nowrite` e `--write-mixin` sono in conflitto logico: non combinarli mai.

```bash
# ❌ MAI — scrive nei modelli reali anche con --nowrite
php artisan ide-helper:models --nowrite --write-mixin

# ✅ Solo rigenerazione del companion file, nessun file reale toccato
php artisan ide-helper:models --nowrite
```

Verificare sempre con `git status --short Modules/*/app/Models/*.php` dopo qualunque comando `ide-helper:models` prima di procedere.

## Fix strutturali (ponytail — una guard condivisa)

### Seeders — `xotSeedModelOnce()`

~100+ errori `method.nonObject` su `Model::factory()->count(1)->create()` in entity seeders.

**SSoT:** `xotSeedModelOnce(string $modelClass)` in `Modules/Xot/helpers/Helper.php` → delega a `GetFactoryAction`.

```php
// ❌ PHPStan non risolve la catena factory su stringhe dinamiche
Article::factory()->count(1)->create();

// ✅
xotSeedModelOnce(Article::class);
```

### Pest — bridge namespace (`PestFunctionBridge.php`)

| Componente | Path | Ruolo |
|------------|------|-------|
| Stub globali | `Helper.php` | `expect`, `it`, `test`, `uses`, `beforeEach`, … |
| `PestUsesChain` | `Xot/tests/Support/PestUsesChain.php` | `uses(...)->beforeEach()` tipizzato |
| Bridge per modulo | `Xot/tests/Support/PestFunctionBridge.php` | stub `expect/test/it/describe/beforeEach/afterEach/uses/skip` per **213** namespace `Modules\{X}\Tests(\...)?` |

Il bridge è **generato meccanicamente** da uno scanner che cerca `^namespace ...;` in ogni file sotto `*/tests/*` di ogni modulo. Se un modulo viene rimosso senza rigenerare il bridge, restano blocchi stale con `@param-closure-this \Modules\{Removed}\Tests\TestCase` non risolvibile → `class.notFound` su ogni funzione stub di quel blocco.

Rigenerazione bridge (self-formatting da 2026-07-24: il generatore lancia `pint` sull'output subito dopo averlo scritto, quindi non serve un fixup manuale):

```bash
php bashscripts/tools/generate-pest-phpstan-bridge.php
```

Verifica dopo rigenerazione:

```bash
cd laravel
php -l Modules/Xot/tests/Support/PestFunctionBridge.php
./vendor/bin/pint --test Modules/Xot/tests/Support/PestFunctionBridge.php
./vendor/bin/phpstan analyse Modules/Xot/tests/Support/PestFunctionBridge.php --no-progress
```

### Factory — `HasXotFactory`

`newFactory()` annotato `@return TFactory` per risolvere la catena generica sui modelli Xot.

### Trait probe Notify

`Modules/Notify/app/Phpstan/HasContactPhpstanProbe.php` registrato in `xotPhpstanTraitProbeClasses()` (valori `::class`, non stringhe).

### Test mock User — `RelationX`

`MockUserWithTeams` (test) deve `use RelationX` se usa `HasTeams` (metodo `belongsToManyX`).

## Blocker bootstrap risolti (sessioni precedenti)

### Vendor corrotto

- `phpdocumentor/reflection-common` (`Fqsen.php` vuoto) → `composer reinstall phpdocumentor/reflection-common`

### ParseError Media

- `ConvertWidget.php`: loop `while` malformato, `$record` non qualificato → progresso solo in `onProgress`, tipi espliciti su `$remaining`/`$rate`

## Fix type-safety per modulo

| Modulo | Fix principali |
|--------|----------------|
| Xot | `Helper.php`: `count($matches) >= 3` al posto di `isset` su offset regex; bridge Pest rigenerato (Comment stale) |
| Cms | `@var view-string` su `AppLayout::$view` |
| Employee | `Admin.php` — self-mixin `@mixin IdeHelperAdmin` orfano rimosso (nessuna classe `IdeHelperAdmin` reale esiste in nessun file: `_ide_helper_models.php` è escluso da `phpstan.neon` e non definisce comunque quella classe) |
| Media | `tests/unit/**` (minuscolo, doppione) rimosso a favore di `tests/Unit/**` (PascalCase) |
| phpstan.neon | `excludePaths` include `./*/Tests/*` (Tenant ha cartella `Tests/`) |

## Regola `@property $deleter`

Il trait `Modules\Xot\Traits\Updater` dichiara `@property ProfileContract|null $deleter`. I modelli che usano il trait devono allineare il PHPDoc a `ProfileContract`, non a implementazioni modulo-specifiche.

## Ignore in phpstan.neon (intenzionali)

- `missingType.generics`, `missingType.iterableValue`
- cast `mixed` unsafe, `new static` unsafe
- deps opzionali non installate (documentate, non forzate via Composer)

## Follow-up aperto (non PHPStan, trovato durante la verifica post-fix)

`./vendor/bin/pest Modules/Xot` (intero modulo, non il solo file toccato) riporta **68 failed / 28 risky** su `HasCommonScopesTest.php` e classi limitrofe: `LogicException: The [bootIfNotBooted] method may not be called on model [Modules\Xot\Tests\Fixtures\Models\HasCommonScopesProbe] while it is being booted`. File non toccato da questa sessione (`git log` mostra un solo commit storico), quindi **preesistente**, non introdotto dal fix PHPStan. Ipotesi principale: fallout Eloquent del bump Laravel v12→v13 sul boot ricorsivo dei trait-probe model. Da investigare separatamente (task distinto, fuori scope da "phpstan analyse Modules").

## Verifica post-modifica

```bash
cd laravel
./vendor/bin/phpstan analyse Modules --no-progress
```

## Related

- [phpstan-cluster-map-and-false-friends](../concepts/phpstan-cluster-map-and-false-friends.md)
- [phpstan-pest-bridge-discipline](../concepts/phpstan-pest-bridge-discipline.md)
- [safe-functions-rule](../../../../../docs/wiki/concepts/safe-functions-rule.md)
- [llm-wiki-qmd-workflow](../../../../../docs/project/llm-wiki-qmd-workflow.md)
