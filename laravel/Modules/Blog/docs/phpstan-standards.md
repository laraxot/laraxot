---
title: Blog PHPStan Standards
type: standards
status: stable
source_of_truth: true
tags: [phpstan, blog, level-10, generics, collection]
updated: 2026-10-15
qmd: "blog   phpstan standards (source of truth) blog"
---

# Blog — PHPStan Standards (Source of Truth)

Scopo: mantenere 0 errori PHPStan livello max su `Modules/Blog`.

## Regole dalla sorgente
- `Article` usa `BaseModel` con `@property`, `@method` PHPDoc completi.
- `BelongsTo` generics: `static` non risolto da PHPStan → usare `Model` se necessario (vedi User/Xot).
- Nessuna directory `Tests/` maiuscolo; test in `tests/` con FQCN.
- Documentazione aggiornata in `docs/phpstan-standards.md`; non timestamp nei nomi.
- Fix alla sorgente: PHPDoc, import, generics — mai il sintomo solo.
