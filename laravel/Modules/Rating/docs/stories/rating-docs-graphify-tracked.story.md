---
id: rating-docs-graphify-tracked
title: "Rimuovere docs/graphify tracciato in Rating"
type: story
status: open
priority: P3
owner: module:Rating
module: Rating
tags: [git, igiene, graphify]
related: [lang-docs-graphify-tracked]
---

# Rimuovere docs/graphify tracciato in Rating

Fase BMAD: Dev.

## Stato
`docs/graphify` è output generato, tracciato nel modulo Rating. Lo stesso vale per Lang e Notify.

## Azioni
1. Verificare che nessun riferimento nel codice o nelle docs punti a `docs/graphify`.
2. Aggiungere la cartella a `.gitignore` e rimuoverla dal tracciamento in un commit separato.

## Verifica
- `ModuleRootHygieneTest` verde per Rating.
