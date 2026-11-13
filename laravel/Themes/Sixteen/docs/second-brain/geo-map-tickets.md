---
title: "Geo map + ticket wizard fixes — 2026-10-14"
type: decision
tags: [second-brain, map-marker, geolocation, wizard, design-comuni, wcag]
qmd: "geo map ticket wizard marker button geolocation fix 2026-10-14"
status: draft
updated: 2026-10-14
---

# Geo Map + Ticket Wizard — Fixes 2026-10-14

Three parallel swarm fixes on the map + wizard area. Each is a Design Comuni
compliance issue, not a cosmetic tweak.

## 1. Marker visibility (STORY-054)
**Symptom**: "nero piccolo con un punto di domanda a fianco" — black small marker
with a question-mark overlay.
**File**: `Themes/Sixteen/resources/views/tickets/index.blade.php` (line ~219)
**Fix**: SVG Design Comuni green `#007A52`, size `36×48`, white inner circle
with `#007A52` stroke, `aria-label="Segnalazione sul territorio"`,
`role="img"`, `aria-hidden="false"`, no `?` overlay.

## 2. Map buttons transparent (STORY-XXX)
**Symptom**: buttons inside the map had `bg-transparent` → invisible.
**File**: `Modules/Geo/resources/js/components/map/controls/geolocation.js`
(`renderButton`, line ~99)
**Fix**: `ctrl-btn` now has inline Design Comuni styling:
- `background-color:#007a52`
- `color:#fff`
- `border:none`
- `border-radius:6px`
- `padding:0.6rem 0.75rem`
- `box-shadow:0 2px 6px rgba(0,122,82,0.3)`
- `@mouseover` → `#005c3d` + `translateY(-1px)`
- `@mouseout` → `#007a52` + `translateY(0)`
- `@focus-visible` → `outline:2px solid #005c3d` + `outlineOffset:2px`
- `cursor:pointer`

## 3. Geolocation not working (STORY-530)
**Symptom**: "usa la posizione corrente non funziona".
**Root cause**: `window.isSecureContext === false` on HTTP
(`APP_URL=http://fixcity.local` in `.env`). `navigator.geolocation` is
disabled on non-secure origins.
**File**: `Modules/Geo/resources/js/components/map/controls/geolocation.js`
**Fix**: `fallBackToApproximate` already present and triggered on
`!isSecureContext`, `!navigator.geolocation`, and geolocation errors.
Documented HTTPS requirement for production
(`FORCE_HTTPS=true` in `.env.production.example`).

## Design Comuni rules applied
- Marker: green PA `#007A52`, size `36×48`, accessible `aria-label`.
- Buttons: primary accent `#007a52` background, proper hover/focus-visible,
  elevation via shadow. No `bg-transparent` on interactive elements.
- No `?` icon overlay on markers.

## Second brain
Cross-link with Fixcity module: `Fixcity/docs/second-brain.md` §8,
`Fixcity/docs/bmad/stories/STORY-054-ticket-marker-visibility.md`,
`Fixcity/docs/bmad/stories/STORY-XXX-wizard-map-buttons.md`,
`Fixcity/docs/bmad/stories/STORY-530-geolocation-not-working.md`.