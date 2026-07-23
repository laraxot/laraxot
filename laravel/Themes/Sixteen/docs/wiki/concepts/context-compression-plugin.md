---
title: "Sixteen theme context compression boundary"
type: concept
created: 2026-05-11
updated: 2026-05-11
tags: [theme, sixteen, context-compression, qmd, openrouter]
related:
  - ../../../../../../docs/wiki/concepts/context-mode-integration.md
  - ../../../../../../docs/wiki/concepts/context-compression-plugin-openrouter.md
---

# Sixteen Theme Context Compression Boundary

## Perche' serve nel tema

`Sixteen` ha un corpus documentale e visuale rumoroso: HTML locali, screenshot, audit di parity, asset CSS e note di design.
Se questo materiale entra in blocco nel prompt, il tema fa esplodere il budget token senza aggiungere abbastanza business value.

## Regola locale

Per task sul tema:

1. partire da `docs/wiki/` e da questo wiki locale;
2. usare QMD o ricerca mirata per trovare solo la pagina owner;
3. evitare di caricare batch completi di screenshot, HTML dump o audit storici;
4. se il client usa OpenRouter, abilitare `plugins: [{ "id": "context-compression" }]` nel client, non nel tema;
5. lasciare a `context-mode` il lavoro di comprimere output shell e retrieval pesanti.

## Configurazione rilevante

La configurazione operativa vive fuori dal tema:

- `laravel/opencode.json` abilita il plugin OpenRouter e la compaction di sessione
- `laravel/.mcp.json` sposta QMD fuori repo in `${HOME}/.cache/fixcity/...`
- il wiki root resta la memoria canonica comune

## Boundary

Il tema non deve introdurre proprie dipendenze o cache per "risolvere" il context overflow.
Il suo compito e' mantenere documenti piccoli, indicizzati e facili da recuperare on-demand.
