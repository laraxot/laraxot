---
title: "Proprietà del blade Folio generico `[container0]/[slug0]`"
type: concept
status: active
tags: [sixteen, folio, volt, ownership, container0]
created: 2026-10-26
updated: 2026-10-26
qmd: "perché la blade generica è nel tema, non nei moduli sixteen"
---

# Perché la blade generica è nel tema, non nei moduli

## Il file
`Themes/Sixteen/resources/views/pages/[container0]/[slug0]/index.blade.php` (Volt `container0.view`)

## Serve a tutti
Questa blade è il **generic handler** per ogni rotta `/{lang}/{container0}/{slug0}`:
- `blog` → articoli (Blog)
- `events` → eventi (Blog)
- `tickets` → segnalazioni (Fixcity)
- `profile` → profili utente (User)
- `services` → servizi (Fixcity)
- Qualsiasi altro `container0` con un modello dinamico o una pagina CMS

## Perché non nel modulo
I moduli non hanno una blade propria per i dettagli. Il modulo fornisce il **modello** (`Ticket`, `Article`) e l'**azione di risoluzione** (`BuildPublicTicketDetailAction`, `ThemeArticleQueries`), ma la **vista** è unica e generica nel tema. Questo è il pattern Folio: i moduli dichiarano dati, il tema dichiara come renderizzarli.

## Contratto
1. Il modulo registra una fonte per il dettaglio (es. `ListPublicTicketPathsAction` per sitemap)
2. Il tema fornisce la blade generica
3. `ResolvePageAction` (Cms) risolve il modello dinamico
4. `BuildPublicTicketDetailAction` (Fixcity) carica il ticket specifico
5. Il componente Volt decide cosa mostrare (ticket-detail, blog-detail, ecc.)

## Dipendenze
- Modulo → Tema: il modulo fornisce dati, il tema fornisce la vista
- Tema → Cms: il tema usa `ResolvePageAction`
- Modulo → Seo: il modulo registra sitemap source
- Seo → Cms: la sitemap legge `Page::query()`
