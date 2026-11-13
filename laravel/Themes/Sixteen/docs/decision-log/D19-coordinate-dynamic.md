---
type: epic-story
github_issue: #104
decision: D19
title: "D19 — CoordinatePicker dinamiche (luogo reale dell'utente, NON fisso)"
status: draft
updated: 2026-10-14
tags: [docs, sixteen, decision-log]
qmd: "d19   coordinatepicker dinamiche (luogo reale dell'utente, non fisso) sixteen"
---
# D19 — CoordinatePicker dinamiche (luogo reale dell'utente, NON fisso)

**Filosofia**: "Uno apre per mettere il ticket dove si trova lui in quel momento" — il luogo del ticket è il luogo dell'utilizzatore, non un punto predefinito. Posso creare ticket sparsi nel mondo; la mappa deve centrarsi dove è l'utilizzatore.
**Logica**: `CoordinatePicker::make('location')` — NON `->center(43.7696, 11.2558)` (fisso). Invece: `->centerBasedOnUserLocation()` (geolocation se disponibile; fallback generale se negato/HTTP).
**Regola dinamica**: se record ha coordinate (lat/lng salvate) → usa quelle (per modifiche); se null → tenta la posizione utente (browser `getCurrentPosition`); se geolocation negata o non disponibile → mappa interattiva con ricerca/click, senza coordinate comunali inventate e senza messaggio HTTPS bloccante.
**Scopo UI/UX**: mappa centrata sull'utilizzatore; ticket posizionabile con un tap; ogni ticket legato a luogo reale.
**Normativa**: GDPR 2016/679 art. 9 (dati di geolocalizzazione — solo con consenso); CAD; AGID; Design Comuni v2.4.0; accessibilità (aria-label, focus-visible).
**Professionale**: contratti (`TicketContract`); BMAD doc (D19); no controller/service; `pub_theme::components.map-lit.blade.php` con `clamp` responsive; multilingua `__()`.
