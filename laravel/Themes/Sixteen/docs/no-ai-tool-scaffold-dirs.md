---
title: No AI/tool scaffold directories in theme tree
---

# Perché queste cartelle non devono esistere qui

Regola canonica: [module-theme-root-cleanup.md — Rule 5](../../../../docs/wiki/rules/module-theme-root-cleanup.md).

Rimosse in questo tema: `scripts/` (~80 file .cjs/.mjs/.py di debug/screenshot/visual-compare), `bashscripts/`, `test-results/`. Aggiunte al `.gitignore` del tema sotto la sezione `# AI/TOOL SCAFFOLD`.

**Perché**: come i moduli, ogni tema vive anche come repo Git indipendente (`git remote -v` → `theme_sixteen_fila5`); strumenti/agenti AI o CI che girano in quella root scrivono lì la propria cache/scaffold locale, ignorando che è un sotto-albero del monorepo con le proprie convenzioni (`docs/` unica, `bashscripts/` unica alla root del monorepo, `build/` per gli artefatti generati, non `test-results/` locale). Duplicare la stessa categoria di contenuto in un secondo posto è entropia, non struttura — la stessa domanda ("dove sono gli script di automazione di questo tema?") avrebbe due risposte diverse.

Nota collaterale scoperta durante la pulizia: il `.gitignore` precedente ignorava anche `components` e `layouts` senza slash finale — pattern troppo generico che stava escludendo dal tracking `resources/views/components/` e `resources/css/components/`, cartelle reali del tema mai state committate. Corretto rimuovendo quelle righe.
