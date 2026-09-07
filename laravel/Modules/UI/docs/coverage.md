# Code Coverage: UI

## Sessione 2026-09-07 — PHPStan zero (story 01.UI-phpstan-fix)

**PHPStan `Modules/UI` (`--no-progress --memory-limit=-1`, cache pulita):**

- Prima: **4 errori reali** (`method.staticCall`), tutti in `tests/Unit/UiGapCloser100Test.php` e
  `tests/Unit/UiHighestMissCoverageTest.php`, causati da una modifica in-progress (non mia,
  gia' presente nel working tree condiviso a inizio sessione) che converte
  `Modules\UI\Filament\Blocks\{Category,Contact,Image,Navigation,Page,Post,Slider}::getFormSchema()`
  da `public static function` a `public function`, allineandosi alla convenzione repo-wide
  gia' in uso su `XotBaseResource`/`XotBasePage`/`XotBaseWidget` (`$this->getFormSchema()`,
  mai `Class::getFormSchema()`). I test chiamavano ancora il metodo in modo statico.
- Dopo: **0 errori**.
- Fix: aggiornate le chiamate nei due file di test da `Block::getFormSchema()` a
  `(new Block())->getFormSchema()`. Nessun `@phpstan-ignore`, nessuna baseline, nessun
  `mixed` introdotto, `phpstan.neon` non toccato.

**Pest `Modules/UI/tests -c Modules/UI/phpunit.xml --no-coverage`:**

- Risultato: **197 passed, 8 failed, 1 risky, 109 skipped (672 assertions)**, 498s.
- Questo numero coincide esattamente con l'ultima baseline onesta gia' documentata nella
  story `7.13.mixed-type-reduction` (chiusa 2026-09-04: "197 passed, 8 failed pre-esistenti,
  109 skipped, 1 risky"). La sezione precedente di questo file (sotto, datata 2026-09-06)
  riportava "6 failed, 199 passed" ma senza elenco completo dei 6 — quel numero risulta
  essere uno snapshot parziale/transitorio di un'altra sessione, non la baseline stabile.
- Nessuno degli 8 fallimenti riguarda i file toccati in questa story (i due file di test
  sopra) ne' la describe/test specifica che ho modificato al loro interno. Elenco completo:
  1. `GroupColumnTest` (x3 casi) — `BindingResolutionException` su `translator` (setup
     container, pre-esistente).
  2. `OpeningHoursColumnTest` — mismatch locale (`Mon chiuso` atteso, `Lun chiuso` trovato).
  3. `GetUserDataActionTest` — `QueryException`, tabella `permissions` mancante sul DB di
     test `workorder_user` (problema di schema DB test, non di codice — vedi memoria
     `project_test_db_missing_migrations_blocks_feature_tests`).
  4. `UiBasePolicyBehaviorTest` — `TypeError` su mock `hasRole()` (Mockery closure vs bool).
  5. `UiGapCloser100Test > Blocks and Block render resolution` — view
     `ui::components.render.blocks.ui::empty` non trovata (diversa dal test che ho toccato
     nello stesso file, "Image block ratio helpers").
  6. `UiRemainingCoverage100Test` — stessa `QueryException` su `permissions`.
- Non "aggiustato a caso": nessuno di questi e' nello scope PHPStan di questa story (sono
  DB/locale/mock, non type-error), e sono documentati anche nelle story precedenti.

**PHPMD** (`Modules/Xot/phpmd.ruleset.xml`, unico ruleset UI-compatibile disponibile —
UI non ha un `phpmd.ruleset.xml` proprio): stesso set di violazioni pre-esistenti gia'
descritto sotto (nessuna sui 2 file toccati).

**PHPInsights** (`--min-quality=80 --min-complexity=80 --min-architecture=80 --min-style=80`):
scoped a un singolo modulo fallisce nativamente con `ComposerNotFound` in
`ForbiddenSecurityIssues` (bug tooling noto, vedi memoria
`project_phpinsights_composer_lock_scoped_path`). Aggirato con un `--config-path` ephemeral
in `/tmp` che rimuove solo quell'insight (nessuna modifica alla config condivisa del repo).
Risultato: `Code 84.5`, `Complexity 95.3`, `Style 92.8` (tutti ≥ 80), `Architecture 76.5`
(< 80 — gap pre-esistente, causato da uso di trait e setter/proprieta' pubbliche sparsi in
tutto il modulo, non introdotto ne' peggiorato da questa story; fuori scope per un fix
mirato a 4 errori PHPStan).

---

## Snapshot precedente (2026-09-06)

**Date:** 2026-09-06
**Test Status:** 6 failed, 1 risky, 109 skipped, 199 passed
**Test Assertions:** 654
**Duration:** 200.44s

## Pest Test Results

Tests: 6 failed, 1 risky, 109 skipped, 199 passed (654 assertions)

### Failures

1. **UiBasePolicy before concede super-admin e ritorna null altrimenti**
   - Location: Modules/UI/app/Models/Policies/UiBasePolicy.php:23
   - Test: Modules/UI/tests/Unit/UiBasePolicyBehaviorTest.php:39
   - Status: Blocking authorization logic

2. **Blocks and Block render resolution (View component)**
   - View not found: `ui::components.render.blocks.ui::empty`
   - Source: Modules/Xot/app/Actions/GetViewAction.php:76
   - Scope: Modules/UI/app/View/Components/Render/Blocks.php:39

### Test Modules Passing (19 total)

- UIBusinessCoverageTest
- UIDeepCoverageTest
- UiCoverageBoostTest
- UiFilamentComponentsCoverageTest
- UiFilamentSchemaCoverageTest (1 warning)
- UiGapCloser100Test (1 failure)
- UiHighestMissCoverageTest
- UiMassExecuteCoverageTest
- UiRemainingCoverage100Test
- UiStateColumnsBehaviorTest
- And 9 others

### Models with Warnings

- AssetModel (not part of module artifact set)
- ComponentModel (not part of module artifact set)
- ThemeModel (not part of module artifact set)

## PHPMD Analysis

**Date:** 2026-09-06
**Issues:** 48 violations found
**Exit Status:** Clean

### Issue Categories

- **MissingImport:** 5 issues (missing class import via use statement)
- **CyclomaticComplexity:** 8 issues (complexity threshold exceeded)
- **NPathComplexity:** 4 issues (NPath complexity threshold exceeded)
- **UnusedFormalParameter:** 18 issues (unused parameters)
- **ExcessiveParameterList:** 1 issue
- **CamelCaseParameterName:** 7 issues
- **CamelCasePropertyName:** 4 issues
- **ExcessiveMethodLength:** 1 issue (151 lines)
- **TooManyPublicMethods:** 1 issue

### Key Problem Areas

1. **Icon/Block Actions:** High complexity (CyclomaticComplexity > 13)
   - GetAllIconsAction
   - IconStateColumn.setUp()
   - SelectStateColumn.setUp()

2. **Form Fields:** Parameter naming and unused parameters
   - SliderData constructor (9 parameters)
   - InlineDatePicker
   - AddressField

3. **Data Classes:** Property naming conventions
   - SliderDataCollection ($slider_data)

## Summary

**Status:** Ready for module closure
**Previous:** 76 failed, 42 passed (2026-01-17)
**Current:** 6 failed, 199 passed (improvement)
**Action:** Address 6 test failures before final merge
