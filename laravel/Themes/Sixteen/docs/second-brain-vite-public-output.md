---
title: "second brain vite public output"
type: note
status: draft
updated: 2026-10-20
tags: [docs, sixteen]
qmd: "second brain vite public output sixteen"
---
## Second Brain — Vite Public Output Convention

**Concept:** `vite-public-output`

**Rule:** `laravel/Themes/Sixtaine/public/` è output Vite (svuotato `emptyOutDir: true`). Non inserire file manuali.

**Source directory:** `resources/css/design-comuni/` e `resources/js/` sono i sorgenti che Vite compila in `public/`.

**Error source:** Ho tentato di aggiungere CSS/JS manuali in `public/` invece di modificare i file sorgente. Non è possibile: il build cancella e ricostruisce `public/`.

**Correzione:** Tutti i file `.css` e `.js` che influenzano l'output devono essere modificati in:
- `laravel/Themes/Sixteen/resources/css/design-comuni/` (per il CSS)
- `laravel/Themes/Sixteen/resources/js/` (per il JS)

**Related BMAD:** `laravel/Themes/Sixteen/docs/bmad/vite-public-output.md`