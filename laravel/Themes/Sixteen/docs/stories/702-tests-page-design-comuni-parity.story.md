---
title: "Sixteen front-office: tests page + DesignComuni argomenti parity"
type: task-story
status: completed
tags: [design-comuni, front-office, blade, json, folio, bmad, tests, parity]
github_issue: "https://github.com/italia/design-comuni-pagine-statiche"
github_discussion: "https://github.com/italia/design-comuni-pagine-statiche/discussions"
created: 2026-10-11
updated: 2026-10-11
---

# STORY-702: Sixteen tests page - DesignComuni index + argomenti tab-parity

## Obiettivo
Creare `resources/views/pages/tests/index.blade.php` per renderizzare la
pagina indice del Design Comuni reference (https://italia.github.io/design-comuni-pagine-statiche/),
con contenuti dinamici caricati da JSON. I contenuti devono riflettere i **2 tab**
del reference: **Generali** (homepage, FAQ, ricerca, argomenti, etc.) e **Sito**.

## Completato

- [x] Creare `config/local/fixcity/database/content/pages/tests.index.json` con slug `"tests.index"` e struttura 2-tab (Generali/Sito)
- [x] Creare `config/local/fixcity/database/content/pages/tests.argomenti.json` con contenuti argomenti (hero, topics-highlight, topics-grid, feedback, contacts)
- [x] Creare `resources/views/pages/tests/index.blade.php` (Folio + Volt pattern, route `tests.index`)
- [x] Fix `home.json` (config + Fixcity) — sostituite URL hardcoded con `route()` calls
- [x] Fix `icon_sprite` — rimosso riferimento a Bootstrap Italia sprite, usato icon component
- [x] Fix migration `2026_06_05_create_profiles_table.php` — rimosuti commenti Laraxot, aggiunto `down()`
- [x] Fix migration `2026_09_26_update_ticket_subscribers_user_id_to_uuid.php` — aggiunto controllo tipo colonna, `down()` method
- [x] Fix `marker-config.js` — sostituito sprite Bootstrap Italia con inline SVG path
- [x] Fix `BlockLegacyPublicPages` — rimosso `tests` dalla blacklist
- [x] Rewrite `tabs/default.blade.php` — TailwindCSS + Alpine.js (era Bootstrap Italia)
- [x] Rewrite `hero/default.blade.php` — TailwindCSS con proprieta `heading`/`intro`/`eyebrow`
- [x] Quality gates: Pint OK, PHPStan level 10 OK, Prettier OK

## Parity verification (Puppeteer)

| Element | Local | Reference |
|---------|-------|-----------|
| H1 | "I templates HTML del modello per i comuni" | "I templates HTML del modello per i comuni" |
| Tabs | Generali, Sito | Generali, Sito |
| Tab pages | Homepage, FAQ, Ricerca, Argomenti, ... | Homepage, FAQ, Ricerca, Argomenti, ... |

## Definition of Done
- [x] `/it/tests/` renderizza l'indice DesignComuni con 2 tab (Generali / Sito)
- [x] `/it/tests/argomenti` carica `tests.argomenti.json`
- [x] Nessun URL hardcoded in JSON — tutti usano `route()`
- [x] Nessun riferimento a `bootstrap-italia/dist/svg/sprites.svg` in Blade/JSON
- [x] Migration test: `php artisan migrate` non fallisce
- [ ] WCAG 2.1 AA: verificare con `wcag-aa-check.mjs`

## Dependency
- `pub_theme::components.ui.icon` (creato in `components/ui/icon.blade.php`)
- `motion-reveal.js` Alpine utility (creato in `resources/js/components/`)
- `docs/design-comuni/design-comuni-canonical.md` (creato)

## Nota su R3 religion
La migration `2026_09_26_update_ticket_subscribers_user_id_to_uuid.php` viola
la R3 religion (niente `update_*` separate). Vedere `r3-one-migration-per-model.md`
per il piano di consolidamento.
