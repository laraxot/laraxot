---
title: "TwentyOne redundancy audit 2026-05-21"
type: audit
theme: TwentyOne
tags: [redundancy, blade, theme-boundary]
created: 2026-05-21
related:
  - https://github.com/laraxot/base_fixcity_fila5/issues/89
---

# TwentyOne redundancy audit 2026-05-21

High-risk findings:
- Header/footer sections are duplicated between `layouts/...` and `components/sections/...`: auth, language, links, links_dropdown, notifications, search, socials, crypto.
- Several block fragments are duplicated with `Modules/Blog`, especially `article_list/play_money_markets/...`.
- UI primitives are duplicated with Sixteen: `button`, `input`, `checkbox`, `badge`, `modal`, `logo`, `text-link`, `light-dark-switch`, `placeholder`.
- `pages/[slug].blade.php` and `pages/categories/[slug]/[slug].blade.php` are byte-identical.

Risk:
- Theme layout and component section directories compete as source of truth.
- Blog module fragments copied into theme can drift from domain behavior.

Suggested cleanup order:
1. Decide whether header/footer canonical paths are `layouts` or `components/sections`.
2. Keep module-provided Blog fragments in Blog unless TwentyOne has intentional visual differences.
3. Replace identical page files with one route/view contract after checking Folio/page resolver behavior.
