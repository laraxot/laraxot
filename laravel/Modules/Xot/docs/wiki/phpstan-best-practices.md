---
title: "PHPStan Best Practices — Xot"
type: guideline
module: Xot
tags: [phpstan, larastan, pest, mockery, eloquent, static-analysis]
created: 2026-06-13
updated: 2026-09-02
qmd: "Xot PHPStan best practices Mockery CompositeExpectation ExpectationDirector Eloquent trait relations fixtures architecture tests no ignore"
issues:
  - "https://github.com/laraxot/module_xot_fila5/issues/79"
discussions:
  - "https://github.com/laraxot/module_xot_fila5/discussions/19"
related:
  - ./concepts/phpstan-pest-bridge-discipline.md
  - ../../../../../docs/wiki/PHPSTAN-INDEX.md
  - ../../../../../docs/wiki/rules/phpstan-neon-immutable.md
  - ./rules/module-testcase-xotbase-hierarchy.md
---

# PHPStan Best Practices — Xot

> Contratti verificati per mantenere `analyse Modules` verde senza indebolire
> l'analisi statica.

## Comando canonico

Da `laravel/`:

```bash
./vendor/bin/phpstan analyse Modules
```

Il solo file di configurazione autorizzato è `laravel/phpstan.neon`. Gli agenti
non aggiungono level, baseline, ignore o configurazioni alternative.

## Pest e Mockery

- Pest resta Pest; per assertion non fluenti usare `PHPUnit\Framework\Assert`.
- Il plugin ufficiale `pestphp/pest-plugin-phpstan` tipizza le closure e sostituisce
  i vecchi bridge manuali.
- `Mockery\ExpectationInterface` espone solo l'API minima e non dichiara
  `with()`/`andReturnUsing()`.
- Mockery può restituire `CompositeExpectation` anche con un solo metodo. Un
  helper deve recuperare l'`Expectation` concreta dal suo
  `ExpectationDirector`, verificare il risultato a runtime e restituire quel tipo.
- Non usare un `@var Expectation` per contraddire il valore restituito: maschera
  una differenza runtime reale.

## Generics Eloquent nei trait

```php
/**
 * @phpstan-require-extends Model
 */
trait HasRatings
{
    /** @return MorphToMany<Rating, $this, MorphPivot, 'pivot'> */
    public function ratings(): MorphToMany
    {
        return $this->morphToManyX(Rating::class, 'model');
    }
}
```

Regole:

- modello dichiarante = `$this`; `static` in una relazione invariante può produrre
  un contratto diverso da quello restituito da Eloquent;
- il trait dichiara `@phpstan-require-extends Model` se usa metodi Eloquent;
- `class-string`, pivot, chiavi e valori delle collection restano specifici;
- una query dinamica parte da una classe verificata o da `Model::newQuery()`, non
  da un valore `mixed` restituito dal container;
- una relazione eager-loaded deve esistere davvero sul modello (`linkedTo`, non
  un nome legacy come `post`).

## Fixture e test di architettura

- Le fixture che consumano trait vengono analizzate come classi reali. Le loro
  firme devono dichiarare esattamente `Column|ColumnGroup|LayoutComponent`,
  `MorphMany<Like, $this>` e gli altri tipi di dominio.
- Se un test architetturale invoca un metodo base documentato ma assente, aggiungere
  l'API nella base e testarne la risoluzione. Non annotare il call site per farlo
  sparire.
- Dopo un fix su helper o trait, eseguire sia i test del consumer sia PHPStan nel
  contesto della fixture: è lì che emergono gli errori generici.

## Narrowing accettato

Usare guardie runtime che migliorano anche il comportamento:

```php
$file = tempnam(sys_get_temp_dir(), 'xot-');
if ($file === false) {
    throw new RuntimeException('Unable to create temporary file.');
}
```

Non usare `assert()`, ignore o `@var` inline come sostituti della guardia.

## Chiusura

1. PHPStan sul modulo owner.
2. Pest sui test coinvolti.
3. `./vendor/bin/phpstan analyse Modules` come gate finale.
4. Write-back nella wiki owner e nella story BMAD.

Router globale: [PHPSTAN-INDEX](../../../../../docs/wiki/PHPSTAN-INDEX.md).
