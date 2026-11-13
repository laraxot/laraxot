---
title: "Redesign homepage con DesignComuni italia/design-comuni-pagine-statiche"
type: epic-story
project: "Fixcity — Tema Sixteen"
priority: high
tags: [homepage, design-comuni, a11y, bmad]
links:
  github_issue: https://github.com/italia/design-comuni-pagine-statiche
  discussion: https://forum.italia.it/c/design
  story_ref: docs/investigation/homepage-design-comuni-investigation.md
  design_ref: https://italia.github.io/design-comuni-pagine-statiche/index.html
  segnalazione_ref: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-dettaglio.html
---

# Homepage DesignComuni — Epic Story

## Goal
Riallineare la homepage (`index.blade.php`) del tema Sixteen ai pattern di `design-comuni-pagine-statiche` (v2.4.0) della Italia Developers team, mantenendo funzionalità esistente (mappa ticket, login, segnalazione).

## Background
- Referenze ufficiali: `https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html`
- Flusso segnalazione (4 step): `segnalazione-01-privacy` → `segnalazione-02-dati` → `segnalazione-03-riepilogo` → `segnalazione-04-conferma`
- Template condiviso: header `it-header-wrapper` / footer `it-footer` con sprite SVG `/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg`

## Acceptance Criteria
- [ ] Header conforme a DesignComuni (`it-header-wrapper`, `it-header-slim-wrapper`, `it-nav-wrapper`)
- [ ] Footer conforme a DesignComuni (`it-footer`)
- [ ] Hero section con titolo, sottotitolo, CTA primaria secondaria allineati al template
- [ ] Mappa ticket integrata nel layout hero (pattern `card border-0 shadow-sm`)
- [ ] Sezione "Come funziona" (3 step) con icone numerate e `card card-bg`
- [ ] Quick-actions section con track CTA (`tickets.track`)
- [ ] Accessibilità: skip link "Vai ai contenuti", `aria-labelledby`, `visually-hidden`
- [ ] Lingua italiana, consistent con `localizeURL`

## Non-Scopi (Out of Scope)
- Migrazioni / schema DB (fuori frontend)
- API backend (`/api/tickets/geojson`) — resta invariata
- Componenti Blade esterni (`quick-actions.blade.php`) — fix separato

## Dependency
- Fonte: `docs/investigation/homepage-design-comuni-investigation.md`