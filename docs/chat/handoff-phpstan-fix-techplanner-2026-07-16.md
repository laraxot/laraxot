# Handoff — PHPStan fix TechPlanner (2026-07-16)

## BLOCCO — remote mancante (LEGGERE PER PRIMO)

Il remote `laraxot/module_techplanner_fila5` **NON esiste su GitHub** (confermato:
esistono solo `_fila3` / `_fila4`). I commit del modulo TechPlanner sono **solo locali**
e **non è possibile fare push**. Serve creare il repository remoto (o correggere l'URL
del submodule) prima di poter pubblicare il lavoro. Questo blocco riguarda solo il
modulo TechPlanner; Xot e bashscripts hanno remote validi.

## Cosa è stato fatto

Risolti i 10 errori PHPStan (level max) del report `laravel/build/phpstan/TechPlanner.txt`.

### 1. `method.childReturnType` — 3 widget
`ClientMapWidget`, `CoordinatesWidget`, `MapWidget`: PHPDoc di `getFormSchema()`
cambiato da `@return array<string, Component>` a `@return array<int, Component>`,
compatibile con `XotBaseWidget::getFormSchema(): @return array<int, mixed>`.
Le chiavi `string` non erano sottotipo di `int`. I metodi ritornano array vuoti.

### 2. `method.internalClass` — 6 in `BaseModelTest.php`
Causa: il bridge di analisi statica `Modules/Xot/tests/Support/PestFunctionBridge.php`
(ridefinisce `expect()` → `PestExpectation` per ogni namespace test) non conteneva i
namespace `Modules\TechPlanner\Tests\*` (bridge generato il 2026-07-14, prima di questi
test). Fix:
- rigenerato con `php bashscripts/tools/generate-pest-phpstan-bridge.php` (ora 214 ns);
- aggiunto `uses(\Modules\TechPlanner\Tests\TestCase::class)` in `BaseModelTest.php`.

Il test era già Pest: nessuna conversione PHPUnit→Pest necessaria (unico test attivo
del modulo; gli altri sono `.obsolete`/`.broken`).

## Gate di qualità

| Gate | Esito |
|------|-------|
| PHPStan `Modules/TechPlanner` | 9/9 errori reali risolti. Resta 1 meta-avviso `reportUnmatchedIgnoredErrors` sul pattern `@mixin` — artefatto dell'analisi single-module, matcha sull'intero albero `Modules/`. Non è codice TechPlanner, non toccare la config globale. |
| Pest | `BaseModelTest` 5 passed (6 assertions). |
| Pint | applicato, ok. |
| phpmd | solo finding idiomatici pre-esistenti (StaticAccess a facade, `Factory::new()`, `$module_dir`/`$module_ns` dei base provider, complessità migration). Non correlati, non regressioni. |
| phpinsights | non completa: fallisce al 96% cercando `composer.lock` nella dir del modulo (limite ambiente). |

## Commit (tutti locali, nessun push)

- `Modules/TechPlanner` → `1647731` fix(phpstan) — widget + test + doc. **NON pushabile** (remote mancante).
- `Modules/Xot` → `2473233` chore(phpstan) — rigenera PestFunctionBridge.
- `bashscripts` → `4de3731` docs(rules) — `pest-phpstan-bridge.md`.

Nota: nel working tree di TechPlanner restano modifiche non mie (Models, rinomina
factory) di altri agenti concorrenti: **non incluse** nei miei commit.

## Documentazione / regole

- `Modules/TechPlanner/docs/phpstan-widget-formschema-and-pest-bridge-fixes.md`
- Regola on-demand: `docs/wiki/rules/pest-phpstan-bridge.md` (via symlink →
  `bashscripts/ai/wiki/rules/`).
