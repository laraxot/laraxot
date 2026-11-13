---
type: story
tags: [cookiebar, cleanup, technical-debt]
created: 2026-10-14
updated: 2026-10-14
qmd: "cookiebar duplicate cleanup feedback ui"
issues: []
discussions: []
id: "2026-10-14-dcp-158"
epic: 158
title: "Cookiebar duplicate cleanup (feedback vs ui)"
slug: cookiebar-duplicate-cleanup
status: ready-for-dev
scope: theme:Sixteen
github:
  repository: https://github.com/laraxot/module_gdpr_fila5
  issues: https://github.com/laraxot/module_gdpr_fila5/issues/58
  discussions: https://github.com/laraxot/module_gdpr_fila5/discussions/59
  issue_status: "aperta"
related:
  - ../docs/stories/dcp-153-cookiebar-integrata-nel-modulo-gdpr.story.md
---

# 2026-10-14 DCP-158 Cookiebar duplicate cleanup (feedback vs ui)

## Stato attuale

Due file cookiebar identici:
- `Themes/Sixteen/resources/views/components/feedback/cookiebar.blade.php` (legacy)
- `Themes/Sixteen/resources/views/components/ui/cookiebar.blade.php` (attivo, usato da `bootstrap-italia.js`)

## Obiettivo

- Eliminare `feedback/cookiebar.blade.php` (o marcarlo superseded).
- Aggiornare i riferimenti rimasti (se ce ne sono).

## Accettazione

1. Un solo cookiebar attivo: `components/ui/cookiebar.blade.php`.
2. Nessun riferimento a `feedback.cookiebar`.
3. Nessun test rotto.