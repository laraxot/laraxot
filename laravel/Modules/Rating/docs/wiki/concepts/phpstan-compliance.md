---
title: "Rating — Contratti PHPStan per trait Eloquent"
type: concept
module: Rating
tags: [rating, phpstan, larastan, eloquent, generics, traits, testing]
created: 2026-06-10
updated: 2026-09-02
qmd: "Rating PHPStan HasLikes HasRatingsTrait null safety MorphToMany MorphMany $this MorphPivot Expectation zero errors"
issues:
  - "https://github.com/laraxot/module_rating_fila5/issues/12"
discussions:
  - "https://github.com/laraxot/module_rating_fila5/discussions/48"
related:
  - ../troubleshooting/phpstan-generic-limitation-morphtomany.md
  - ../../../../../../docs/wiki/PHPSTAN-INDEX.md
  - ../../../../Xot/docs/wiki/phpstan-best-practices.md
---

# Rating — Contratti PHPStan per trait Eloquent

> Stato verificato sul comando canonico, senza configurazioni alternative o
> soppressioni aggiunte.

## Stato

Il 2 settembre 2026:

```text
./vendor/bin/phpstan analyse Modules
10573/10573
[OK] No errors
```

Test mirati Rating: **24 passed** su `HasLikesTraitTest` e
`HasRatingsTraitAccessorsTest`.

## `HasLikes`: null safety e relazione

- Il trait richiede un host Eloquent con `@phpstan-require-extends Model`.
- `likes()` restituisce `Collection<int, Like>`.
- `likesRelation()` restituisce `MorphMany<Like, $this>`.
- `likedBy()`, `dislikedBy()` e `isLikedBy()` gestiscono esplicitamente l'utente
  `null`; non dereferenziano più `UserContract|null`.
- Il callback `deleting` restringe realmente l'host prima di invocare relazione e
  invalidazione della cache Eloquent.

## `HasRatingsTrait`: generics invarianti

Il modello dichiarante delle relazioni è `$this`, non `static`:

```php
/** @return MorphToMany<Rating, $this, MorphPivot, 'pivot'> */
public function ratings(): MorphToMany;
```

Questo coincide con il tipo restituito da Eloquent e non forza covarianza dove la
relazione è invariante. Il trait:

- risolve una `class-string<Rating>` e la verifica a runtime;
- usa `morphToManyX()` per rispettare il pivot Laraxot;
- tipizza `Builder<static>`, `HasMany<Rating, $this>` e le collection;
- avvia `syncRatingsWhere()` da una query Rating tipizzata, non da `app(): mixed`;
- usa la relazione reale `linkedTo` al posto del nome legacy inesistente `post`;
- restituisce `0.0` quando non esiste alcuna media, come da contratto dei test.

## Regola per le fixture

Le fixture non sono scaffolding senza tipo: PHPStan rianalizza il trait nel loro
contesto. Override e proprietà di relazione devono mantenere gli stessi generics
del modello reale.

## Gate

```bash
cd laravel
./vendor/bin/phpstan analyse Modules/Rating
./vendor/bin/pest Modules/Rating/tests/Unit/HasLikesTraitTest.php \
  Modules/Rating/tests/Unit/HasRatingsTraitAccessorsTest.php
./vendor/bin/phpstan analyse Modules
```
