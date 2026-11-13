---
title: "phpstan article tests"
type: story
status: done
scope: [module:Blog, tests]
qmd: "phpstan article tests ArticleWorkflowTest ArticleTest published_at factory createOne"
updated: 2026-10-15
---

# Story — PHPStan test articolo (workflow + model)

## Scopo

I test `ArticleWorkflowTest` e `ArticleTest` documentano il ciclo di vita degli articoli Blog: bozza → modifica → pubblicazione (`published_at`) → visibilità via scope `published()`, CRUD e query. Non esiste la colonna/proprietà booleana `published` sul modello `Article`.

## Famiglie PHPStan risolte (66 → 0 sui due file)

| Identificatore | Conteggio | Causa | Fix |
|----------------|-----------|-------|-----|
| `method.nonObject` | 28 | `Model::factory()` inferito `mixed` | `ArticleFactory::new()->createOne()` / `count()->create()`; `UserFactory::new()->createOne()` |
| `property.nonObject` | 22 | `fresh()` nullable; accesso su `Article\|null` | Helper `freshArticle()` con `assertInstanceOf` |
| `assign.propertyType` | 1 | `User::factory()->create()` union Collection\|User | `createOne()` |
| `property.notFound` | 1 | `$published` inesistente | Allineamento a `published_at` + scope `published()` |
| `variable.unused` | 3 | Variabili solo per side-effect DB | Uso esplicito di `id` / `$article3` nelle asserzioni |
| `method.alreadyNarrowedType` | 1 | `assertTrue(true)` nel ramo catch | `$this->fail()` se manca l’eccezione attesa |
| `return.type` | 2 | `create()` restituisce union | `createOne()` per singolo modello |
| `return.tooWideBool` | 2 | PHPDoc `published_at` non nullable vs draft | Asserzioni via query `Article::published()` |

## Verifica

```bash
cd laravel && ./vendor/bin/phpstan analyse \
  Modules/Blog/tests/Feature/ArticleWorkflowTest.php \
  Modules/Blog/tests/Unit/Models/ArticleTest.php \
  --no-progress
```

Esito atteso: `[OK] No errors`.

## Collegamenti

- [Convenzione naming test](../testing/test-naming-convention.md)
- [Scope `published()`](../../app/Models/Concerns/ArticleQueryScopes.php)
- Claim swarm: [phpstan-swarm-modules](../../../../docs/chat/phpstan-swarm-modules.md)
