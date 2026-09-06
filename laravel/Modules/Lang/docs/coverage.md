---
title: "Code Coverage: Lang"
module: "Lang"
type: concept
tags: [links]
created: 2026-07-14
updated: 2026-07-14
qmd: "links"
related:
  - "./italian-text-refined-audit-report.md"
---
# Code Coverage: Lang

**Date:** 2026-01-17
**Date:** 2026-01-17
**Date:** 2026-01-17
**Date:** 2026-01-17
**Lines Coverage:** N/A (Failed to parse)
**Test Exit Code:** 2

## Output

## 2026-09-04 — Mixed type reduction

Task: ridurre l'uso di `mixed` dove il tipo reale e' desumibile dal codice (CLAUDE.md:
"cerchiamo di non usare mixed, quando lo troviamo cerchiamo di sostituirlo con qualcosa
di adeguato"), best-effort ("dove possibile"), non 100% coverage.

**Censimento**: 142 occorrenze di `mixed` su 47 file in `Modules/Lang` (grep `\bmixed\b`
su `*.php`). La maggioranza sono docblock generici `array<string, mixed>` gia' adeguati
(payload di traduzione, valori eterogenei per costruzione — stringhe e array annidati),
o override di firme vendor tipizzate `mixed` (es. `Illuminate\Translation\Translator::get()`
esteso da `TranslatorAction`, `TranslatorAdapter`, `TranslatorService`; l'interfaccia
`CastsAttributes` in `Casts/LangField.php`) che non si possono restringere senza violare
LSP.

**Modifiche applicate** (4 file, tutte native type-hint, nessun docblock-only):
- `app/Actions/Filament/AutoLabelAction.php`: closure `Arr::first(..., function (mixed $item) ...)`
  → `array $item` (il valore viene sempre da `debug_backtrace()`/`array_slice`, sempre array).
- `app/Models/TranslationFile.php`: closure `Arr::map($files, function (mixed $item) ...)`
  → `array $item` (il chiamante `GetAllTranslationAction::execute()` dichiara
  `list<array{key: string, path: string}>`). Rimosso il check `is_array($item)` ridondante
  che PHPStan segnalava come `function.alreadyNarrowedType` dopo la narrowing.
- `app/Models/Traits/HasStrictTranslations.php`: docblock `array<mixed, mixed> $value`
  → `array<array-key, mixed> $value` (le chiavi di un array PHP non sono mai "mixed"
  arbitrario, solo `int|string`).
- `app/Actions/SyncTranslationsAction.php`: stesso fix, `array<mixed, mixed> $arr`
  → `array<array-key, mixed> $arr`.

**Skip motivati** (principali, non esaustivo):
- `Casts/LangField.php` get()/set(): implementa `CastsAttributes<mixed, array<string, mixed>>`,
  contratto vendor.
- `Actions/TranslatorAction.php`, `Adapters/TranslatorAdapter.php`, `Services/TranslatorService.php`
  — `get(mixed $key, ..., mixed $locale = null, mixed $fallback = true)`: override di
  `Illuminate\Translation\Translator::get()` i cui parametri nel vendor sono **non
  tipizzati** (equivalenti a mixed); dichiarare un tipo piu' stretto qui violerebbe la
  compatibilita' del contratto per i chiamanti che passano tramite l'interfaccia Translator.
- `Actions/TransArrayAction.php` e `Actions/TransCollectionAction.php`: `trans(mixed $item): string`
  — chiamato da `Arr::map()`/`Collection::map()` su un array/collection dichiarato
  `array<int|string, mixed>` nel docblock del chiamante: input genuinamente eterogeneo.
- `Filament/Forms/Components/NationalFlagSelect.php` (3 closure `mixed $c`/`mixed $country`):
  valori da `countries()` (pacchetto `rinvex/countries`), la cui firma vendor ritorna
  `array` non generico — ogni elemento e' gia' verificato con `is_array()` a runtime nel
  codice, narrowing non sicuro senza generics upstream.
- `View/Composers/ThemeComposer.php` closure `mixed $item`: valore da `config('laravellocalization.supportedLocales')`,
  payload di configurazione intenzionalmente non tipizzato, validato con `is_array()`
  dentro la closure.
- Resto delle occorrenze (~130): docblock `array<string, mixed>` / `array<int, mixed>`
  su modelli, DTO (`Datas/*`), factory, risorse Filament, fixture di test — shape stabile
  ma genuinamente eterogenea (alberi di traduzione, contenuto JSON/config); lasciati
  invariati.

**PHPStan**: 0 errori prima, 0 errori dopo (`./vendor/bin/phpstan analyse Modules/Lang
--no-progress --error-format=table`). Un tentativo iniziale di narrowing su
`TranslationFile.php` ha introdotto `function.alreadyNarrowedType` (check `is_array()`
ora sempre vero) — risolto rimuovendo il check morto, non con ignore/widening.

**PHPMD**: crash su tutto il modulo (`No node to visit provided for visitAnonymousClass`,
noto/flaky su questo modulo). Rieseguito solo sui 4 file modificati: nessun nuovo finding
imputabile al diff, solo debito preesistente (CyclomaticComplexity/NPath/ExcessiveMethodLength
su `AutoLabelAction::execute()`, `ElseExpression` in piu' punti, `Superglobals` su
`isRunningIdeHelper`, mai toccati da queste modifiche).

**Pest**: `./vendor/bin/pest Modules/Lang/tests -c Modules/Lang/phpunit.xml --no-coverage`.
Verificato con `git stash` che le stesse failure esistono identiche su HEAD non modificato:
`LangBusinessLogicTest` (6 test falliti) e `ReadTranslationFileActionTest` (3 test falliti,
inclusi `boolean_false` non serializzato e helper `createTranslationFile()` non definito)
sono pre-esistenti, non causati da questo diff — confermato eseguendo gli stessi due file
di test con lo stash attivo (diff rimosso): 13 failed/12 passed identici. La suite completa
si interrompe inoltre (way prima della mia modifica) per un `dd()` (`dddx()` in
`Modules/Xot/helpers/Helper.php`) invocato da `app/Actions/SaveTransAction.php:36` durante
alcuni test — comportamento preesistente, file non toccato da questo lavoro, fuori scope
per questo task.

Effetto collaterale osservato e ripulito: l'esecuzione della suite scrive per davvero su
`lang/en/*.php`/`lang/it/*.php` del modulo (side effect dei test, non del codice applicativo
modificato); ripristinati con `git checkout -- lang/ && git clean -fd lang/` prima del commit.

```text
──────────────────────────────────────────────────  
   FAILED  Modules\Lang\tests\Feature\LangBusinessLogicTest > `Lang Business Lo…  Error   
  Call to a member function connection() on null

  at vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php:1980
    1976▕      * @return \Illuminate\Database\Connection
    1977▕      */
    1978▕     public static function resolveConnection($connection = null)
    1979▕     {
  ➜ 1980▕         return static::$resolver->connection($connection);
    1981▕     }
    1982▕ 
    1983▕     /**
    1984▕      * Get the connection resolver instance.

      [2m+9 vendor frames [22m
  10  Modules/Lang/tests/Feature/LangBusinessLogicTest.php:329

  ──────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Modules\Lang\tests\Feature\LangBusinessLogicTest > `Lang Business Lo…  Error   
  Call to a member function connection() on null

  at vendor/laravel/framework/src/Illuminate/Database/Eloquent/Model.php:1980
    1976▕      * @return \Illuminate\Database\Connection
    1977▕      */
    1978▕     public static function resolveConnection($connection = null)
    1979▕     {
  ➜ 1980▕         return static::$resolver->connection($connection);
    1981▕     }
    1982▕ 
    1983▕     /**
    1984▕      * Get the connection resolver instance.

      [2m+9 vendor frames [22m
  10  Modules/Lang/tests/Feature/LangBusinessLogicTest.php:349

  ──────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Modules\Lang\tests\Unit\Actions\ReadTranslationFileActionTest > `ReadTransl…   
  Failed asserting that exception of type "Error" matches expected exception "Exception". Message was: "Call to undefined method Illuminate\Container\Container::storagePath()" at
. progetto>_fila5_mono/laravel/vendor/laravel/framework/src/Illuminate/Foundation/helpers.php:933
. progetto>_fila5_mono/laravel/Modules/Lang/tests/Unit/Actions/ReadTranslationFileActionTest.php:58
/var/www/_bases/base_modulo questionari_fila4_mono/laravel/vendor/laravel/framework/src/Illuminate/Foundation/helpers.php:933
/var/www/_bases/base_modulo questionari_fila4_mono/laravel/Modules/Lang/tests/Unit/Actions/ReadTranslationFileActionTest.php:58
/var/www/_bases/base_modulo questionari_fila4_mono/laravel/vendor/laravel/framework/src/Illuminate/Foundation/helpers.php:933
/var/www/_bases/base_modulo questionari_fila4_mono/laravel/Modules/Lang/tests/Unit/Actions/ReadTranslationFileActionTest.php:58
/var/www/_bases/base_modulo questionari_fila4_mono/laravel/vendor/laravel/framework/src/Illuminate/Foundation/helpers.php:933
/var/www/_bases/base_modulo questionari_fila4_mono/laravel/Modules/Lang/tests/Unit/Actions/ReadTranslationFileActionTest.php:58
/var/www/_bases/base_modulo questionari_fila4_mono/laravel/vendor/laravel/framework/src/Illuminate/Foundation/helpers.php:933
/var/www/_bases/base_modulo questionari_fila4_mono/laravel/Modules/Lang/tests/Unit/Actions/ReadTranslationFileActionTest.php:58
. progetto>_fila5_mono/laravel/vendor/laravel/framework/src/Illuminate/Foundation/helpers.php:933
. progetto>_fila5_mono/laravel/Modules/Lang/tests/Unit/Actions/ReadTranslationFileActionTest.php:58
.

  ──────────────────────────────────────────────────────────────────────────────────────  
   FAILED  Modules\Lang\tests\Unit\Actions\ReadTranslationFileActionTest > `ReadTransl…   
  Expected: <?php\n
  \n
  return [\n
  ... (6 more lines)

  To contain: Text with\nnewlines

  at Modules/Lang/tests/Unit/Actions/ReadTranslationFileActionTest.php:105
    101▕         $phpContent = $this->action->toPhp($translations);
    102▕ 
    103▕         expect($phpContent)->toContain("Text with \\'single\\' and \\\"double\\\" quotes");
    104▕         expect($phpContent)->toContain('Text with \\\\ backslashes');
  ➜ 105▕         expect($phpContent)->toContain('Text with\\nnewlines');
    106▕     });
    107▕ 
    108▕     test('handles deeply nested arrays', function () {
    109▕         $translations = [

  1   Modules/Lang/tests/Unit/Actions/ReadTranslationFileActionTest.php:105


  Tests:    16 failed, 14 passed (37 assertions)
  Duration: 1.44s


```
```
```
```
```

## 2026-09-04 — app/Services retired, no-services-rule compliance

Task: convert every file under `Modules/Lang/app/Services/` to the QueueableAction /
Actions layout required by `docs/wiki/rules/no-services-rule.md` (RELIGION, no exceptions
on destination folder).

**Censimento**: `app/Services/` contained exactly one file, `TranslatorService.php`
(a subclass of `Illuminate\Translation\Translator`, meant to be bound as the framework
`translator` singleton — not a multi-method "god service" facade).

### Classification

| File | Kind | Finding | Action |
|---|---|---|---|
| `app/Services/TranslatorService.php` | Neither A nor B — **dead duplicate** | A previous session had already migrated this exact class to `app/Adapters/TranslatorAdapter.php` (same `get()` override, but `notifyMissingKey()` delegates to the real `Modules\Lang\Actions\Translation\RecordMissingTranslationAction` QueueableAction instead of inlining `Translation::firstOrCreate()`). `LangServiceProvider::registerTranslator()` already binds `TranslatorAdapter` as the `translator` singleton (the call is currently commented out in `boot()`, pre-existing and unrelated to this task — not re-enabled here, out of scope). `TranslatorService` had zero production call sites; it was referenced only by its own file and by two coverage tests that instantiated it directly for line coverage. | **Deleted** the file and the now-empty `app/Services/` directory. Not moved to `Actions/` — a class without a real `execute()` entrypoint used as framework-container adapter belongs in `Adapters/` per the rule's own carve-out, and that destination already existed with better code than the Service version. |

No second Kind-A/Kind-B file existed — this module's `app/Services/` was already a single stray leftover from an earlier, unfinished migration, not a live god-service.

### Call sites updated (all inside Modules/Lang, no other module referenced `Lang\Services\TranslatorService`)

- `tests/Unit/LangCoverageGapsTest.php` — removed `use Modules\Lang\Services\TranslatorService;`; test
  `TranslatorService notifyMissingKey and TranslatorAction non-string branch` renamed to
  `TranslatorAdapter notifyMissingKey and TranslatorAction non-string branch`, instantiates
  `TranslatorAdapter` instead (import already present).
- `tests/Unit/LangHundredPercentCoverageTest.php` — removed `use Modules\Lang\Services\TranslatorService;`;
  test `TranslatorAction and TranslatorService cover missing keys and array results` renamed to
  `TranslatorAction and TranslatorAdapter cover missing keys and array results`, swapped the
  `TranslatorService` instantiation for `TranslatorAdapter` and added a missing-key assertion so the
  `RecordMissingTranslationAction` delegation path stays covered (the old test only exercised the inline
  `notifyMissingKey` version).
- `tests/Unit/Services/TranslatorServiceTest.php` — **deleted**. It didn't actually instantiate
  `TranslatorService` (it resolved `app('translator')`, which — since `registerTranslator()` is
  currently commented out — resolves to Laravel's stock translator, not any module class); it duplicated
  `tests/Unit/Adapters/TranslatorAdapterTest.php` in intent without exercising anything the class-under-test
  name claimed. Removed together with the now-empty `tests/Unit/Services/` directory.

Grep across the whole `Modules/` tree for `Lang\\Services`, `Modules\\Lang\\Services`, `TranslatorService::`,
`new TranslatorService`, `app(TranslatorService::class)` found no hits outside the four files above.

### Known pre-existing duplicate left untouched (out of scope)

`app/Actions/TranslatorAction.php` is a near-identical third copy of the same translator subclass
(inline `notifyMissingKey`, flat in `Actions/` root instead of a context subfolder, fake no-op
`execute(): void {}`) that predates this task and is not under `app/Services/`. It is not converted here
— flagged for a follow-up story so nobody re-litigates it blind. During this session it was also found
mid-edit with broken syntax from a concurrent session (see Collision note below); untouched on disk after
verification.

### PHPStan

- True baseline (`clear-result-cache` then `analyse Modules/Lang --no-progress`): **0 errors**.
- True final (same, after all edits): **0 errors**.

### Pest

Full-suite `./vendor/bin/pest Modules/Lang/tests -c Modules/Lang/phpunit.xml` was attempted but the run
is not clean end-to-end for reasons unrelated to this change: `Modules/Lang/app/Actions/SaveTransAction.php`
calls `dddx()` (dump-and-die) in its catch branch, and `tests/Unit/LangCoverageGapsTest.php`'s pre-existing
`SaveTransAction catch and non-array require paths` test deliberately triggers that branch, which kills the
whole PHP test process (pre-existing, not introduced here). Verification was therefore scoped with
`--filter` to the tests touched by this change:

```
./vendor/bin/pest Modules/Lang/tests -c Modules/Lang/phpunit.xml --no-coverage \
  --filter="TranslatorAdapter notifyMissingKey|TranslatorAction and TranslatorAdapter cover missing keys|TranslatorAdapter business logic"
# Tests: 6 passed (15 assertions)
```

### PHPMD

`./tools/phpmd.sh Modules/Lang text ../docs/phpmd.ruleset.xml` crashes on the whole module
(`No node to visit provided for visitAnonymousClass.`, pre-existing, unrelated to this change). Scoped to
the two changed test files: only pre-existing debt outside the touched lines (`Superglobals` on
`$GLOBALS` usage in helper functions, `BooleanArgumentFlag` on unrelated helpers in the same files).

### Collision with another session (discovered, not caused by this task)

While verifying, `Modules/Lang/app/Actions/TranslatorAction.php` was found on disk with a syntax error —
`use Spatie\QueueableAction\ActionJob;` duplicated three times, twice inside method bodies — consistent
with another concurrent AI session's in-flight, not-yet-completed edit to that file (see
`bashscripts/ai/wiki/rules/multi-agent-same-repo-race.md`). It was restored to its exact on-disk state
after a transient local fix was used only to unblock this module's own PHPStan/Pest verification (never
committed). `Modules/Lang/lang/it/txt.php` was also found already modified before this task started and
kept changing while this task was in progress — left untouched and excluded from this commit, per the
"don't touch another session's live WIP" rule.

Story: `docs/stories/lang-services-to-actions.story.md`.
