---
type: story
tags: [legal, gdpr, cookie-policy, multilingua]
created: 2026-10-14
updated: 2026-10-14
qmd: "cookie-policy pagina link footer multilingua"
issues: []
discussions: []
id: "2026-10-14-dcp-157"
epic: 157
title: "Cookie policy link nel footer (tutti i layout)"
slug: cookie-policy-footer-link
status: ready-for-dev
scope: theme:Sixteen
github:
  repository: https://github.com/laraxot/module_gdpr_fila5
  issues: https://github.com/laraxot/module_gdpr_fila5/issues/57
  discussions: https://github.com/laraxot/module_gdpr_fila5/discussions/58
  issue_status: "aperta"
related:
  - ../docs/stories/dcp-156-pagine-legali-complete-a-norma.story.md
  - ../docs/stories/dcp-153-cookiebar-integrata-nel-modulo-gdpr.story.md
---

# 2026-10-14 DCP-157 Cookie policy link nel footer (tutti i layout)

## Stato attuale

La pagina `cookie-policy` esiste già come contenuto tenant multilingua (`config/.../pages/cookie-policy.index.json`, layout `design-comuni`, blocco `cookie_policy`). Il footer la **non linka**.

## Obiettivo

Aggiungere il link `/it/cookie-policy` (e equivalenti en/de/es) nei footer small-prints di tutti i layout:

- `Themes/Sixteen/resources/views/components/blocks/footer/main.blade.php`
- `Themes/Sixteen/resources/views/components/blocks/footer/exact-1to1.blade.php`
- `Themes/Sixteen/resources/views/components/blocks/footer/v1.blade.php` (se esiste)
- layout `guest.blade.php`, `design-comuni.blade.php`, `main.blade.php` (se includono footer)

## Accettazione

1. Link cookie-policy presente in tutti i footer.
2. Link usa `__('cookie-policy.title')` o testo corretto "Cookie policy".
3. Nessun test rotto.
4. Pagina raggiungibile in it/en/de/es.