---
title: "Project Roadmap"
type: concept
tags: [roadmap]
created: 2026-07-14
updated: 2026-07-14
qmd: "roadmap project roadmap"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./2025-excellence-achievement.md"
  - "./agid-implementation-guide.md"
  - "./architecture.md"
  - "./complete-refactoring-analysis.md"
  - "./documentation-status.md"
  - "./final-implementation-report-.md"
  - "./final-implementation-report-1.md"
  - "./final-implementation-report.md"
---

# Project Roadmap

## Goals
- Zero PHPStan errors across all Modules.
- Full upgrade and alignment to Filament v4 patterns.
- Consistent use of Laraxot/Xot base classes (XotBase*) and contracts.
- Robust module documentation and migration notes.

## Milestones
- M1: Fix critical syntax/contract issues in User module (`BaseUser`, `Profile`).
- M2: Clean Filament pages/resources in User and Notify (v4 forms/actions, remove deprecated patterns).
- M3: Achieve PHPStan 0 errors across Modules; add missing types and return annotations.
- M4: Final docs pass with per-module ROADMAPs, changelogs, and migration notes.

## Execution Plan
- Iterate with PHPStan: run, fix, re-run until 0 errors.
- Apply Filament v4 upgrade guide changes as we go.
- Keep changes small and verifiable; add tests where helpful.
