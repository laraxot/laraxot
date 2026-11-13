---
title: "Header/footer rationale"
type: decision
tags: [second-brain, header, footer, sections, json, sixteen]
qmd: "why header footer sections JSON Sixteen theme ownership"
status: draft
updated: 2026-10-22
---

# Perché header e footer sono sections JSON

<!-- Supersede: aggiorna rationale 2026-10-11. Link da: header-footer-sections-contract, dcp-222, reusable-components. Istruzione utente: logo footer = header. -->

La section CMS è il confine corretto: l'identità del Comune e i suoi link
variano per tenant, mentre il tema deve garantire la stessa struttura visuale
Design Comuni per tutte le installazioni. Per questo il JSON contiene dati,
non markup, e Sixteen contiene un solo renderer canonico.

Il marchio (`brand.logo`) è parte di quell'identità: header, footer e hamburger
usano lo stesso partial `brand-mark` (SVG + invert). Un `<img>` nel footer e un
SVG nell'header sembrano due loghi diversi anche con lo stesso URL — bug di
vestito, non di dato.

L'errore più rischioso è trattare `x-section` come una semplice inclusione
Blade: `Section` passa `BlockData` a `v1`. Se `v1` ricrea sempre un blocco dai
props vuoti, la configurazione JSON viene silenziosamente ignorata. Il
contratto quindi impone la propagazione di `blocks` e mantiene i props soltanto
per i test diretti e la compatibilità.

Questa scelta evita tre duplicazioni: un layout `design-comuni`, un namespace
`pub_theme` alternativo e contenuto tenant copiato nei componenti. Le differenze
tra comuni restano nei file `sections/header.json` e `sections/footer.json`,
mentre UI, responsive e WCAG restano verificabili in un solo punto.
