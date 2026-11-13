---
title: "BMAD Planning — commenti del ticket"
type: bmad-planning
status: ready-for-dev
module: Comment
created: 2026-10-16
tags: [bmad, planning, filament, comments, ticket, authorization]
related:
  - ./ticket-comments-brainstorming.md
  - ./fo-comments/filament-blueprint.md
  - ../../Fixcity/docs/bmad/ticket-lifecycle-planning.md
updated: 2026-10-17
qmd: "obiettivo comment"
---

# Obiettivo

Usare un solo `CommentsWidget` Filament per la discussione sul ticket, con
accesso coerente con `TicketPolicy` e senza creare un secondo componente
Livewire o una tabella commenti parallela.

## Contratto

- Host: `Ticket` (`Commentable` + `HasComments`).
- Widget: `Modules\\Comment\\Filament\\Widgets\\Commentable\\CommentsWidget`.
- Mount: `commentableType`, `commentableKey`, `readOnly`, `hideNotifications`.
- Proprietario e operatori autorizzati: lettura/scrittura.
- Ospite e altro cittadino: nessun thread privato.
- Replies e reactions restano configurabili dal contratto del prodotto, non
  duplicati nel modulo Fixcity.

## Piano

1. Centralizzare il controllo di autorizzazione nel resolver/widget per evitare
   bypass tramite props Livewire falsificate.
2. Mantenere commenti approvati, moderazione e notifiche secondo le policy del
   modulo Comment.
3. Aggiungere test per owner, non-owner, moderatore, guest e key contraffatta.
4. Esporre nella timeline solo eventi sanitizzati quando il prodotto approva
   la pubblicazione; il testo privato non diventa automaticamente pubblico.

## Non fare

- non usare `Modules\\Comment\\Http\\Livewire` legacy;
- non montare il widget solo se esistono commenti;
- non passare un modello Eloquent completo come unica protezione;
- non usare `writable` al posto di `readOnly`.

