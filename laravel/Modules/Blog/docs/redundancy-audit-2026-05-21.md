---
title: "Blog redundancy audit 2026-05-21"
type: audit
module: Blog
tags: [redundancy, blade, theme-boundary]
created: 2026-05-21
related:
  - https://github.com/laraxot/base_fixcity_fila5/issues/89
---

# Blog redundancy audit 2026-05-21

High-risk findings:
- Several Blog Blade blocks are byte-identical to `Themes/TwentyOne`, especially `article_list/play_money_markets/...` fragments.
- `article_list.blade.php` and `leaderboard.blade.php` are byte-identical.
- `banner_and_slides/v1.blade.php` and `banner_slides/v1.blade.php` are byte-identical.
- Config is duplicated under `app/Config/config.php` and `config/config.php`.

Risk:
- Module/theme boundary is unclear: reusable domain blocks belong in the module; theme-specific presentation belongs in the theme.
- Duplicated config path creates uncertainty about which file is published/merged.

Suggested cleanup order:
1. Decide ownership for play-money-market fragments: Blog domain block or TwentyOne theme override.
2. Replace theme copies with overrides only where they intentionally diverge.
3. Keep one config path aligned with the module service provider.
