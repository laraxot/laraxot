---
title: SettingPage di Rating, pagina impostazioni senza campi
qmd: "Rating SettingPage configurazione tenant BaseSettingPage Filament"
status: in-progress
module: Rating (owner)
created: 2026-11-04
related:
  - ../../../Xot/docs/bmad/stories/setting-page-tutti-i-moduli.story.md
---

# SettingPage di Rating

## Fase BMAD

Build. Decisione dell'utente (2026-11-04): la pagina Impostazioni va in tutti i moduli. Storia
di decisione in Xot: `Modules/Xot/docs/bmad/stories/setting-page-tutti-i-moduli.story.md`.

## Intento

Il super-admin trova la voce Impostazioni in `Rating`, su `/rating/admin/setting`. Il form e' vuoto:
il modulo non ha ancora valori da cambiare senza deploy.

## File

- `app/Datas/RatingConfigData.php`: Data di configurazione, senza proprieta', legge `config/{tenant}/rating.php`.
- `app/Filament/Pages/SettingPage.php`: estende `BaseSettingPage`, `getFormSchema()` vuoto.
- `lang/it/setting.php`, `lang/en/setting.php`: navigazione, titolo, azioni.
- `tests/Unit/Datas/RatingConfigDataTest.php`: convenzione nome e file tenant.

## Verifica

- Test: vedi il riepilogo di Xot (`setting-page-tutti-i-moduli.story.md`).
