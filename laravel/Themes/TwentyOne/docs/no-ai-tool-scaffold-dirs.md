---
title: No AI/tool scaffold directories in theme tree
---

# Perché queste cartelle non devono esistere qui

Regola canonica: [module-theme-root-cleanup.md — Rule 5](../../../../docs/wiki/rules/module-theme-root-cleanup.md).

Rimosse in questo tema (dove presenti): `scripts/`, `bashscripts/`, `test-results/`, `docs/archive/`, `.devcontainer/`. Aggiunte al `.gitignore` del tema.

**Perché**: come i moduli, ogni tema vive anche come repo Git indipendente; strumenti/agenti AI o CI che girano in quella root scrivono lì la propria cache/scaffold locale, ignorando che è un sotto-albero del monorepo con le proprie convenzioni (`docs/` unica, `bashscripts/` unica alla root, `build/` per gli artefatti generati). Duplicare la stessa categoria di contenuto in un secondo posto è entropia, non struttura.
