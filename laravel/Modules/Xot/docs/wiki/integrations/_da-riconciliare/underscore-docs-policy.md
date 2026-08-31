---
title: "Underscore Docs Policy"
module: xot
type: integration
tags: [integrations, modules, xot]
created: 2026-08-24
updated: 2026-08-24
---

# Underscore Docs Policy

## Regola

Nel repository `<nome repository>` la cartella `_docs/` non deve mai esistere dentro `laravel/Modules/*`.

Anche le varianti annidate come `docs/_docs/` sono vietate.

## Path corretti

- documentazione viva del modulo: `Modules/<Module>/docs/`
- eventuale documentazione di progetto condivisa: `laravel/project_docs/`

## Perche'

- `_docs/` e' un contenitore ambiguo e non canonico
- favorisce rigenerazioni sporche e contenuti duplicati
- rende meno chiaro quale documentazione e' viva e quale e' scarto

## Regola operativa

1. I `.gitignore` dei moduli devono contenere `_docs/`.
2. Se compare una cartella `_docs/`, va rimossa.
3. La documentazione utile va migrata o mantenuta solo in `docs/`.
