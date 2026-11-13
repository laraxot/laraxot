---
title: "[STORY] Home «Novità»: le card stanno 3 per riga (specificità del CSS dei blocchi)"
type: story
status: done
module: Sixteen
priority: medium
created: 2026-11-12
updated: 2026-11-12
tags: [bmad, ecc, sixteen, home, css, specificita, bootstrap, ui, playwright]
qmd: "home novita news card grid 2 per riga gap g-4 body:has data-dc specificita 0,3,1 fc-home-news getComputedStyle"
related:
  - front-office-production-readiness-audit.story.md
  - ../architecture/css-scoping-rule.md
  - ../../../../Modules/Blog/docs/stories/front-office-demo-content-population.story.md
issues: ["https://github.com/laraxot/theme_sixteen_fila5/issues/112"]
discussions: ["https://github.com/laraxot/theme_sixteen_fila5/discussions/95"]
---

# [STORY] Home «Novità»: le card stanno 3 per riga

**Issue:** [#112](https://github.com/laraxot/theme_sixteen_fila5/issues/112) (aperta il 2026-11-12; correzione committata dalla sync, restano la verifica in produzione e il censimento degli altri blocchi).

**Discussione (decisioni e alternative scartate):** [#95](https://github.com/laraxot/theme_sixteen_fila5/discussions/95), complementare a [#93](https://github.com/laraxot/theme_sixteen_fila5/discussions/93) (head SEO) e [#94](https://github.com/laraxot/theme_sixteen_fila5/discussions/94) (audit, area loggata).

## Fase BMAD

**Dev** + **Doc**. Origine: difetto `F-06` dell'audit del front office ([story madre](front-office-production-readiness-audit.story.md)),
trovato guardando lo screenshot della home a 1440 px, non dai controlli automatici (nessuno misura il layout). ECC usato: `browser-qa`.

## Sintomo (UI)

A **1440 px** il blocco mostrava **2 card per riga** con la terza sola sotto e meta' larghezza vuota; a **800 px** una card per riga (attese 3 e 2).
Con 3 notizie in evidenza, `/it`, `/en`, `/de`, `/es`.

## Prove e falso allarme

- Prima ipotesi **sbagliata**: «le immagini delle card mancano» (nello screenshot c'era un buco in cima). Riprodotta prima di correggere: le immagini sono
  `loading="lazy"` e nello screenshot del solo viewport non erano ancora caricate; portate in vista caricano in **2,8 s** sotto carico 35.
  Non era un difetto. Il difetto vero era nel layout.
- Misura con `getComputedStyle`: la riga era `display: flex` (non `grid`), la colonna aveva `padding: 0 12px` (non `0`) e `gap: 1.5rem`.

## Causa

Il CSS compilato del tema scopa le regole di Bootstrap come `body:has(.page-content[data-dc]) .row`. `:has()` conta come la specificita' del suo argomento:
`.page-content[data-dc]` pesa 0,2,0 piu' `body` e `.row` = **0,3,1**. Il `<style>` del blocco aveva `.fc-home-news__grid { display: grid }` (0,1,0) e
`.fc-home-news__grid > [class*="col-"] { padding: 0; width: auto }` (0,2,0): **non vincono mai**, anche se vengono dopo nel documento. Il `gap: 1.5rem`
invece si applica anche in flex e **si somma** al gutter `g-4`: 3 x 440 + 2 x 24 = 1368 px in una riga da 1320, quindi la terza card va a capo.

## Correzione

In `Themes/Sixteen/resources/views/components/blocks/home/news.blade.php` tolte le due regole inline (`display: grid` + `gap` e `padding: 0` + `width: auto`).
Il layout lo dicono le classi del markup: `row g-4` e `col-md-6 col-xl-4` sulle card. Il resto del `<style>` (card, categoria, titolo) e' invariato.

## Guardia

`Themes/Sixteen/tests/browser/home-news-grid.spec.mjs` (Playwright, legge solo la home pubblica): 3 card sulla prima riga a 1440 px, 2 a 800 px, 1 a 375 px,
e nessuno scorrimento orizzontale. **Rossa prima** (2 falliti: 1440 con 2 invece di 3; 800 con 1 invece di 2), **verde dopo** (3 passati).

```bash
cd laravel/Themes/Sixteen
FIXCITY_BASE_URL=http://127.0.0.1:8081 ./node_modules/.bin/playwright test tests/browser/home-news-grid.spec.mjs --reporter=list --workers=1
```

La regola generale e' scritta in [css-scoping-rule.md](../architecture/css-scoping-rule.md), sezione «Specificita': il `<style>` di un blocco perde contro lo scope».

## Accettazione (con prova)

- [x] Spec rossa prima, verde dopo: 3 passati (1440, 800, 375).
- [x] Controllo a occhio dello screenshot dopo la correzione: 3 card per riga, immagini presenti.
- [x] Regola documentata nel tema (`css-scoping-rule.md`) e nella memoria di progetto.
- [x] Censimento degli altri blocchi con la stessa trappola (`<style>` con `gap`, `display:grid`, `padding:0`, `width:auto` su un `.row`), fatto il 2026-11-12 sul dev (`:8081`): **due soli blocchi candidati**, `components/blocks/events-page/design-comuni.blade.php` (`/it/eventi`) e `components/blocks/blog/index.blade.php` (`/it/blog`). Misurati con Playwright a 1440/800/375 px: griglia CSS, `gap` 24 px, **3 card per riga a 1440 e a 800, 1 a 375**, nessuno scorrimento orizzontale. Non regrediscono perche' i loro selettori sono scopati sull'attributo (`main[data-page="eventi"] .row.g-4`, specificita' sopra lo scope `body:has(.page-content[data-dc]) .row`, 0,3,1): il difetto della home era un selettore a bassa specificita'. 7 falsi positivi del grep (`.row-title`, `.row-calendar` combaciano con `\.row\b`), scartati leggendo i selettori. `/it/lista-risorse` risponde 404: il blocco si vede su `/it/blog`.
- [ ] **Non fatto:** (a) un test che vieti in modo meccanico un `.row` ridisegnato con selettore debole: i due blocchi sopra lo fanno **legittimamente** (selettore forte), quindi servirebbe una allowlist motivata e non l'ho scritto; (b) verifica **in produzione** dopo il deploy: dal dev non la posso fare.

## Lezioni

1. Un CSS che «sembra giusto» puo' non fare nulla: confrontare sempre con lo stile calcolato, non con il sorgente.
2. Il `gap` non entra in conflitto con Bootstrap: si somma. Un `.row` non va ridisegnato con `gap`.
3. Prima di scrivere «immagine mancante» da uno screenshot del viewport: scrollare e aspettare, oppure misurare `img.complete`.
4. Nessun controllo automatico misurava il layout: la guardia e' stata aggiunta insieme alla correzione.
