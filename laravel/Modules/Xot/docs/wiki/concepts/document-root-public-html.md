---
title: "Document root public_html"
type: concept
tags: [architecture, public_html, laravel]
created: 2026-09-01
updated: 2026-09-01
qmd: "document root public_html laravel Application publicPath"
related:
  - ../rules/public-path-public-html.md
  - ../../../../Notify/docs/architecture/document-root-architecture.md
---

# Document root `public_html`

## Layout repo

```
base_ptvx_fila5/
├── public_html/          ← DocumentRoot (browser, artisan serve via server.php)
│   ├── index.php         → bootstrap da ../laravel/
│   ├── css/, js/, assets/, themes/, modules/
├── laravel/              ← Applicazione (base_path)
│   ├── app/Application.php
│   ├── public/           ← cartella Laravel default; NON servita in produzione
```

## Filosofia

Laravel standard mette il web root **dentro** il progetto (`public/`). Laraxot lo **sposta un
livello sopra** rispetto a `laravel/`: il repo è un monorepo tooling + app + document root.

Conseguenza: **`public_path()` è l'API** — non indovinare path. Se scrivi stringhe verso
`laravel/public`, stai scrivendo codice che in produzione non trova i file.

## Vite e temi

Build Vite possono scrivere prima in `laravel/public/themes/…`; il deploy copia in
`public_html/themes/…`. Vedi memoria [dual-public-html-architecture.md](../../../../../../bashscripts/ai/wiki/memories/dual-public-html-architecture.md).

La regola `public_path` resta: a runtime Laravel legge sempre `public_html/`.
