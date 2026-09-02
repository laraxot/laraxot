---
title: "public_path = public_html (Xot)"
type: concept
tags: [public_path, public_html, AssetAction]
created: 2026-09-01
updated: 2026-09-01
qmd: "public_path public_html AssetAction Xot"
related:
  - ../../logo-resolution.md
  - ../../../../../docs/wiki/memories/public-path-is-public-html.md
---

# Xot e `public_path`

`AssetAction` / `MetatagData` scrivono e risolvono asset sotto **`public_html/assets/...`** perché `public_path()` punta lì (`App\Application`).

Non usare `laravel/public` nelle diagnosi. Canon: [public-path-is-public-html](../../../../../docs/wiki/memories/public-path-is-public-html.md) · [logo-resolution](../../logo-resolution.md).
