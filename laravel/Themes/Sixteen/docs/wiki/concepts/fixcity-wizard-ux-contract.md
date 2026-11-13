---
qmd: "fixcity wizard ux contract 2026 09 26"
title: "FixCity — contratto UI/UX wizard Sixteen"
type: concept
theme: Sixteen
confidence: high
created: 2026-09-26
updated: 2026-09-26
tags: [fixcity, sixteen, wizard, ux, accessibility, design-comuni, second-brain]
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions"
related:
  - ../rules/frontend-stack-canonical.md
  - ../../../../../Modules/Fixcity/docs/wiki/overviews/fixcity-module.md
status: draft
---

# Contratto UI/UX del wizard FixCity

Sixteen è owner della presentazione Frontoffice; Fixcity è owner di dati, Action e workflow. Non duplicare la logica di dominio nel tema.

## Percorso

`privacy → dati → posizione → riepilogo → conferma`.

Ogni step deve avere label tradotta, focus visibile, messaggi di errore inline, stato loading e comportamento mobile verificabile. Il riepilogo usa lo schema Infolist canonico, non una seconda form manuale.

## Stack

Tailwind v4, Alpine, Lit per componenti interattivi/mappa, DaisyUI/Flowbite dove previsto, Filament 5 per widget. Nessun Bootstrap runtime e nessun CSS inline che sostituisca il design system.

## Boundary

- la pagina Folio usa il layout e il data bag `<x-page>` previsti;
- il tema non crea Controller, query Ticket o policy;
- il widget usa schema Filament e `$this->form->getState()`;
- le stringhe passano dalle traduzioni `fixcity::`/tema, mai testo hardcoded nel nuovo codice;
- verificare desktop e mobile con screenshot/report prima di chiudere la story UI.

## Acceptance UX

- tastiera: ordine focus e submit funzionanti;
- contrasto e stato errore leggibili;
- mappa non blocca la compilazione se il provider Geo fallisce;
- upload comunica formato, limite e fallimento;
- riepilogo mostra tutti i dati prima della creazione;
- conferma contiene identificativo e azione di tracking.
