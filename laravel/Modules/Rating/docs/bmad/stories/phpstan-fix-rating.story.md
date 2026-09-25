---
id: Rating/phpstan-fix-rating
title: "PHPStan level max — RatingsHostStub::$forcedHasMany union type"
status: done
module: Rating
priority: P2
phase: fix
created: 2026-10-05
updated: 2026-10-05
qmd: "Rating PHPStan forcedHasMany HasMany union MorphPivot Rating fixture stub"
related:
  - 18.59-ratings-hoststub-getclassname-harness.story.md
  - ../stories/18.56.ratings-by-id-accessor.story.md
---

# Story Rating/phpstan-fix-rating

## Finding

`phpstan analyse Modules/Rating` (level max):

```
tests/Unit/HasRatingsTraitAccessorsTest.php:161
Property RatingsHostStub::$forcedHasMany (HasMany<MorphPivot, RatingsHostStub>|null)
does not accept HasMany<Rating, RatingsHostStub>&Mockery\MockInterface.
```

## Analisi

La property `$forcedHasMany` del fixture `tests/Fixtures/RatingsHostStub.php`
serve due scopi distinti:

- `ratingMorphs()` la legge come `HasMany<MorphPivot, static>` (righe pivot);
- `hasMany(Rating::class)`/`ratingObjectives()` la leggono come
  `HasMany<Rating, static>` (catalogo ratings — il caso del test a riga 161,
  mock Mockery annotato `HasMany<Rating, RatingsHostStub>`).

Il daemon composer-auto aveva ristretto il `@var` a `HasMany<MorphPivot, static>|null`
aggiungendo l'override `ratingMorphs()`: corretto per un uso, troppo stretto
per l'altro (i generics PHPStan sono invarianti, `HasMany<Rating>` non e'
sottotipo di `HasMany<MorphPivot>`).

## Fix

`tests/Fixtures/RatingsHostStub.php` — `@var` allargato a union che copre
entrambi gli usi reali, con nota che spiega chi legge cosa:

```php
/** @var HasMany<MorphPivot, static>|HasMany<Rating, static>|null */
public ?HasMany $forcedHasMany = null;
```

Nessun `@phpstan-ignore` (vincolo del task).

## Gate

- `php -l` sul file: OK.
- `cd laravel && php -d memory_limit=2G ./vendor/bin/phpstan analyse Modules/Rating
  --error-format=raw --no-progress` → **exit 0, 0 errori** (output raw vuoto).
- `./vendor/bin/pest Modules/Rating/tests/Unit/HasRatingsTraitAccessorsTest.php`
  → **12 passed, 4 failed (30 assertions)** — i 4 failure sono **preesistenti e
  documentati** (story 18.56, sezione "Fuori scope": stesso
  `RuntimeException: Unable to resolve caller object for getClassName()` da
  `XotBaseModel.php:72`, meccanismo backtrace fragile tracciato in 18.55/18.59).
  Il fix e' docblock-only → zero impatto runtime, conteggio invariato.
