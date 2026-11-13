---
id: "sixteen-fo-tests-domande-frequenti-parity"
type: "story"
title: "Tests DesignComuni: /it/tests/domande-frequenti (FAQ) parity"
status: todo
parent: STORY-705
tags: [design-comuni, faq, tailwind, alpine, parity, tests-pages, bmad]
design_ref: "https://italia.github.io/design-comuni-pagine-statiche/sito/domande-frequenti.html"
local_route: "http://192.168.1.40:8000/it/tests/domande-frequenti"
content_json: "laravel/config/local/fixcity/database/content/pages/tests.domande-frequenti.json"
blade_component: "laravel/Themes/Sixteen/resources/views/components/blocks/faq/default.blade.php"
created: 2026-10-13
updated: 2026-10-13
---

# Story — Tests /it/tests/domande-frequenti (FAQ) parity

## Obiettivo
Portare `/it/tests/domande-frequenti` al minimo 90% body HTML parity e 97% visual parity con la reference Design Comuni.

## Stato attuale
- Componente blade `faq/default.blade.php`: ESISTENTE (accordion Alpine, `questions` array, `icon`, `x-data` toggle).
- JSON `tests.domande-frequenti.json`: AGGIORNATO con 4 Q/A reali (accesso servizi, pagamenti, certificato residenza, contatti).
- Stack: Tailwind + Alpine + Lit + DaisyUI + Flowbite (NO Bootstrap Italia).
- Gate: 90% HTML / 97% visual.

## Task
- [x] Verificare componente blade `faq/default.blade.php`.
- [x] Aggiornare JSON con contenuti reali.
- [ ] Verificare rendering locale (`/it/tests/domande-frequenti`) con screenshot a 375/768/1440.
- [ ] Aggiornare BMAD log (docs/wiki/log.md) con decisione.

## Verifica
```bash
node bashscripts/tools/visual/compare-body-html.mjs \
  https://italia.github.io/design-comuni-pagine-statiche/sito/domande-frequenti.html \
  http://192.168.1.40:8000/it/tests/domande-frequenti --json
```

## Storia nuova iniziativa
Iniziativa tracciata con `hindsight_capture_initiative`: `kp-7f8e85f1d7d34532bcd1e7de08753ce2` (FAQ domande-frequenti).
