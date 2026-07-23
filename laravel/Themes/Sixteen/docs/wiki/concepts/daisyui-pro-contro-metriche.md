---
title: DaisyUI — pro, contro, metriche (tema Sixteen)
type: concept
module: Sixteen
tags: [daisyui, tailwind, sixteen, governance]
updated: 2026-05-15
---

# DaisyUI nel tema Sixteen: sintesi

Questa pagina è l’**overlay tema**; i pro/contro completi e le tabelle percentuali commentate stanno nel modulo Cms (SSoT):

→ **[daisyui-pro-contro-metriche](../../../../../Modules/Cms/docs/daisyui-pro-contro-metriche.md)**

## Perché qui

- Il plugin è dichiarato in **`tailwind.config.js`** e in **`package.json`** del tema Sixteen.
- La **build** (`npm run build && npm run copy`) determina quante regole Daisy finiscono nel CSS effettivo (purge `content`).
- Il rischio principale nel tema è il **doppio lessico** con Design Comuni / Bootstrap Italia (`btn`, `card`, …): la resa dipende dall’ordine degli `@import` / layer e dalle regole in `app.css` + parity.

## Percentuali rapide (riallineamento)

| Tipo | Valore | Dove dettagliato |
|------|--------|------------------|
| Pesi governance (PA vs Tailwind vs Daisy vs Filament) | **45% / 30% / 15% / 10%** | Tabella B nel doc Cms |
| Numeri vendor (riduzione classi/DOM demo) | **~88% / ~79%** | Tabella A nel doc Cms — *non sono KPI del nostro repo* |

## Checklist dopo modifiche Daisy / Tailwind

1. Preferire **nuovi alias** con `@apply` nei CSS del tema, non utility lunghe nei Blade (vedi [bootstrap-italia-tailwind-philosophy](./bootstrap-italia-tailwind-philosophy.md)).
2. `cd laravel/Themes/Sixteen && npm run build && npm run copy`
2. Smoke su pagine **Design Comuni** (header, stepper segnalazione) per regressioni visive.
3. Se si introducono nuovi path Blade/JS, verificare che siano coperti da `content` in Tailwind per il purge.

## Collegamenti

- [design-comuni-class-mapping](../entities/design-comuni-class-mapping.md)
- [design-comuni-site-wide-component-css-rule](./design-comuni-site-wide-component-css-rule.md)
