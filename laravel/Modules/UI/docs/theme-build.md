---
title: "Theme Build & Publish Guide"
type: concept
tags: [theme, build]
created: 2026-07-14
updated: 2026-07-14
qmd: "theme-build theme build & publish guide"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index.md"
  - "./04-datas.md"
  - "./advanced-form-components-1.md"
  - "./advanced-form-components.md"
  - "./agent-confidence-discipline.md"
  - "./agent-confidence-protocol.md"
  - "./agent-edit-discipline.md"
---

# Theme Build & Publish Guide

## Installazione dipendenze

All'interno di `Themes/One`, installa i plugin necessari per Filament 3.x con:
```bash
npm install tailwindcss@3 @tailwindcss/forms @tailwindcss/typography postcss postcss-nesting autoprefixer --save-dev
```

Per aggiornare il tema **One**, eseguire i seguenti passi all'interno della cartella del tema:

```bash
cd Themes/One

# 1. Compilare asset (Tailwind, JavaScript, CSS):
npm run build

# 2. Pubblicare asset nella cartella pubblica:
npm run copy
```

> **Requisito:** Filament 3.x supporta solo **Tailwind CSS 3.x**. Verificare in `package.json` di avere `"tailwindcss": "^3.x"` come dipendenza.

Se è la prima volta, verificare di aver eseguito `npm install` per le dipendenze.
