---
title: "Sub navigation del pannello admin"
type: reference
tags: [filament, navigation, admin, theme]
created: 2026-08-05
updated: 2026-08-05
qmd: "filament admin record sub navigation theme ownership"
related:
  - "./00-index.md"
---

# Sub navigation del pannello admin

## Il tema non possiede la chrome del pannello

Le tab che compaiono sopra una pagina di record (Modifica, Compila, Assenze e simili) sono
sub navigation di Filament. Non passano dal tema: Zero sovrascrive solo
`resources/views/filament/widgets/auth/login.blade.php` e non ridefinisce il layout delle
pagine del pannello.

Chi cerca dove togliere, aggiungere o rinominare una tab non deve cercare qui. I punti di
intervento sono tre e stanno nei moduli:

1. la risorsa, con `getRecordSubNavigation()`, decide quali pagine diventano tab;
2. la pagina, con `getSubNavigation()`, decide se mostrarle o nasconderle;
3. il file di lingua del modulo fornisce etichetta e icona della voce.

La posizione delle tab arriva da `XotBaseResource`, che imposta
`SubNavigationPosition::Top`. Anche questa e' una scelta del modulo Xot, non del tema.

## Riferimenti

- `Modules/Xot/docs/filament-record-sub-navigation.md`
- `Modules/Ptv/docs/scheda-record-sub-navigation.md`
