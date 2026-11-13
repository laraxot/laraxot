---
title: "civic homepage conversion playbook"
type: playbook
tags: [marketing, homepage, design-comuni, conversion, municipal]
created: 2026-10-22
updated: 2026-10-23
qmd: "civic homepage services-first conversion design comuni guest marketing playbook"
related:
  - ./homepage-conversion-contract.md
  - ./gtm-agent-tooling.md
  - ../bmad/homepage-guest-visual-contract.md
  - ../../../../Modules/Fixcity/docs/bmad/homepage-guest-expected-visual.md
status: draft
---

# Playbook: homepage civica che converte (tutti i temi)

## Religione (senso)

Il cittadino non cerca un “brand story”: cerca di **fare una cosa** (segnalare, vedere, tracciare). Il marketing migliore per un portale comunale è **services-first** + **fiducia istituzionale** (Design Comuni / Bootstrap Italia), non hero charcoal stile SaaS.

Fonti: GovStack / Munibit (task sopra-the-fold), Peterborough digital principles (plain language, iterate), GOV.UK homepage (priorità task, non elenco esaustivo), Nielsen (critical above the fold).

## Contratto FixCity `/it`

1. Hero = `#head-section.fc-home-head.bg-primary` (verde ente `#007a52`) + mappa viva a destra; mai `fc-home-hero` charcoal senza prova operativa.
2. Un solo H1 beneficio + intro breve con esempi concreti (buca, lampione, rifiuti).
3. CTA primaria: **Segnala un problema** → create.
4. CTA secondaria: **Vedi le segnalazioni** / elenco.
5. Link auth: Accedi / Le mie pratiche (context-aware).
6. Mappa viva a destra + “Vai all’elenco” nel footer card.
7. Come funziona in 3 passi; banda traccia codice in fondo.
8. Copy da SSOT `laravel/config/local/fixcity/database/content/pages/home.json` + `pub_theme::home.*`; niente `pages/home.blade.php`.

## Checklist tema (riusabile)

- [ ] Chrome istituzionale coerente con header
- [ ] CTA primaria visibile above-the-fold a 375 e 1440
- [ ] Verbi d’azione (Segnala / Traccia / Vedi), no “Scopri di più”
- [ ] Contrasti AA, target ≥ 44px
- [ ] Screenshot Puppeteer/Playwright in `/tmp/` prima di dichiarare “fatto”

## Gutter

- Sotto lg: hero/how/track fluidi (`max-width: 100%`) con `--fc-home-gutter: 3.5rem`
  allineati al brand header, non a 12px Bootstrap.
- Desktop (≥992): stesso max 1320 del chrome header.
- Vietato `#main-container > section > .container { padding: 0 }` su `#head-section`.
- Banda track: verde scuro + `fc-button--sun`/`quiet` in riga (parity pubblica
  desiderabile). Target ≥ 44px; icone SVG boundate. Non utility flex BI sotto
  `data-dc`. Hero: `#head-section.fc-home-head` + mappa viva (studio pages_old).

## Desiderabilità (studio pages_old → avanti)

La home storica (`pages_old/index`) insegnava: **verde ente + mappa viva nella
hero**, ritmo `py-5`, CTA «Segnala», Accedi/register guest, una sola mappa.
Non si ripristina quel markup: la home JSON-driven monta `#head-section.fc-home-head`
con `map-lit`, trust line, H1 Lora, CTA `btn-hero-*`, how-grid + track sun.

Anti-pattern respinto: hero charcoal SaaS + card «impegno civico» al posto
della mappa; seconda mappa full sotto la piega.

Implementazione: `home/hero.blade.php` + `16-homepage-civic-reframe.css` +
`pub_theme::home.hero.*` + `home.json` (sync CMS per-locale).

## Anti-pattern

- Hero marketing scuro scollegato dal verde header
- CTA sotto la piega / nascoste dal cookie banner senza scroll hint
- Slider o promo al posto dei task
- Prospecting B2B (Explorium) usato come guida del FO cittadino
