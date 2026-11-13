---
title: "personal-area shared nav tabs contract"
type: concept
tags: [sixteen, area-personale, design-comuni, dry, bmad]
created: 2026-10-22
updated: 2026-10-22
qmd: "area personale cmp-nav-tab shared heading pratiche seguite notifiche impostazioni parity chrome"
related:
  - ../../bmad/knowledge/why-personal-area-tabs-must-be-identical.md
  - ../../bmad/stories/dcp-110-area-personale-pratiche-e-seguite.story.md
  - ../../bmad/stories/dcp-111-area-personale-notifiche-e-impostazioni.story.md
  - ../../bmad/filament-blueprint.md
  - ../../rules/reusable-components.md
status: draft
---

# Contratto: tab area personale identici su tutte le pagine

## Perché (senso)

L'area personale è **un solo luogo** con **quattro destinazioni** (pratiche, seguite, notifiche, impostazioni). I tab Design Comuni (`cmp-nav-tab`) non sono decorazione di una pagina: sono la **mappa dell'area**. Se l'utente vede un tab su `/seguite`, quel tab deve esistere — stesso set, stesso placement — anche su `/notifiche` e `/impostazioni`. Diversamente la mappa mente.

## Regola (DRY + KISS)

1. **Un solo chrome**: `components/blocks/personal-area/heading.blade.php` (briciole + h1 + CTA opzionale) include `personal-area/navigation.blade.php`.
2. **Un solo placement**: la nav sta in `col-12 p-0` sotto il titolo (pattern DCP-110 / riferimento `segnalazione-area-personale`), non dentro `col-lg-10` duplicato.
3. **Quattro link veri** via `FrontofficeRoutes::personalArea*()`, `aria-current="page"` sulla voce attiva, niente `role="tab"` (sono pagine, non pannelli).
4. **Vietato** reincludere `navigation` fuori dall'heading nelle pagine settings.

## Verifica

```bash
cd laravel && APP_ENV=testing DB_CONNECTION=sqlite ./vendor/bin/pest \
  Themes/Sixteen/tests/Unit/PersonalAreaNavigationContractTest.php \
  Modules/Fixcity/tests/Feature/Pages/PersonalAreaPagesTest.php \
  Modules/Fixcity/tests/Feature/Pages/PersonalAreaSettingsPagesTest.php \
  --filter='four personal|same four|share the same'
```

Il feature test deve passare su **pratiche, seguite, notifiche, impostazioni** (non solo le prime due).
