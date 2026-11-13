---
created: 2026-09-26
updated: 2026-09-26
issues: []
discussions: []
title: PHPStan + Pest — pattern test modulo Comment
type: concept
tags: [phpstan, pest, testing, comment]
qmd:
  index: true
created_at: 2026-06-10
updated_at: 2026-06-10
status: draft
---

# PHPStan + Pest — pattern test

## Gate

```bash
cd laravel && ./vendor/bin/phpstan analyse Modules/Comment
```

Stato: **0 errori** (`app/` + `tests/`).

## Pattern (no ignore, no baseline)

| Problema | Soluzione |
|----------|-----------|
| `expect()->toBe*()` → `method.internalClass` | `PHPUnit\Framework\Assert::assert*()` |
| `Mockery` su `CanComment` → `argument.type` | `Tests\Support\ParityCommentatorStub` |
| `$model->relation` su nullable → `property.nonObject` | `$m = $model->fresh(); Assert::assertInstanceOf(...)` |
| `Model::make()` → `larastan.noModelMake` | `new Model` |
| `User` vs `Comment\Models\Contracts\CanComment` | testare `User\Contracts\CanComment`; stub per alias Comment |
| `Commentable::comments()` vs `HasComments` → `return.type` / `method.childReturnType` | contratto e trait `@return MorphMany<Comment, Model>` + `@var` sul valore di ritorno di `morphMany()` |
| `(string) $subscriber->getKey()` con `getKey(): mixed` → `cast.string` | `subscriberUniqueKey()` con `match` su null/scalar/object |

## Stub

`tests/Support/ParityCommentatorStub.php` — `CanComment` tipizzato per unit senza DB user.

## Vietato

- `ignoreErrors` globali in `phpstan.neon` per silenziare Pest/Mockery
- `@phpstan-ignore` su test

Canon: [phpstan-module-scoped-analysis](../rules/phpstan-module-scoped-analysis.md)
