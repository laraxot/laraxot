---
id: zero-docs-bmad-git-conflict-resolution-audit
slug: git-conflict-resolution-audit
title: "Audit collisioni Git committate in bashscripts"
description: "Risoluzione deterministica per singolo blocco: lato non vuoto, superset, metadata updated più recente, quindi HEAD come spareggio conservativo."
document_type: guide
type: guide
category: git-workflow
status: superseded
tags: []
created: "2026-10-08"
updated: "2026-10-08"
qmd: "audit collisioni git committate in bashscripts"
issues: []
discussions: []
superseded_by: git-collision-audit-bashscripts.md
---

# Audit collisioni Git committate in bashscripts

Risoluzione deterministica per singolo blocco: lato non vuoto, superset, metadata `updated` più recente, quindi HEAD come spareggio conservativo.

| File | Blocchi | Decisioni | SHA-256 prima → dopo |
|---|---:|---|---|
| `laravel/Themes/Zero/docs/code-quality-improvement-report.md` | 1 | shorter_tiebreak=1 | `fe612360cefa` → `102f43cb09f7` |
