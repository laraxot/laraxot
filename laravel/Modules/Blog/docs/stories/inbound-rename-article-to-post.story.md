---
id: IM-BLOG-008
title: "Rename opzionale Article → Post (deferred)"
type: story
module: Blog
epic: "V Sistema editoriale"
status: deferred
priority: P2
owner: module:Blog
scope: "laravel/Modules/Blog/"
created: 2026-10-15
updated: 2026-10-15
tags: [bmad, rename, post, article, parental]
qmd: "rename Article model to Post morph Filament Parental"
related: [IM-BLOG-002, IM-BLOG-007]
issues: []
discussions: []
---

# Rename opzionale Article → Post

## Richiesta / scopo
Allineare il nome della classe Eloquent al concetto di dominio **post**.

## Perché
Il linguaggio prodotto è già «post»; la classe `Article` resta per Ponytail.
Il rename è costoso (morph Comment, Filament, STI Parental, import).

## Acceptance Criteria
- [ ] Piano morph + Parental child map verificato.
- [ ] Nessuna regressione su Comment / Media / Resource.
- [ ] Docs e seed usano solo il nuovo nome.
- [ ] Finestra senza demo live o con dual-read temporaneo.

## Dipendenze / blocchi
Deferred finché `content_type=post|event` è stabile in FO e Filament.
Decisione: [brainstorming-post-vs-article.md](../brainstorming-post-vs-article.md).

## Dev Notes
Non fare alias frettolosi. Preferire rename orchestrato con test.
