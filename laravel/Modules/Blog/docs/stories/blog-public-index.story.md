---
title: "Indice pubblico dei post FixCity"
status: done
---

# Obiettivo

Rendere `/it/blog` un indice reale di post pubblici, coerente con il modello
agnostico `content_type=post|event`, senza introdurre un modulo duplicato.

## Decisioni BMAD / Blueprint

- Il CMS mantiene il contenuto editoriale della pagina in `blog.index.json`.
- `ArticleResource` resta la superficie di authoring Filament Blueprint.
- La query pubblica passa da `ThemeComposer` e include solo post pubblicati con
  `content_type=post`; gli eventi restano nella loro pagina dedicata.
- Le card usano l’immagine locale e il titolo come testo alternativo; gli stati
  vuoto e paginazione sono espliciti e accessibili.
- Il dettaglio `/blog/{slug}` riusa lo stesso modello e mostra solo commenti
  approvati.

## Acceptance criteria

- `/it/blog` risponde HTTP 200 e mostra titolo, intro, card, immagini e CTA.
- La paginazione conserva la lingua e non mostra eventi nell’indice blog.
- Ogni CTA apre `/it/blog/{slug}` senza 404.
- Un post senza commenti o immagini resta leggibile.
- Un titolo salvato come testo semplice (non JSON di traduzione) compare comunque sulla card: `Article::getTitle()` legge il valore grezzo.
- Nessun commento pending appare al pubblico.
