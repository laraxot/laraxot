---
title: "Sixteen — roadmap completamento tema FO"
type: overview
tags: [sixteen, theme, roadmap, design-comuni, testing]
created: 2026-06-13
updated: 2026-06-13
qmd: "Sixteen tema completamento Design Comuni componenti test PHPStan segnalazioni"
issues:
  - "https://github.com/laraxot/module_fixcity_fila5/issues/52"
discussions:
  - "https://github.com/laraxot/module_fixcity_fila5/discussions/53"
related:
  - ../concepts/theme-component-test-contract.md
  - ../concepts/phpstan-compliance.md
  - ../../../Modules/Fixcity/docs/wiki/overviews/completion-roadmap.md
---

# Sixteen — roadmap completamento tema FO

## Ruolo nel progetto

Sixteen è il tema **owner** del front office Fixcity: Folio, Design Comuni, wizard segnalazioni, mappe. I test del modulo UI verificano file sotto `Themes/Sixteen/resources/views/components/`.

## Stato (2026-06-13)

| Area | Stato |
|------|-------|
| PHPStan tema `Themes/Sixteen` | ⚪ Escluso da `phpstan.neon` `paths` (solo `Modules/`) |
| Test indiretti via UI module | ✅ Aggiornati (`ComponentFilesExistTest`, wizard view tests) |
| Design Comuni parity | 🔄 Vedi [visual-comparison](../../design-comuni/visual-comparison/it-vs-segnalazioni-elenco.md) |

## Completato (sessione correlata)

- Test UI: skip esplicito se legacy components presenti (no dead code PHPStan)
- Contract documentato: [theme-component-test-contract.md](../concepts/theme-component-test-contract.md)

## Priorità completamento

### P0 — Shell pagina Folio

- [ ] Audit `bashscripts/tools/audit-folio-page-shell.sh Sixteen`
- [ ] `<x-page :data="$data">` opaque bag su tutte le route ticket/segnalazioni
- [ ] No `route()` nel FO — solo URL localizzati

### P1 — Design Comuni

- [ ] HTML parity elenco segnalazioni (target >90%)
- [ ] Map popup allineato a `popup-ticket.js` (modulo Geo)
- [ ] CSS token in `resources/css/`, non inline nei moduli

### P2 — Asset e build

- [ ] `npm run build` theme dopo change JS mappe
- [ ] Verifica `map-lit-*.js` in manifest Vite

### P3 — Test

- [ ] Estendere test Playwright FO (login, crea segnalazione, lista)
- [ ] Quando PHPStan includerà Themes: tipizzare eventuali classi PHP sotto `Themes/Sixteen/app/`

## Collegamenti modulo Fixcity

Roadmap dominio: [Fixcity completion-roadmap](../../../Modules/Fixcity/docs/wiki/overviews/completion-roadmap.md)

Hub piattaforma: [platform-completion-roadmap](../../../Modules/Xot/docs/wiki/overviews/platform-completion-roadmap.md)
