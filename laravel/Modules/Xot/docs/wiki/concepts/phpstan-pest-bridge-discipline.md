---
title: "PHPStan Pest Bridge Discipline"
type: concept
module: Xot
tags: [xot, phpstan, pest, testing, bridge]
created: 2026-06-10
updated: 2026-09-02
qmd: "Xot phpstan pest bridge discipline plugin-phpstan Mockery CompositeExpectation ExpectationDirector concrete Expectation no PestFunctionBridge"
issues:
  - "https://github.com/laraxot/module_xot_fila5/issues/28"
  - "https://github.com/laraxot/module_xot_fila5/issues/79"
discussions:
  - "https://github.com/laraxot/module_xot_fila5/discussions/29"
  - "https://github.com/laraxot/module_xot_fila5/discussions/19"
related:
  - ../../../../../../docs/wiki/rules/phpstan-pest-tests-stay-pest.md
  - ../../../../../../docs/wiki/rules/pest-phpstan-bridge.md
  - ../../../../../../docs/wiki/skills/phpstan-pest-remediation.md
---

# PHPStan Pest Bridge Discipline

Xot e' il posto giusto per pattern condivisi di test/static analysis, ma **non**
si devono stubbare le funzioni Pest nei namespace test.

## Contratto (aggiornato 2026-08-31)

- Pest resta il framework test.
- PHPStan resta governato dal solo `laravel/phpstan.neon` utente.
- **Vietato** `Modules/Xot/tests/Support/PestFunctionBridge.php` — anti-pattern:
  stub `uses|it|test → void` generano `function.void` + `method.nonObject`.
- **Obbligatorio** `pestphp/pest-plugin-phpstan` (caricato da `phpstan/extension-installer`).
- Il generatore `bashscripts/tools/generate-pest-phpstan-bridge.php` è una **guard**
  che esce con codice 1 — non rigenerare mai il bridge.
- Canon progetto: `docs/wiki/rules/pest-phpstan-bridge.md`.
- `uses(\Modules\<M>\Tests\TestCase::class)` sempre **dopo** gli `import use` nel file Pest.
- File Pest: **niente** `namespace …;` in cima (rompe il parser PHPStan su `uses()`).
- HTTP: `actingAs($user); get($url)->assertOk();` — non chainare `actingAs()->get()` (Pest tipizza `actingAs` → `TestCase` / overload confusi).
- `@var` nelle closure Pest: preferire **FQCN** anche se c’è `use` in testa al file.

## Helper XotBaseTestCase (usare nei moduli)

| Metodo | Uso |
|--------|-----|
| `mockService($class, $closure)` | Mock in Pest senza chiamare `$this->mock()` protetto |
| `expectsOnce()` / `expectsExactly(n)` | Expectation PHPUnit tipizzate per Pest |
| `expectApplicationException($class)` | Wrapper `expectException` |
| `rrmdir($dir)` | Cleanup directory in test feature (Xot `TestCase`) |

## Pattern moduli (2026-06-13)

- **Activity:** batch 7 file — `expect()` → `Assert::assert*()`; [completion-status](../../../Activity/docs/wiki/overviews/completion-status.md)
- **Fixcity:** helper `ticket()`, `authUser()`, … — [phpstan-pest-testcase-helpers](../../../Fixcity/docs/wiki/concepts/phpstan-pest-testcase-helpers.md); `PestHelper.php` tipizzato
- **Notify:** `notificationManager()` + trait doubles — [phpstan-pest-test-doubles](../../../Notify/docs/wiki/concepts/phpstan-pest-test-doubles.md)
- **Xot:** test File — no `@var TestCase $this` se la closure non usa `$this`; no `assertIsString(tempnam())`
- **Xot Blade:** `RegisterBladeComponentsActionTest` — `Assert::assertSame` sul count collection; Mockery `allows(['execute' => …])` + `@var Action&MockInterface`; no `expect()->toBe*` se PHPStan emette `method.internalClass` (vedi [PHPSTAN-BEST-PRACTICES](../PHPSTAN-BEST-PRACTICES.md) §7–8)
- **Tenant:** non ridefinire `mockService()`; non re-tipizzare `$model`/`$baseModel` se il parent ha `mixed`
- **UI:** `createStub` + `willReturn(null)` per action mock; no `andReturnNull()` Mockery

## Mockery: interfaccia minima e fluent API

Osservazione verificata il 2 settembre 2026: `shouldReceive('metodo')` può
restituire a runtime `Mockery\CompositeExpectation`, anche quando il nome è uno
solo. `ExpectationInterface` dichiara `andReturn()` ma non `with()` né
`andReturnUsing()`; tipizzare l'helper con quell'interfaccia tronca quindi la
fluent API per PHPStan.

Pattern per un helper riusabile:

1. registrare il metodo con `shouldReceive()`;
2. ottenere il relativo `ExpectationDirector` tramite il mock;
3. estrarre l'ultima `Mockery\Expectation` concreta;
4. usare guardie runtime per i casi inattesi;
5. restituire `Expectation`.

Non aggiungere `/** @var Expectation */` sul risultato composito: il commento
mentirebbe sul valore runtime e nasconderebbe esattamente il bug che il test deve
intercettare.

## Fixture che consumano trait

PHPStan analizza un trait nel contesto di ogni consumer, incluse le fixture. Una
fixture anonima con `getTableColumns(): array<string, mixed>` trasforma colonne e
layout in `mixed`, facendo fallire `clone`, `Stack::make()` e i resolver di layout.
Il consumer deve dichiarare lo stesso contratto del trait:
`Column|ColumnGroup|LayoutComponent`.

Allo stesso modo, per le relazioni Eloquent nei trait usare
`@phpstan-require-extends Model` e `$this` come modello dichiarante. Le guardie
runtime per input nullable fanno parte del comportamento, non sono espedienti per
l'analizzatore.

Hub piattaforma: [platform-completion-roadmap](../overviews/platform-completion-roadmap.md).

## Quando centralizzare in Xot

Centralizzare solo se il pattern e' usato da piu' moduli:

- helper per database assertion senza `$this` ambiguo;
- helper per factory `createOne()` e narrowing del modello (`bashscripts/tools/fix-test-factory-createone.php`);
- wrapper assertion per stringhe, array shape o class-string.

Non centralizzare fix one-shot di un singolo test Activity.
