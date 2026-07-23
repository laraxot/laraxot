---
title: "Dipendenza da User module"
type: concept
tags: [comment, user, dependency, module]
created: 2026-06-29
qmd: "Comment module depends on User module unidirectional"
related:
  - ../../../User/docs/wiki/concepts/no-comment-module-dependency.md
  - ../rules/module-dependency-rules.md
---

# Comment → User: dipendenza unidirezionale

## Regola

**Comment PUO' dipendere da User** (unidirezionale).

- `Comment` usa `Modules\User\Models\User`, `Modules\User\Models\BaseUser` in seeders, factories, policies, tests e provider.
- `User` **NON** deve mai importare classi da `Comment`.

## Perché

User è un modulo infrastrutturale di identità/auth. Comment è un modulo di dominio. La freccia va dal dominio all'infrastruttura, mai viceversa.

## Verifica

```bash
# User non deve importare Comment
grep -r "use Modules\\\\Comment" Modules/User/app/ --include="*.php"
# → deve restituire 0 risultati

# Comment può importare User
grep -r "use Modules\\\\User" Modules/Comment/app/ --include="*.php"
# → OK, risultati attesi
```
