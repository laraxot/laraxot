---
title: Sixteen testing and documentation boundary
type: guide
status: active
updated: 2026-10-19
tags: [tests, theme, modules, documentation, bmad]
qmd: "sixteen testing boundary sixteen"
---

# Sixteen testing boundary

Sixteen is a theme, not a Nwidart module. Theme-specific tests belong under
`laravel/tests/Feature/Themes/Sixteen/` (or the matching theme test area), while
tests for Cms, Fixcity, User and other modules belong inside their module:
`laravel/Modules/<Module>/tests/`.

Do not use `laravel/tests/Modules/<Module>/` for module tests. When a Sixteen
change exercises a module, keep the behavior test in that module; only keep a
test under the theme tree when the assertion is specifically about theme
rendering, assets or visual layout.

Before documenting a test path, verify it with:

```bash
find laravel/Modules/<Module>/tests -maxdepth 1 -type f
find laravel/tests/Feature/Themes/Sixteen -maxdepth 2 -type f
```
