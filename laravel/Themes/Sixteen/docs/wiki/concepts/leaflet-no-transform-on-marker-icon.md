---
title: "Map-lit cluster fix — lezione cascade + lezioni operative multi-repo"
type: concept
confidence: high
created: 2026-06-03
updated: 2026-06-03
tags: [leaflet, markercluster, css-cascade, lit, light-dom, git, multi-repo, STORY-123]
related:
  - marker-cluster-hover-stability.md
  - leaflet-z-index-layering.md
---

# Map-lit cluster fix — cascade + lezioni operative

> **DRY**: la root cause CSS e il fix tecnico vivono già in
> [`marker-cluster-hover-stability.md`](./marker-cluster-hover-stability.md)
> (regola: **mai** `transform`/`transition: transform` su `.leaflet-marker-icon`).
> Qui si distillano due lezioni *trasversali* emerse durante STORY-123 che non
> appartengono al solo CSS: **la cascade light-DOM vs tema** e **la disciplina
> git multi-repo**.

## Lezione 1 — dove cercare la regressione (cascade light-DOM vs tema)

`<map-lit>` è un web component a **light DOM**: le sue regole interne
(`Modules/Geo/.../map/styles.js`) **non** sono isolate in shadow DOM e competono
nella cascade globale. Il CSS di tema **bundlato** in `app.css`
(`resources/css/app/07-map-clusters-and-leaflet.css`) usa `!important` e
**vince** sul light-DOM.

> **Regola di debug**: per una regressione visiva su un componente Lit a light
> DOM, ripulire il `styles.js` interno **non basta** — controllare **sempre**
> anche il CSS di tema bundlato con `!important`. La SSoT dello stile mappa per
> il front-office è nel tema Sixteen; il light-DOM dà solo i default.

Concretamente in STORY-123: `styles.js` era già pulito, ma
`07-map-clusters-and-leaflet.css` conteneva ancora
`.geo-cluster-wrapper:hover { transform: scale(1.1) !important }` → il fix andava
applicato nel tema.

## Lezione 2 — disciplina git multi-repo (come sono stati risolti gli errori)

Il fix tocca **tre repo git annidati** indipendenti: root `base_fixcity_fila5`,
tema `Themes/Sixteen` (remote `laraxot`), modulo `Modules/Geo` (remote
`laraxot`). Errori di processo emersi e correzioni:

1. **"mergiato" dichiarato prima del commit**: i file del fix di Geo
   (`map-lit.js`, `map/styles.js`, `map-lit.blade.php`) risultavano *modified ma
   non committati* mentre lo stato pubblico (issue/discussion) diceva già
   "mergiato su `dev`".
   → **Verificare committed *e* pushed su ogni repo annidato** prima di
   dichiarare completato. "Done" si dà solo dopo verifica reale.
2. **Commit mirato, non `git add -A`**: nei working tree c'erano file non-miei
   di altre sessioni → committare **solo** i file della story, per nome.
3. **Push divergente**: i remote `laraxot/dev` erano avanti (tema ~20, Geo ~17
   commit). La riconciliazione (merge forward-only) va fatta a working tree
   pulito, **senza** forzare sopra file untracked altrui.

## Riferimenti

- Runbook Geo (module-side): [`map-lit-cluster-hover-escape-fix.md`](../../../../../Modules/Geo/docs/wiki/troubleshooting/map-lit-cluster-hover-escape-fix.md)
- Story: `docs/stories/STORY-123-map-lit-cluster-hover-escape-fix.md`
