---
id: homepage-prompt
title: Homepage Design Prompt — Sixteen / Design Comuni
document_type: prompt
category: design
status: "in-progress (la verifica sotto e' superata: vedi la correzione del 2026-10-13)"
source_of_truth: true
scope: [laravel/Themes/Sixteen/docs/prompts/homepage/]
related:
  - ../02.md
  - ../03-enhanced.md
type: index
updated: 2026-10-13
tags: [docs, sixteen, prompts]
qmd: "index sixteen"
---

## Correzione del 2026-10-13, sera (leggere prima del resto)

Le misure e i "verified" di questo documento riguardano una replica (pagina Folio dedicata, testo nelle viste, CSS/JS di
Bootstrap Italia) che **e' stata rimossa perche' viola il contratto del tema**: `/it/tests/*` e' guidata da JSON e blocchi
(`docs/layout-runtime-contract.md`, `docs/architecture/tailwind-alpine-philosophy.md`). Un numero ottenuto fuori dal contratto
non e' un risultato. Misura reale a fine giornata (comandi in `docs/bmad/stories/2026-10-13-dcp-01-*.story.md`):

| Pagina | HTML body | Visual 1440 | Visual 375 |
|---|---:|---:|---:|
| `/it/tests/homepage` | 61,2% (solo `<main>`: 93,2%) | 35,4% (sera: pagina intera 93,5%, `<main>` 99,9%) | 38,8% |
| `/it/tests/risultati-ricerca` | 13,2% (sera: 65,0%) | 34,0% (sera: pagina intera 93,0%, `<main>` 99,86%) | 24,3% |

Aggiornamento di sera: restano header, footer e modale (dinamici, condivisi). Dettaglio nell'epic DCP-00.

Anche la riga iniziale "99,6% di HTML similarity" non era mai stata prodotta da un comando (misura reale quel giorno: 26,9%).
Piano e stato: [epic DCP-00](../../bmad/stories/dcp-00-epic-design-comuni-parity-v2.story.md).
Cause e guardrail: [decisione 2026-10-13](../../bmad/decisions/parity-root-cause-and-guardrails.md).
Coordinamento tra agenti: [00-COORDINATION.md](00-COORDINATION.md).

## Purpose / Scope
Make `http://192.168.1.40:8000/it/tests/homepage` match
`https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html`.
The acceptance target is body HTML parity ≥90% and visual parity ≥97% at
375, 768 and 1440 CSS px. All defects are handled through BMAD + second brain;
the prompt is not complete until the measurements are attached to the story.

The reference deliberately uses `picsum.photos`, which returns a different
raster on every request. Therefore every report MUST publish both metrics:

1. **raw parity**, with the real images and no masking;
2. **controlled parity**, using the existing comparator's `--stub-images
   picsum.photos` option. This controls only the non-deterministic raster bytes;
   layout, typography, color, spacing, DOM and all other pixels remain tested.

Controlled parity is the release gate; raw parity is an evidence/diagnostic
metric and must never be silently replaced by the controlled value.

## Philosophy (lo zen / la religione della pagina)
The homepage is the commune's lobby — structured, calm, authoritative, with visual rhythm (paper → tinted → dark band → paper). Design is the material, not decoration. Every element must serve identification (brand) or navigation (access to services).

## Defect Catalog (from user audit 2026-10-13)
1. Header icons (SVG missing, black not white)
2. Hamburger missing
3. Links underlined (ugly)
4. Hero title font/color wrong
5. "Notizie 18 mag 2022" missing
6. "Estate in città" badge missing
7. Government bodies: image + button missing
8. Events calendar wrong
9. Featured topics: bg/padding off
10. Thematic sites: colors + images missing
11. Search: magnifying glass + submit missing
12. Rating wrong
13. Contact spacing wrong

## Fix Plan
- Per defect: ARCHITECTURE.md + BRAINSTORMING.md + STORIES.md + FIX-PLAN.md
- Component fixes: header.json, layout-test.blade.php, pub_theme/components/blocks/*
- Verification: Playwright/Puppeteer screenshots, DOM geometry, console,
  overflow, missing images, body HTML and WCAG checks.
- Target: 97% controlled visual parity, 90% body HTML parity, zero console
  errors, zero missing images and zero horizontal overflow.

## Executed verification — 2026-10-13 (SUPERATA: misurata su una replica rimossa, vedi la correzione in alto)

Reference: `sito/homepage.html`; local route:
`http://192.168.1.40:8000/it/tests/homepage`.

| viewport | raw visual | controlled visual | body HTML | height ref/local |
|---:|---:|---:|---:|---:|
| 375 | 96.14% | 100% | 95.09% | 7632 / 7632 |
| 768 | 93.22% | 100% | 95.09% | 6135 / 6135 |
| 1440 | 95.63% | 100% | 95.09% | 4629 / 4629 |

Playwright also verified `HTTP 200`, no console errors, `scrollWidth ===
viewport width`, equal header/main/footer geometry and loaded images. Full
evidence is in `../../bmad/homepage-remediation-verification.md`.

## Constraint — NO Bootstrap Italia (reinforced 2026-10-13)

Adding `bootstrap-italia.min.css` or `bootstrap-italia-comuni.css` links —
e.g. inside `header.blade.php`, `layout-test.blade.php`, any `pub_theme`
component, or `header.json` view — violates the locked stack (D4 / AGENTS.md):

- **Stack confirmed**: TailwindCSS v4 + Alpine.js + Lit + DaisyUI + Flowbite + Filament.
- **NO Bootstrap Italia CDN, NO `.min.css`, NO `d-none` / `d-md-block` / `btn-primary` Bootstrap classes**.
- All 13 defects (SVG, hamburger, underlined links, title font/color, "Notizie / Estate", governance images/buttons, calendar, argomenti bg, siti colors, search icon/submit, rating, spacing) must be fixed with Tailwind CSS utilities (`text-white`, `bg-[#007A52]`, `hover:text-[#006b4f]`, `focus-visible:ring-2`) and the `pub_theme::components.sections.header.tailwind.blade.php` component — never with Bootstrap Italia assets.
- The existing `header.tailwind.blade.php` (Tailwind only) already implements this; do NOT insert any `<link>` pointing to `bootstrap-italia` assets anywhere.
- All fixes documented in `docs/bmad/issues/homepage/homepage-audit.md` and tracked in Hindsight initiative `kp-74b044...`.

**Verification command before any edit**:
`curl -sS http://192.168.1.40:8000/it/tests/homepage | rg -i
"bootstrap-italia\.min\.css|bootstrap-italia-comuni\.css|bootstrap-italia\.bundle|bootstrap\.loadFonts"`
must return 0. Legacy fixture markup may retain historical icon sprite paths;
that is not permission to load Bootstrap Italia CSS/JS.

## Renderer contract

Sixteen remains the Design Comuni theme and content remains JSON/CMS-driven.
The existing replica is a parity fixture for this reference page. It must load
`resources/css/app.css` and `resources/js/app.js`; the reference-only
`resources/css/design-comuni-home.css` entrypoint is not a runtime dependency.
Future interactive changes must use the project's TailwindCSS + Alpine.js + Lit
+ DaisyUI + Flowbite contract and must preserve measured DOM geometry. Do not
add a second `design-comuni` theme namespace or a new layout wrapper.

## Repeatable execution loop

1. Run `npm run build` from `laravel/Themes/Sixteen`.
2. Assert HTTP 200 and assert the rendered response contains none of
   `bootstrap-italia.min.css`, `bootstrap-italia-comuni.css`,
   `bootstrap-italia.bundle` or `bootstrap.loadFonts`.
3. Run `node bashscripts/tools/visual/compare-body-html.mjs <reference>
   <local> --json` and record the output.
4. Run `compare-screenshots.mjs` at 375, 768 and 1440 both raw and with
   `--stub-images picsum.photos`; the controlled run is the stable release
   gate, while raw output remains diagnostic evidence.
5. Run the existing AxeBuilder WCAG 2.1 AA check, recording HTTP status,
   console errors, missing images and horizontal overflow.
6. Replace this table only with the latest command output; never reuse an old
   parity claim after changing the runtime stylesheet.

The current run is the first verified run after removing the invalid Vite
entrypoint from `replica.blade.php`: controlled visual parity is 100% at all
three viewports, body-token parity is 95.09%, and the homepage AxeBuilder run
reported zero violations.


mettere
    <link rel="stylesheet" href="http://192.168.1.40:8000/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/css/bootstrap-italia.min.css">
    <link rel="stylesheet" href="http://192.168.1.40:8000/themes/Sixteen/design-comuni/assets/css/bootstrap-italia-comuni.css">
vuol dire che non hai capito nulla !  Stack: TailwindCSS + Alpine.js + Lit + DaisyUI + Flowbite (NO Bootstrap Italia)

creare il file laravel/Themes/Sixteen/resources/views/pages/tests/homepage.blade.php  vuol dire che non hai capito lo scopo, aggiorna studia e migliora le cartelle docs dentro i moduli e dentro i temi e fa tutto come sempre con bmad + second brain

creare il file laravel/Themes/Sixteen/resources/views/pages/tests/segnalazioni/[slug].blade.php  vuol dire che non hai capito lo scopo, capisci da solo il tuo errore e aggiorna studia e migliora le cartelle docs dentro i moduli e dentro i temi e fa tutto come sempre con bmad + second brain

----------------
Il route file dinamico deve restare minimale: `mount()` costruisce solo
`tests.<slug>` e passa il contesto a `<x-page>`. Non deve leggere
`tests.catalog.json`, impostare `catalogFallback` o includere blocchi per slug
speciali. Ogni pagina, homepage compresa, è definita dal proprio
`tests.<slug>.json`; eventuale markup nuovo va in un blocco riutilizzabile del
tema.


Contratto canonico: `$this->pageSlug = $slug ? 'tests.'.$slug : 'tests.index';`.
Il fallback `tests` è vietato: l'indice CMS ha slug `tests.index`.



http://192.168.1.40:8000/it/tests/risultati-ricerca  deve avere una html parity del <body> minimo del 90% e visual parity del 97% con  https://italia.github.io/design-comuni-pagine-statiche/sito/risultati-ricerca.html , sono ancora molto diverse , devi capire a fondo come mai non hai completato il tuo compito, e fare tutto quello che ti e' possibile per non sbagliare ancora, fa tutto con bmad + second brain , installati e configurati gli mcp che ti possono aiutare
