---
title: "Audit collisioni Git committate in bashscripts"
type: report
created: 2026-07-31
updated: 2026-07-31
id: zero-docs-bmad-git-collisions-bashscripts-audit
slug: git-collisions-bashscripts-audit
description: "Risoluzione deterministica per singolo blocco: lato non vuoto, superset, metadata updated più recente, quindi HEAD come spareggio conservativo."
document_type: analysis
category: git-workflow
status: superseded
tags: []
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
