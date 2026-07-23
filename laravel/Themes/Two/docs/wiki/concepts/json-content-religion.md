---
title: "Theme Two — religione del contenuto JSON"
type: concept
tags: [theme-two, json, folio, frontoffice, philosophy, zen]
module: Two
created: 2026-07-16
updated: 2026-07-16
qmd: "Theme Two religione filosofia zen JSON pagine header footer front office"
issues:
  - "https://github.com/laraxot/base_techplanner_fila5/issues/40"
discussions:
  - "https://github.com/laraxot/base_techplanner_fila5/discussions/41"
related:
  - ../../architecture-data-driven-pages.md
---

# Theme Two — religione del contenuto JSON

## Logica

Una pagina generica Folio e componenti riusabili rendono molti JSON. Questo è
il patto DRY del tema: struttura stabile, contenuto dichiarativo.

## Speranza

Chi cura il sito può cambiare testi, ordine e blocchi senza moltiplicare route,
controller o template specializzati.

## Politica

`pages/*.json` e `sections/{header,footer}.json` sono sorgenti versionate del
prodotto. Nessun cleanup può cancellarle perché sembrano config locale,
duplicati linguistici o vecchi slug senza prima dimostrare e migrare i consumer.

## Filosofia, religione e zen

Folio è il sentiero. Il tema è la forma. Il JSON è la voce. Proteggerne uno
solo non basta: il front office esiste nell'incontro dei tre. La soluzione più
semplice non è eliminare i dati, ma lasciare che una pagina generica li renda.
