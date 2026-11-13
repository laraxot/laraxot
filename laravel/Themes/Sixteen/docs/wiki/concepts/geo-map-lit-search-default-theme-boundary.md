---
title: "geo map lit search default theme boundary"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "geo map lit search default theme boundary"
issues: []
discussions: []
status: draft
---

# GeoMapLit Search Default Theme Boundary

## Decisione

Nel tema Sixteen l'integrazione della mappa elenco segnalazioni non passa `show-search` a `<geo-map-lit>`.

La ricerca indirizzo e' default del componente owner Geo.

## Boundary

Sixteen deve montare e dimensionare il componente:

```blade
<geo-map-lit
    id="ticket-map"
    data-url="/data/tickets.json"
    height="clamp(360px,58vh,560px)"
></geo-map-lit>
```

Il tema non decide se la search esiste: evita varianti inutili e drift tra pagine.

## Verifica

Quando si modifica `/it/tests/ticket-list`:

- controllare desktop, tablet e mobile;
- verificare che la search sia visibile;
- verificare che fullscreen non rompa layout e controlli;
- verificare che cluster e marker restino cliccabili.
