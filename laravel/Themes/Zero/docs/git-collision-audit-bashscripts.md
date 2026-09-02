---
title: "Audit collisioni Git committate in bashscripts"
type: report
tags: [git, conflitti, bashscripts, audit]
created: 2026-07-31
updated: 2026-07-31
qmd: "audit collisioni git committate bashscripts blocchi risolti sha256"
---
# Audit collisioni Git committate in bashscripts

Risoluzione deterministica per singolo blocco: lato non vuoto, superset, metadata `updated` più recente, quindi HEAD come spareggio conservativo.

| File | Blocchi | Decisioni | SHA-256 prima → dopo |
|---|---:|---|---|
| `laravel/Themes/Zero/docs/code-quality-improvement-report.md` | 1 | shorter_tiebreak=1 | `fe612360cefa` → `102f43cb09f7` |
