---
title: "Sixteen - Niente pubblicità di default sul sito PA (PRD-13)"
type: story
bmad: true
status: done
created: 2026-11-10
owner: Themes/Sixteen
related:
  - ../../../../Modules/Fixcity/docs/audit/PRODUCT_UX_AUDIT.md
---

# Niente pubblicità di default sul sito PA

- Fase BMAD: Quick Flow
- Branch: `dev`

## Problema

Il footer del tema mostrava blocchi AdSense (`footer-comune`) e tre riquadri "Spazio in vendita" con link a `/pubblicita`
(`sections/footer-full`), anche senza configurazione: `COMUNE_ADSENSE_FOOTER_ENABLED` era vero per default e il client
AdSense di ripiego era il finto `ca-pub-XXXXXXXX`. Su un sito istituzionale di un Comune la pubblicità non è prevista e
lo script di Google carica tracciamento di terze parti senza consenso.

## Modifiche

| File | Correzione |
| --- | --- |
| `laravel/config/comune.php` | `COMUNE_ADSENSE_FOOTER_ENABLED` falso per default |
| `components/footer-comune.blade.php` | Ripiego di `comune.adsense.footer_enabled` a `false` (blocchi e script) |
| `sections/footer-full.blade.php` | La sezione sponsor compare solo se il JSON del footer elenca `sponsors`; tolti i tre segnaposto "Spazio in vendita" |

## Verifica

- `php -l config/comune.php`; direttive Blade bilanciate (`@if`/`@endif`, `@foreach`/`@endforeach`).
- Nessun JSON CMS in `config/local/fixcity` definisce `sponsors`: su fixcity.eu la sezione sparisce.

## Aperto

- Chi vuole davvero la pubblicità imposta `COMUNE_ADSENSE_FOOTER_ENABLED=true` con un client reale e gestisce il consenso
  cookie (il banner deve bloccare lo script finché l'utente non accetta).
