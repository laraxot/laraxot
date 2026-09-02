---
title: "User-Comment Module Dependency Architecture"
type: concepts
tags: [architecture, user, comment, dependencies, modules]
qmd: true
---

# User-Comment Module Dependency Architecture

## Pattern

**User module** è il modulo base per autenticazione e profili.

**Comment module** può dipendere da User (già configurato in `composer.json`).

**Predict module** ha `Models\User` che estende `Modules\User\Models\BaseUser` - NON è una dipendenza incrociata ma un pattern di specialization.

## Dipendenze

```json
// Modules/User/composer.json - nessun require da altri moduli
// Modules/Comment/composer.json - può require User
// Modules/Predict/composer.json - usa User\Models ma non require
```

## Regole

1. User NON deve mai require Comment
2. Comment può require User
3. Predict User specialization estende BaseUser da User module