---
title: "CLAUDE Theme Development"
type: concept
tags: [claude, theme, dev]
created: 2026-07-14
updated: 2026-07-14
qmd: "claude-theme-dev claude theme development"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index.md"
  - "./01-gsd-workflow.md"
  - "./02-bmad-workflow.md"
  - "./03-architecture-zen.md"
  - "./04-filament-philosophy.md"
  - "./05-front-office-audit.md"
  - "./06-cinematic-effects.md"
  - "./07-mcp-tailwind-ui.md"
---

# CLAUDE Theme Development

Guida allo sviluppo del tema Meetup.

## Theme Development (Meetup Theme)

Il Meetup theme è una **VERSIONE MIGLIORATA** di laravelpizza.com usando Tailwind CSS + Alpine.js.

### Obiettivi di Miglioramento

- ✨ Design moderno e accattivante
- 🚀 Migliori animazioni e interazioni
- 🎯 Clickbait-worthy headlines e CTAs
- 💥 Viral-ready social sharing
- 🔥 Conversion-optimized user flows

Il tema deve far dire "WOW!" ai visitatori.

---

## Setup & Build

```bash
cd laravel/Theme/Meetup

# Development
npm run dev

# Production build
npm run build
npm run copy    # MUST run after build to copy to public_html
```

---

## Key Files

- `resources/css/app.css` - Tailwind styles
- `resources/js/app.js` - Alpine.js components
- `resources/views/pages/` - Folio routes
- `resources/views/components/blocks/` - Reusable block components
- `resources/views/layouts/` - Page layouts

---

## Layout Hierarchy

- `x-layouts.main` - Base HTML shell (no header/footer)
- `x-layouts.app` - Full layout with nav + footer (public pages)
- `x-layouts.guest` - Auth pages (login/register)

---

## Front Office Architecture

### ✅ CORRETTO

```
Folio (routing)
├── Theme Fallback: [container0]/[slug0]/index.blade.php
├── Volt (anonymous Livewire - OK for Folio)
└── Blade Components (modular)
```

### ❌ SBAGLIATO

```
Themes/*/Http/Livewire/  ← FORBIDDEN!
PredictController@index   ← FORBIDDEN!
```

---

## 🔗 Link

- [Indice CLAUDE](./claude-split-index.md)
- [claude.md originale](../../claude.md)
- [Index principale](./index.md)
