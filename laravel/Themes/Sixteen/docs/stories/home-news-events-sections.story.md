---
id: SIXTEEN-HOME-NEWS-EVENTS
title: "Homepage: sezioni Novità ed Eventi dai dati Blog"
type: story
theme: Sixteen
status: done
priority: P1
owner: theme:Sixteen
scope: "laravel/config/local/fixcity/database/content/pages/home.json, laravel/Themes/Sixteen/"
created: 2026-10-26
updated: 2026-10-26
tags: [bmad, sixteen, homepage, blog, event, wcag]
qmd: "Sixteen homepage novita eventi home.news home.events ThemeComposer"
related: [IM-SIXTEEN-001, homepage-design-comuni-rebuild]
---

# Homepage: sezioni Novità ed Eventi dai dati Blog

## Problema

La homepage (`pages/home.json`) non mostrava notizie né eventi, anche con post ed eventi pubblicati
(Blog `Article`, STI `type` = post|event).

## Soluzione

Due blocchi CMS nuovi in tutte le lingue (it, en, de, es), dopo «Come funziona» e prima di «Azioni rapide»:

- `home.news` → `pub_theme::components.blocks.home.news`. Prende i dati da `ThemeComposer::featuredBlogPosts($limit)`,
  e se non ci sono notizie in evidenza usa `paginatedBlogPosts($limit)` (le ultime). Le card riprendono la sezione
  «In evidenza» di `sito/novita.html` (immagine panoramica, categoria, data, titolo, abstract) e passano dal partial
  `partials/news-card.blade.php`. Il link «Vedi tutte» porta a `FrontofficeRoutes::blogIndex()`.
- `home.events` → `pub_theme::components.blocks.home.events`. Prende i dati da
  `ThemeComposer::paginatedArticlesComingSoon($limit)` (solo eventi futuri, i più vicini prima). Rifà la struttura di
  `section#calendario` di `sito/homepage.html`: `row-title` h2, `it-calendar-wrapper` con un h3 per mese e card per
  giorno con h4. Il link «Vedi tutti» porta a `FrontofficeRoutes::eventsIndex()`. È una griglia statica, senza JS Splide.

I testi (titolo, etichetta e aria-label del link «Vedi tutte/i», categoria di ripiego, limite) stanno nel JSON per
ogni lingua. Date e mesi usano `translatedFormat` nella lingua attiva.

## Accessibilità

- Titoli in ordine: h2 di sezione, h3 per card o mese, h4 per giorno. Il giorno abbreviato è `aria-hidden` e
  accanto c'è la data completa in `visually-hidden`.
- Le immagini delle notizie hanno come alt il titolo, perché l'immagine non sta dentro il link. Le miniature
  degli eventi hanno `alt=""` perché il titolo è già il testo del link accanto.
- Gli aria-label dei link «Vedi tutte/i» contengono il testo visibile (WCAG 2.5.3).

## Stati vuoti

Se non ci sono notizie pubblicate o eventi futuri, la sezione non viene stampata (né il wrapper né il titolo).

## Verifica

- `Themes/Sixteen/tests/Feature/HomeNewsEventsTest.php` (scratch DB): `/it` e `/en` mostrano post ed evento futuro
  e non mostrano l'evento passato. Senza articoli le due sezioni non ci sono.
- Controllo dal vivo sul DB di sviluppo, solo GET: `/it`, `/en`, `/de`, `/es` rispondono 200 e mostrano 3 notizie
  in evidenza e i 5 eventi futuri.

## Aperto

- `blog/index.blade.php` ripete lo stesso markup della card: si può passare a `partials/news-card`.
- I post e gli eventi in de/es non hanno traduzioni nel DB e si vedono in italiano.
- Manca il controllo visivo con screenshot: il browser Playwright non è installato.
