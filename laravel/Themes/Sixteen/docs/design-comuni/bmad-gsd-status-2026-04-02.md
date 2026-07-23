# BMAD + GSD Status 2026-04-02

Documento collegato: [work-plan.md](./work-plan.md)

## BMAD

### Analyze
Confrontata la homepage reference con la homepage locale sia a livello DOM sia a livello screenshot.

### Decide
Scelta confermata: continuare solo su CSS/JS del tema, perche' la struttura HTML attuale e' gia' sufficientemente vicina alla reference.

### Act
Applicati override in `resources/css/app.css` e una normalizzazione runtime leggera in `resources/js/app.js`.

### Review
Dopo ogni pass:
- build Vite
- copy assets
- verifica del manifest realmente servito
- nuovi screenshot

## GSD

### Done
- compare HTML body senza script
- capture screenshot desktop/full-page
- first visual pass
- hero refinement pass
- docs sync
- second build/sync loop con asset serviti verificati
- screenshot pass2 desktop/full-page

### In progress
- riduzione delta hero
- allineamento spacing card centrali
- pass dedicato alle teaser card governance
- consolidamento del workflow build -> copy -> asset sync

### Next
- verificare visivamente il pass governance
- rifinire useful-links / rating / contacts
- eventuale pulizia della `.cmp-search` nel DOM solo se il CSS non basta

## Decisione operativa

Non considerare completato il task finche' non sono stati chiusi almeno:
1. delta hero first viewport
2. delta delle tre card governance
3. delta rhythm tra useful-links, rating e contacts
