---
title: "Visual parity 95%+ — Design Comuni test pages"
type: story
status: in-progress
module: theme-sixteen
tags: [bmad, design-comuni, visual-parity, tailwind]
date: 2026-10-12
---

# STORY: Visual parity 95%+ — Design Comuni test pages

## Goal

Portare le pagine test `http://192.168.1.40:8000/it/tests/*` al 95%+ di visual parity con
`https://italia.github.io/design-comuni-pagine-statiche/`. Stack: TailwindCSS + Alpine.js + Lit +
DaisyUI + Flowbite (NO Bootstrap Italia). Metodo: BMAD + second brain.

## Page map

| Local URL | Reference |
|-----------|-----------|
| /it/tests/ | index.html |
| /it/tests/homepage | sito/homepage.html |
| /it/tests/segnalazione-dettaglio | sito/segnalazione-dettaglio.html |
| /it/tests/segnalazioni-elenco | sito/segnalazioni-elenco.html |
| /it/tests/segnalazione-area-personale | sito/segnalazione-area-personale.html |
| /it/tests/segnalazione-01-privacy | sito/segnalazione-01-privacy.html |
| /it/tests/segnalazione-02-dati | sito/segnalazione-02-dati.html |
| /it/tests/segnalazione-03-riepilogo | sito/segnalazione-03-riepilogo.html |
| /it/tests/segnalazione-04-conferma | sito/segnalazione-04-conferma.html |

## Reference page structure (homepage)

Reference homepage (`sito/homepage.html`) has these sections:

1. **head-section** — NEWS CARD (not hero): hidden h2 "Contenuti in evidenza", container > row >
   col-lg-6 (order-2 order-lg-1) with card (category-top: icon+Notizie+date, card-title link,
   lora paragraph, chip "Estate in città", read-more "Tutte le novità") + col-lg-6 (order-1
   order-lg-2) with image `https://picsum.photos/800/600`.
2. **calendario** — GOVERNMENT CARDS (3 card-teaser): Organi di governo (Mario Rossi / La giunta
   comunale / Il consiglio comunale) with read-more "Vai alla pagina", then "Eventi" h2 with
   calendar carousel (splide).
3. **evidence-section** — Argomenti in evidenza (h2 text-white), 3 card-title + h3 text-uppercase
   title-xsmall-bold text-secondary, then h2 + siti tematici (3 card-title text-white sito-tematico).
4. **useful-links-section** — Link utili.
5. **rating/feedback** — title-medium-2-semi-bold data-element="feedback-title" + title-medium-2-bold
   id="rating-feedback" + step-title with drop-shadow.
6. **contacts** — title-medium-2-semi-bold.

## Key color tokens (from reference CSS)

- Primary green: #007a52 (bg-[#007a52])
- Dark green: #006b4f (evidence-section bg)
- Dark blue: #17324d (text)
- Muted blue: #334e68
- Grey: #5d7083
- Light grey: #d9e2e8 (borders)
- Section muted: #f5f7f9 (backgrounds)
- Light section: #eef0f2

## Tasks

- [ ] Analyze each reference page block-by-block
- [ ] Document data structure for each block (JSON schema)
- [ ] Update JSON content files to match reference
- [ ] Rewrite Blade components with Tailwind to match reference visuals
- [ ] Verify parity with Playwright screenshots
- [ ] PHPStan + Pint validation

## Verification

- curl diff per page
- Playwright screenshot comparison
- PHPStan level 10 zero errors on modified files
- Pint clean
