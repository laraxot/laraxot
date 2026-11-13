---
title: "BMAD Planning — dettaglio Blog/Eventi e contenuto editoriale"
type: bmad-planning
status: maintenance
module: Blog
created: 2026-10-16
tags: [bmad, planning, blog, events, article, comments]
related:
  - ./blog-detail-brainstorming.md
  - ../../Comment/docs/bmad/ticket-comments-planning.md
  - ../../../Themes/Sixteen/docs/bmad/content-detail-planning.md
updated: 2026-10-17
qmd: "scopo blog"
---

# Scopo

Garantire che gli articoli e gli eventi abbiano un dettaglio leggibile,
localizzato, con contenuto presente e commenti Filament montati senza rompere
la pagina Livewire/Volt.

## Piano minimo

- trattare `closed_at` come nullable (`null` non è un errore di tipo);
- verificare sempre il rendering server-side del body prima del widget;
- mantenere `Article` come host `Commentable` per Blog/Eventi;
- usare le pagine JSON e i blocchi del tema come contratto di composizione;
- verificare header/footer con link Novità ed Eventi in tutte le lingue.

## Gate

GET dettaglio con articolo senza `closed_at` → HTTP 200 e body visibile;
POST Livewire del widget non deve serializzare o sovrascrivere il contenuto;
nessun errore Blade per componenti commenti mancanti.

