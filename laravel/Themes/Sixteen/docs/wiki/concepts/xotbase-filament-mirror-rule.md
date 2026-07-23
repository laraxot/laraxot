---
title: "Themes consume Filament through the Xot mirror"
type: concept
confidence: high
created: 2026-07-16
updated: 2026-07-16
qmd: "xotbase filament mirror inheritance"
issues: ["https://github.com/laraxot/base_techplanner_fila5/issues/45"]
discussions: ["https://github.com/laraxot/base_techplanner_fila5/discussions/12"]
tags: [themes, sixteen, filament, xotbase]
---

# Themes consume Filament through the Xot mirror

Themes may compose Filament components, but concrete PHP classes must extend the matching abstract `Modules\\Xot\\Filament\\...\\XotBase*`. Direct inheritance from `Filament\\...` creates a second framework boundary and is forbidden.

Keep presentation in the theme, shared Filament lifecycle policy in Xot, and domain behavior in modules. Vendor/demo documentation is an external example, not runtime architecture.

Canonical rule: `docs/wiki/rules/xotbase-critical-rules.md`.
