---
title: Design Comuni Site-Wide Component CSS Rule
type: concept
tags: [sixteen, design-comuni, css, parity, governance, daisyui]
created: 2026-04-22
updated: 2026-05-15
sources:
  - https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html
---

# Design Comuni Site-Wide Component CSS Rule

## Regola Permanente

Il tema Sixteen deve seguire il modello Design Comuni: CSS per componenti riusabili del sito, non CSS per singola pagina e non CSS per singolo wizard di dominio.

## Vietato

- `.page-content[data-slug="tests.segnalazione-crea"] ...`
- `.ticket-wizard-root ...`
- modificatori route-specific come owner della resa visuale ordinaria;
- duplicare la stessa regola in Blade o in CSS page-scoped.

## Consentito

Usare selettori di componente e semantica riusabile:

- `.fi-sc-wizard`
- `.fi-section`
- `.fi-section[data-step-section="inefficiency"]`
- `coordinate-picker-lit`
- `.it-header-slim-wrapper`
- `.it-header-navbar-wrapper`

Nel kit statico Design Comuni la pagina e' una composizione di componenti coerenti. Sixteen deve replicare questo approccio.

## Riferimenti

- Token PA FO (no hex per `data-page`): [fo-pa-tokens-uniformity](fo-pa-tokens-uniformity.md) · ADR [fo-pa-tokens-no-per-page-hex.md](../../../../../docs/wiki/decisions/fo-pa-tokens-no-per-page-hex.md)
- Architettura CSS/HTML nel repo ufficiale (stylesheets + `cmp-*`): [entities/design-comuni-class-mapping.md](../entities/design-comuni-class-mapping.md).
- Plugin UI Tailwind: [DaisyUI — documentazione](https://daisyui.com/docs/) (tema Sixteen); linee guida modulo Cms: [daisyui-componenti](../../../../../Modules/Cms/docs/daisyui-componenti.md); **pro/contro e percentuali**: [daisyui-pro-contro-metriche](../../../../../Modules/Cms/docs/daisyui-pro-contro-metriche.md).

## Build

Ogni modifica ai CSS tema richiede `npm run build` e `npm run copy` da `laravel/Themes/Sixteen`.
