---
title: "Custom Theme"
type: concept
tags: [custom, theme]
created: 2026-07-14
updated: 2026-07-14
qmd: "custom-theme custom theme"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./api-relocated.md"
  - "./api.md"
  - "./blocks-relocated.md"
  - "./blocks.md"
  - "./carousel-slider.md"
  - "./changelog.md"
  - "./chunk.md"
  - "./ci.md"
---

https://blog.jpat.dev/build-custom-components-inside-a-filament-v3-panel-with-livewire-and-tailwindcss


php artisan make:filament-theme admin

add resources/css/filament/admin/theme.css entry to vite.config.js

in app/Providers/Filament/AdminPanelProvider.php
->viteTheme('resources/css/filament/admin/theme.css')


