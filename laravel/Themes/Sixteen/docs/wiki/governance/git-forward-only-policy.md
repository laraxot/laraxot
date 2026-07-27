---
title: "Politica Git Forward-Only — Tema Sixteen"
type: rule
tags: [git, forward-only, sixteen, themes]
created: 2026-04-01
updated: 2026-07-27
qmd: "sixteen git forward-only never restore checkout reset revert study show"
related:
  - ../../../../docs/wiki/concepts/git-forward-only-discipline.md
  - ../../../../docs/wiki/concepts/git-forward-only-study-old-version.md
---

# Politica Git: Forward-Only (Sixteen)

Conformità globale: [git-forward-only-discipline](../../../../docs/wiki/concepts/git-forward-only-discipline.md).

1. Bug CSS/JS/Lit → **nuovo** commit di fix (roll-forward), mai revert/reset.
2. Passato = `git show` / `git log` (sola lettura). **Mai** restore / checkout -- / reset / revert / rollback.
3. Codice vecchio utile → studia → riscrivi **migliorato** sul file attuale.
4. Integrazioni componenti → `docs/wiki/log.md`.

Cursor: `.cursor/rules/git-forward-only.mdc` (**alwaysApply**).
