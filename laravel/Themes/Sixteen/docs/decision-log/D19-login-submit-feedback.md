---
title: "D19 — Feedback immediato sul submit del login"
type: note
status: draft
updated: 2026-10-19
tags: [docs, sixteen, decision-log]
qmd: "d19   feedback immediato sul submit del login sixteen"
---
# D19 — Feedback immediato sul submit del login

## Decisione

La vista Sixteen del `LoginWidget` rende lo stato di invio con le direttive Livewire
`wire:loading` e `wire:target="login"`: il pulsante viene disabilitato, espone
`aria-busy` e annuncia “Accesso in corso…” tramite `role="status"` e
`aria-live="polite"`.

## Perché

Il widget esegue `login`, non `save`. La vista del tema è una sovrascrittura del
vestito del modulo e deve quindi ripetere il contratto di interazione; altrimenti
il feedback presente nella vista di default del modulo non raggiunge `/it/auth/login`.

Sono stati applicati solo i principi di feedback immediato, stato busy, prevenzione
del doppio invio e annuncio accessibile studiati nella documentazione Bootstrap
Italia. Non vengono importati Bootstrap Italia, le sue classi o le sue dipendenze:
lo stack resta Tailwind + Livewire/Alpine + Filament.

## Verifica

Il contratto è coperto da `LoginSubmitFeedbackContractTest`, un test statico che
non apre connessioni al database e non modifica dati.
