# Cms Module Test Coverage

## Overview
This module has comprehensive test coverage with various test types implemented.

## Test Results
- **Tests Passed**: 0
- **Assertions**: 0
- **Test Types**: Unit, Feature, Integration tests

## Coverage Statistics
- **Files**: 0
- **Lines of Code**: 0
- **Classes**: 0
- **Methods**: 0
- **Coverage Rate**: 0%

## Test Categories
- Unit Tests
- Feature Tests
- Integration Tests

## Status
All tests are passing and coverage is being maintained.

## 2026-09-04 — Riduzione uso di `mixed`

Best-effort (non 100%) di sostituzione di `mixed` con tipi piu' specifici,
solo sui type-hint nativi (parametri/return type reali, non docblock
`@property`/`@method` auto-generati da ide-helper che rispecchiano le firme
del framework).

**Modificati (7 file):**
- `app/Providers/RouteServiceProvider.php` — `fn (mixed $item)` -> `fn (int|string $item)` (item da `array_keys()` su `config('morph_map')`).
- `app/Models/Page.php` — nessuna modifica: l'unico native-mixed reale (`getMiddlewareBySlug`, riga 462) filtra un campo JSON `array<array-key, mixed>|null`, genuinamente polimorfico, lasciato cosi'. Le ~50 occorrenze restanti sono `@property`/`@method static` auto-generati da ide-helper che rispecchiano `Illuminate\Database\Eloquent\Builder` — non toccati.
- `tests/TestCase.php` — `getPackageProviders(mixed $app)` -> `getPackageProviders(Application $app)`, allineato alla firma gia' ristretta nel parent `Modules\Xot\tests\XotBaseTestCase`.
- `tests/TestHelper.php` — `map(fn (mixed $item): ?string => $item->getUrl())` -> tipizzato `Filament\Navigation\NavigationItem` (ritorno certo di `GetModulesNavigationItems::execute()`). Lasciata `mixed` una seconda occorrenza (`getUserNavigationItemUrlRoles`, mappa su `getRoleNames()`): il codice ha gia' un guard `!is_string($item)` a runtime e narrowing a `string` avrebbe reso quel guard un controllo ridondante segnalabile da PHPStan — non abbastanza sicuro da toccare senza verifica piu' approfondita del tipo di colonna via Larastan.
- `tests/Feature/FilamentBuilderBlocksTest.php` — closure su `GetAllBlocksAction::execute()` (`DataCollection<int, ComponentFileData>`) tipizzata `ComponentFileData` invece di `mixed`; rimosso il guard `method_exists($block, 'toArray')` ora ridondante.
- `tests/Feature/HomepageFilamentBlocksArchitectureTest.php` — stesso pattern, 2 occorrenze tipizzate `ComponentFileData`.
- `tests/Feature/CmsContentManagementTest.php` — `fn (mixed $i)` su `array_map(..., range(1, 12))` -> `fn (int $i)`; `fn (mixed $page)` su `Page::query()->get()->filter()` -> `fn (Page $page)`.

**Lasciati `mixed` con motivo (principali):**
- `app/Models/{Page,Attachment,Menu,PageContent,Section}.php` — decine di `@method static ... mixed ...` / `@property array<array-key, mixed>` auto-generati (ide-helper) che rispecchiano Eloquent Builder e colonne JSON traducibili: narrowing romperebbe la corrispondenza con la firma reale del framework o non e' verificabile senza leggere lo schema.
- `app/Http/Volt/Password/ResetComponent.php:37` — item di un array di traduzione (`trans($response)` quando array), genuinamente eterogeneo.
- `tests/Feature/HeaderNavJsonTest.php:59` — item di JSON decodificato da file (`File::json()`), payload polimorfico per definizione.
- `tests/Unit/Http/Middleware/PageSlugMiddlewareTest.php` — helper di reflection generici (`invokeProtected`/`setProtected`), intenzionalmente polimorfici.
- `tests/Unit/Http/View/Composers/XotComposerTest.php:55` — callback mock PHPUnit che riceve valori di tipo diverso a seconda della chiamata (array poi string), genuinamente eterogeneo.
- `app/docs/config.php:81` — commento a singolo asterisco (`/* @param ... */`, non `/** */`), non e' nemmeno una docblock analizzabile da PHPStan; file di esempio in `app/docs/`, non wired come config reale del modulo.

**PHPStan**: 0 errori prima -> 0 errori dopo (`./vendor/bin/phpstan analyse Modules/Cms --no-progress`).

**PHPMD** (`tools/phpmd.sh`): crash su tutto il modulo (`No node to visit provided for visitAnonymousClass`, noto/flaky, non causato da questo diff). Rieseguito sui soli file modificati: solo debito pre-esistente non correlato (naming convention `CamelCaseVariableName`/`CamelCasePropertyName`, `UnusedFormalParameter` su override di metodi contratto framework).

**Pest**: nessun `Modules/Cms/phpunit.xml` presente nel modulo — comando canonico non eseguibile per questo modulo (nessuna suite dedicata da lanciare con `-c`).

Story: `docs/stories/cms-mixed-type-reduction.story.md`.
## 2026-09-04 — instanceof contro classe concreta sbagliata (test)

`tests/Feature/Auth/LoginTest.php` verificava
`assert($authenticatedUser instanceof \Modules\Quaeris\Models\User)` —
stesso anti-pattern gia' trovato in produzione in
`Modules\User\app\View\Pages\ProfileEditVoltComponent.php` (story
`user-profile-volt-instanceof-wrong-user-class.md`): funziona per caso
solo perche' `config('auth.providers.users.model')` punta oggi a quella
classe specifica. Corretto in `instanceof
Modules\Xot\Contracts\UserContract`. **Non verificato a runtime**: la
suite Feature di Cms non e' eseguibile in questo checkout
(`Themes/TwentyOne` mancante, blocca la risoluzione Folio prima ancora
di arrivare all'asserzione) — vedi
`Modules/Quaeris/docs/stories/quaeris-user-profile-hardcoded-to-contract.story.md`
per il dettaglio. `phpstan analyse Modules/Cms`: 0 errori.
