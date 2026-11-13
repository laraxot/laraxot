---
id: "sixteen-fo-01-homepage-redesign"
type: "epic-story"
title: "Homepage redesign with Design Comuni"
description: "Riallineare la homepage (index.blade.php) del tema Sixteen ai pattern di design-comuni-pagine-statiche v2.4.0, mantenendo funzionalità esistente (mappa ticket, CTA segnalazione, login)."
frontmatter:
  github_issue: "#54"
  github_discussion: "#54"
links:
  github_issue: "#54"
  github_discussion: "#54"
  design_ref: "https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html"
  investigation_ref: "docs/investigation/homepage-design-comuni-investigation.md"
  segnalazione_ref: "https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-dettaglio.html"
---

# Homepage DesignComuni — Epic Story

## Goal
Riallineare la homepage (`index.blade.php`) del tema Sixteen ai pattern di `design-comuni-pagine-statiche` (v2.4.0) della Italia Developers team, mantenendo funzionalità esistente (mappa ticket, login, segnalazione).

## Background
- Referenze ufficiali: `https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html`
- Flusso segnalazione (4 step): `segnalazione-01-privacy` → `segnalazione-02-dati` → `segnalazione-03-riepilogo` → `segnalazione-04-conferma`
- Template condiviso: header `it-header-wrapper` / footer `it-footer` con sprite SVG `/themes/Sixteen/design-comuni/assets/bootstrap-italia/dist/svg/sprites.svg`
- Dati gestiti via `config/local/fixcity/database/content/pages/home.json` + componenti `<x-cms::page slug="home" />`

## Acceptance Criteria
- [ ] Header conforme a DesignComuni (`it-header-wrapper`, `it-header-slim-wrapper`, `it-nav-wrapper`, `it-brand-wrapper`, `it-search-wrapper`)
- [ ] Footer conforme a DesignComuni (`it-footer`, `it-footer-main`, `it-footer-list`, logo UE, 4 colonne)
- [ ] Skip link obbligatorio: "Vai ai contenuti" (#main-container) + "Vai al footer" (#footer)
- [ ] Hero section con titolo, sottotitolo, CTA primaria (Segnala disservizio) + secondaria (Tutte le segnalazioni) allineati al template
- [ ] Mappa ticket integrata nel layout hero (pattern `card border-0 shadow-sm` con `<map-lit>`)
- [ ] Sezione "Come funziona" (3 step) con icone numerate e `card card-bg`
- [ ] Quick-actions section con track CTA (`tickets.track` route)
- [ ] Accessibilità: `aria-labelledby`, `visually-hidden`, contrasto ≥4.5:1, `prefers-reduced-motion`
- [ ] Lingua italiana, consistent con `localizeURL`
- [ ] Dati da `home.json` (hero, map-preview, how-it-works, quick-actions blocks)

## Non-Scopi (Out of Scope)
- Migrazioni / schema DB (fuori frontend)
- API backend (`/api/tickets/geojson`) — resta invariata
- Componenti Blade esterni (`quick-actions.blade.php`) — fix separato

## Dependency
- Fonte: `docs/investigation/homepage-design-comuni-investigation.md`
- Componenti universali: `cmp-hero`, `cmp-card`, `cmp-stat-card`, `cmp-cta-section`, `cmp-page-section`