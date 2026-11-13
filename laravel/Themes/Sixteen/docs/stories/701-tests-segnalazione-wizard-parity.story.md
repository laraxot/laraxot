---
id: "sixteen-fo-tests-segnalazione-wizard"
type: "story"
title: "Tests DesignComuni: complete segnalazione wizard parity (4 steps + elenco + area personale)"
status: in-progress
parent: STORY-701
tags: [design-comuni, segnalazione, wizard, tailwind, alpine, parity, tests-pages]
github_issue: "https://github.com/italia/design-comuni-pagine-statiche/issues"
github_discussion: "https://github.com/italia/design-comuni-pagine-statiche/discussions"
design_ref: "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html"
created: 2026-10-11
updated: 2026-10-11
---

# Story: Tests DesignComuni — Segnalazione Wizard Parity

## Goal

Create JSON content + Tailwind blade components for all `tests.segnalazione-*` pages
so they visually match `design-comuni-pagine-statiche` segnalazione wizard:

| URL local | Reference | Blade component |
|---|---|---|
| `/it/tests/segnalazione-privacy` | `segnalazione-01-privacy.html` | `flow/segnalazione/01-privacy.blade.php` |
| `/it/tests/segnalazione-dati` | `segnalazione-02-dati.html` | `flow/segnalazione/02-dati.blade.php` |
| `/it/tests/segnalazione-riepilogo` | `segnalazione-03-riepilogo.html` | `flow/segnalazione/03-riepilogo.blade.php` |
| `/it/tests/segnalazione-conferma` | `segnalazione-04-conferma.html` | `flow/segnalazione/04-conferma.blade.php` |
| `/it/tests/segnalazioni-elenco` | `segnalazioni-elenco.html` | `segnalazioni-elenco/default.blade.php` |
| `/it/tests/segnalazione-area-personale` | `segnalazione-area-personale.html` | `segnalazione-area-personale/default.blade.php` |

## Completed

- [x] Converted `01-privacy.blade.php` from Bootstrap Italia to TailwindCSS + Alpine.js
- [x] Converted `02-dati.blade.php` from Bootstrap Italia to TailwindCSS + Alpine.js
- [x] Converted `03-riepilogo.blade.php` from Bootstrap Italia to TailwindCSS + Alpine.js
- [x] Converted `04-conferma.blade.php` from Bootstrap Italia to TailwindCSS + Alpine.js
- [x] Created `tests.segnalazione-privacy.json` content config
- [x] Created `tests.segnalazione-dati.json` content config
- [x] Created `tests.segnalazione-riepilogo.json` content config
- [x] Created `tests.segnalazione-conferma.json` content config
- [x] Created `tests.segnalazioni-elenco.json` content config
- [x] Created `tests.segnalazione-area-personale.json` content config
- [x] Created `segnalazioni-elenco/default.blade.php` (Tailwind, no BS Italia)
- [x] Created `segnalazione-area-personale/default.blade.php` (Tailwind, no BS Italia)
- [x] Verified header.json structure matches Twig template expectations
- [x] Moved all blade files from `resources/view/` to `resources/views/`
- [x] Pint PSR-12: all files formatted
- [x] PHPStan: no new errors introduced
