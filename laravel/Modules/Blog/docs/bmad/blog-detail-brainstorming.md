---
title: "BMAD Brainstorming — Blog, Eventi e conversazione"
type: bmad-brainstorming
status: synthesized
module: Blog
created: 2026-10-16
tags: [bmad, brainstorming, blog, events, content, comments]
related: [./blog-detail-planning.md]
updated: 2026-10-17
qmd: "ipotesi e decisioni blog"
---

# Ipotesi e decisioni

- **Problema:** un campo editoriale opzionale (`closed_at`) non può rompere il
  dettaglio; la pagina deve privilegiare il contenuto prima degli elementi
  accessori.
- **Opportunità:** Blog ed Eventi condividono il contratto di dettaglio e il
  widget Comment, ma mantengono URL, tipo e copy distinti.
- **Rischio:** duplicare template tra modulo e tema crea divergenza; il modulo
  possiede dati e query, Sixteen possiede presentazione.
- **Decisione:** ogni errore di contenuto si verifica prima con GET/cache Blade,
  poi con Livewire; i commenti non sono la fonte del body editoriale.

