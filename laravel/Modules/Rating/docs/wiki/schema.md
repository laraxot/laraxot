---
title: Wiki Schema
description: Schema e convenzioni per la manutenzione della wiki
tags:
  - schema
  - conventions
  - llm-instructions
created: 2026-04-15
---

# Wiki Schema - Rating

Istruzioni per l'LLM su come mantenere questa wiki.

## Struttura

```
docs/
├── wiki/
│   ├── index.md           # Catalogo
│   ├── log.md             # Registro
<<<<<<< HEAD
<<<<<<< HEAD
=======
<<<<<<< HEAD
│   ├── schema.md          # Questo file
=======
>>>>>>> laraxot/dev
<<<<<<< HEAD
<<<<<<< HEAD
│   ├── schema.md          # Questo file
=======
<<<<<<< HEAD
=======
>>>>>>> fd7a600 (.)
<<<<<<<< HEAD:docs/wiki/schema.md
│   ├── schema.md          # Questo file
========
=======
>>>>>>> laraxot/dev
<<<<<<< HEAD
│   ├── schema.md          # Questo file
=======
│   ├── SCHEMA.md          # Questo file
>>>>>>> laraxot/dev
<<<<<<< HEAD
>>>>>>>> laraxot/dev:docs/wiki/SCHEMA.md
=======
>>>>>>> laraxot/dev
<<<<<<< HEAD
<<<<<<< HEAD
>>>>>>> laraxot/dev
=======
>>>>>>> fd7a600 (.)
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
=======
│   ├── schema.md          # Questo file
│   ├── SCHEMA.md          # Questo file (case-duplicate di schema.md)
>>>>>>> e8cf105 (Check & fix styling)
│   ├── concepts/          # Pattern, architettura
│   ├── entities/          # Modelli, azioni
│   ├── sources/           # Doc esterna
│   └── comparisons/       # Tabelle comparative
└── raw/                   # Sorgenti immutable
```

## Convenzioni

- File: kebab-case (es. `entity-user.md`)
- Frontmatter: title, description, tags, created
- Cross-ref: `Link`
- NON modificare mai `docs/raw/`
