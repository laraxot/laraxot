<<<<<<< HEAD
# Docs archive policy

`docs/archive/` is local-only scratch/history and must not be used as a canonical documentation source.

Active module knowledge belongs in normal `docs/*.md`, `docs/wiki/**`, or a precise topical subdirectory. This keeps QMD ingestion deterministic and prevents stale duplicates from outranking current documentation.

The module `.gitignore` ignores `docs/archive/`; when a useful archived note is still valid, promote it into a live document outside `archive` and link it from the local docs index.
=======
---
title: docs archive policy — puntatore
type: reference
updated: 2026-05-21
---

# Docs archive policy (puntatore)

Policy globale: [../../../../docs/wiki/concepts/second-brain-continuous-improvement.md](../../../../docs/wiki/concepts/second-brain-continuous-improvement.md).

`docs/archive/` e `docs/legacy/` sono solo scratch locale; non fonte canonica. Vedi [module-docs-deduplication](../../../../docs/wiki/how-to/module-docs-deduplication.md).

Il `.gitignore` del modulo ignora queste cartelle; se una nota archiviata è ancora valida, promuoverla in un documento attivo e linkarla dall'indice locale.
>>>>>>> laraxot/dev
