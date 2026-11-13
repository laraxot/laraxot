---
type: story
tags: [legal, disclaimer, compliance, multilingua]
created: 2026-10-14
updated: 2026-10-14
qmd: "disclaimer verifica a norma TermsFeed SOS-WP"
issues: []
discussions: []
id: "2026-10-14-dcp-161"
epic: 161
title: "Verifica disclaimer a norma (TermsFeed, SOS-WP, Servizi Industria)"
slug: verifica-disclaimer-a-norma
status: ready-for-dev
scope: theme:Sixteen
github:
  repository: https://github.com/laraxot/module_gdpr_fila5
  issues: https://github.com/laraxot/module_gdpr_fila5/issues/61
  discussions: https://github.com/laraxot/module_gdpr_fila5/discussions/62
  issue_status: "aperta"
related:
  - ../docs/stories/dcp-156-pagine-legali-complete-a-norma.story.md
  - ../docs/stories/dcp-157-cookie-policy-footer-link.story.md
---

# 2026-10-14 DCP-161 Verifica disclaimer a norma

## Fonti studiate

- TermsFeed: disclaimer generatore, sezioni obbligatorie (ownership, IP, restrictions, limitation of liability, external links, changes, governing law, contact).
- SOS-WP: disclaimer blog, focus su limitazione responsabilità e collegamenti esterni.
- Servizi Industria: termini di utilizzo e disclaimer blog, legge applicabile e foro competente.

## Stato attuale

`Themes/Sixteen/resources/views/disclaimer.blade.php` è ora multilingua (`__('disclaimer.*')` in it/en/de/es) con 10 sezioni coperte.

## Accettazione

1. Tutte le 10 sezioni presenti e tradotte in 4 lingue.
2. Disclaimer collegato al footer (link `/disclaimer` o `/note-legali`).
3. Nessun testo hardcodato non tradotto.
4. Nessun file in `app/Http/Controllers`.