---
title: "claude-audit static — modulo Blog"
type: concept
module: Blog
tags: [blog, quality, claude-audit]
created: 2026-07-09
updated: 2026-07-09
qmd: "Blog claude-audit static 80 score ArticleQueryScopes blade partials"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/704"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/705"
related:
  - ../../../../../../bashscripts/tools/run-claude-audit-module-static.sh
  - ../../../../../../docs/wiki/guidelines/claude-audit-static-free-tier.md
---

# claude-audit static (Blog)

## Comando

```bash
bash bashscripts/tools/run-claude-audit-module-static.sh Blog
```

## Fix applicati (80/0)

- `ArticleQueryScopes` trait — `Article.php` sotto 500 LOC
- Rimosso codice commentato annidato in `Menu.php`
- Blade grandi (`search/v1`, leaderboard v2) → partials + wrapper `@include`
- `fix-blade-audit-doc-ratio.py` — commenti `{{-- --}}` su blade >100 righe

## Report

`Modules/Blog/.claude-audit/audit-report.html`
