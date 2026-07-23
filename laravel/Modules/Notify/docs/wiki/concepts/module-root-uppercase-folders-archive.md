---
title: "Archivio Modules.bak — modulo Notify"
type: concept
module: Notify
status: active
tags: [module-structure, archive, docs]
updated: "2026-06-30"
related:
  - "./claude-audit-static.md"
  - "./code-redundancy-notify.md"
  - "./composer-root-minimal-nwidart.md"
  - "./context-overflow-prevention.md"
  - "./enum-standards.md"
  - "./llm-wiki-governance.md"
  - "./method-name-homonyms.md"
  - "./no-app-support-queueable-actions.md"
---

# Archivio `Modules.bak/` — Notify

## Situazione

Path anomalo: `Modules/Xot/docs/llm-wiki-integration.md` sotto Notify (non è un sottomodulo reale).

## Regola

La root di Notify non deve contenere una cartella `Modules/` (PascalCase). Documentazione Xot va in `Modules/Xot/docs/`.

## Azione

`Modules/` → `Modules.bak/` (2026-06-30). Da discutere: spostare il markdown in Xot o in `docs/project/` e poi rimuovere l’archivio.
