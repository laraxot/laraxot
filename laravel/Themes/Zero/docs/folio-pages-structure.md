---
title: "Folio pages — struttura tema Zero (ptvx)"
type: concept
tags: [folio, pages, theme-zero, ptvx, auth, dry, kiss]
created: 2026-07-22
updated: 2026-07-22
qmd: "folio pages structure theme zero ptvx auth home index no semantic directories"
issues:
  - https://github.com/provtv/base_ptv_fila5/issues/124
discussions:
  - https://github.com/laraxot/base_fixcity_fila5/discussions/273
  - https://github.com/laraxot/platform/discussions/273
related:
  - ./architecture.md
  - ./authentication.md
  - ../../One/docs/folio-pages-structure.md
  - ../../../../docs/wiki/rules/no-semantic-folio-page-directories.md
  - ../../../../bashscripts/tools/verify-no-semantic-folio-pages.sh
---

# Folio pages — Theme Zero (ptvx)

## Scopo

Stesso contratto FO di Theme One su ptvx: `pages/` = shell tema, non albero di domini editoriali.

## Layout attuale

```text
resources/views/pages/
├── auth/login.blade.php
├── home.blade.php
└── index.blade.php
```

## Perché

- **DRY:** login/home restano nel tema; business logic nei moduli.
- **KISS:** niente `pages/dashboard` / `pages/profile` come cartelle — esempi storici nei docs Zero non sono licenza a crearli.
- Fixcity/Sixteen (`[container0]`) **non** è presente in questo repo; non forzarlo qui.

## Provenienza

Studiato (read-only) lo script/canon Fixcity Sixteen; su Zero si applica forward-only lo stesso divieto semantic dirs senza forzare `container0`/`tests`.  
- progetto corrente/Sixteen (`[container0]`) **non** è presente in questo repo; non forzarlo qui.

## Provenienza

Studiato (read-only) lo script/canon progetto corrente Sixteen; su Zero si applica forward-only lo stesso divieto semantic dirs senza forzare `container0`/`tests`.  
Git: `git show` / basi sibling — **mai** restore ([git-forward-only](../../../../docs/wiki/rules/git-forward-only.md)).

## Enforcement

[`verify-no-semantic-folio-pages.sh`](../../../../bashscripts/tools/verify-no-semantic-folio-pages.sh) — greppa **tutti** i temi con `pages/`, fallisce solo su cartelle semantiche vietate; richiede `[container0]` solo se tema = Sixteen o pattern già adottato.

Dettaglio parallelo: [One folio-pages-structure](../../One/docs/folio-pages-structure.md).
