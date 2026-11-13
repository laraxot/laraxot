---
title: "Rimozione include cookiebar duplicate dai footer (Sixteen)"
type: story
status: completed
created: 2026-10-15
updated: 2026-10-15
module: Sixteen
tags: [cookiebar, gdpr, duplicate, cleanup]
---

# Rimozione include cookiebar duplicate dai footer (Sixteen)

## Richiesta
Ogni pagina riceveva due banner cookie: uno iniettato da `InjectCookieBanner` (vista canonica `cookie-consent::index`)
e uno inline nei footer. DCP-158/DCP-161/DCP-170 lo avevano già identificato ma non ancora eliminato.

## Analisi
- `InjectCookieBanner` middleware (Gdpr) inietta `<x-layouts.marketing>` con `cookie-consent::index` in testa al body.
- `components/ui/footer.blade.php:185` e `components/agid/footer.blade.php` contenevano `@include('sixteen::components.ui.cookiebar')`
  → produceva un secondo banner inline, con testi hardcodati e nessun consenso registrato.
- `components/bootstrap-italia/cookiebar.blade.php` è un bridge che include `components/feedback/cookiebar` — entrambi non usati da nessun layout attivo.

## Modifiche
- `components/ui/footer.blade.php`: rimosso `@include('sixteen::components.ui.cookiebar')`, aggiunto commento che l'iniezione è del middleware.
- `components/agid/footer.blade.php`: stesso trattamento.
- `components/feedback/cookiebar.blade.php` e `components/bootstrap-italia/cookiebar.blade.php`: lasciati (bridge per `pages_old` e test legacy).

## Verifica
```bash
grep -rn "components.ui.cookiebar\|components.feedback.cookiebar" Themes/Sixteen/resources/views/
# solo il bridge bootstrap-italia/cookiebar.blade.php (non incluso da layout attivi)
```

## Decisioni
- **D-1**: unico banner per pagina = `cookie-consent::index` (Gdpr). Nessun include nei footer.
- **D-2**: i due file legacy rimangono come bridge per `pages_old/bootstrap-italia-showcase.blade.php`.

## Aperto
- I due file legacy potranno essere rimossi quando `pages_old` sarà decommissioned.