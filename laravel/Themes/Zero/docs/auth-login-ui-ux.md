---
title: "UI/UX della pagina di accesso Restaurant"
type: decision
theme: Zero
created: 2026-09-17
updated: 2026-09-17
tags: [auth, ui, ux, accessibilita, motion]
related:
  - ./navigation-integration.md
  - ../../Modules/User/docs/wiki/concepts/login-page-design-comuni.md
---

# UI/UX della pagina di accesso Restaurant

## Direzione visiva

La pagina usa una direzione **hospitality premium**: fondo caldo, accento
ambra, tipografia serif per la gerarchia editoriale e una card chiara per il
compito principale. Su desktop il pannello narrativo accompagna il form; su
mobile scompare per lasciare spazio all'accesso.

## Contratto di interazione

- il form mantiene label visibili, autocomplete e target touch di almeno 44px;
- il focus resta sempre visibile e gli errori restano vicino al campo;
- GSAP anima soltanto `opacity`, `transform` e feedback di pressione;
- l'entrata è una stagger breve (400ms, `power2.out`), mentre il movimento
  decorativo CSS viene disattivato con `prefers-reduced-motion`;
- Three.js/WebGL non è usato nella pagina di login: un effetto 3D non aggiunge
  informazione al compito e peggiorerebbe peso, batteria e prevedibilità.

## Verifica

Verificare desktop e viewport 375px con Playwright, controllare la navigazione
da tastiera e ricostruire gli asset del tema con `npm run build && npm run copy`.
