---
title: "Header style layer rule"
type: concept
confidence: high
updated: 2026-04-21
tags: [header, design-comuni, bootstrap-italia, css, parity, blade]
sources:
  - https://github.com/italia/design-comuni-pagine-statiche
  - https://italia.github.io/bootstrap-italia/docs/menu-di-navigazione/header/
  - ../../../../Main_files/five/segnalazione-02-dati.html
  - ../../../../Main_files/five/src/style.css
  - ../../../../resources/views/components/sections/header/v1.blade.php
---

# Header style layer rule

## Regola permanente

Non inserire blocchi `<style>` condizionali dentro `resources/views/components/sections/header/v1.blade.php` per correggere colori o parity dell'header.

L'header Sixteen segue il modello Design Comuni / Bootstrap Italia:

- Blade: markup, struttura, stato guest/autenticato, inclusione partial.
- CSS/token layer: colori, background, hover, varianti responsive e parity visiva.
- Docs/wiki: razionale e collegamenti alle pagine reference.

## Motivazione

Nel repository `italia/design-comuni-pagine-statiche` l'header e' markup Bootstrap Italia pulito con classi come `it-header-navbar-wrapper`, `navbar`, `navbar-nav`, `navbar-secondary`. La variante cromatica e' demandata a Bootstrap Italia e ai fogli stile generati, non a style inline nel template HTML.

La documentazione Bootstrap Italia dell'Header Nav esplicita che il tema colore si gestisce con classi e CSS del componente: su desktop il default usa background primario e link bianchi; su mobile il default puo cambiare, e le classi tema (`theme-dark-mobile`, `theme-light-desk`) controllano la variante. In Sixteen, eventuali eccezioni di parity per `segnalazione-crea` devono quindi stare nei CSS del tema, non nel Blade owner.

## Applicazione pratica

- Se i link header hanno sfondo/colore sbagliato, correggere `resources/css/app.css` o il CSS sorgente importato dal build del tema.
- Mantenere `v1.blade.php` senza CSS inline, salvo casi tecnici temporanei documentati e rimossi nella stessa story.
- Per la pagina `tests/segnalazione-crea`, confrontare sempre:
  - runtime locale: `/it/tests/segnalazione-crea?step=form.dati-della-segnalazione%3A%3Adata%3A%3Awizard-step`
  - reference: `design-comuni-pagine-statiche/sito/segnalazione-02-dati.html`
- Usare la copia locale in `Main_files/five/` come fonte veloce quando disponibile.

## Anti-pattern

```blade
@if($isSegnalazioneCrea)
    <style>
        .it-header-wrapper.is-segnalazione-crea .nav-link {
            background: #007a52 !important;
        }
    </style>
@endif
```

Questo accoppia una pagina specifica al template header e rende fragile ogni futura parity review. Il fix corretto e' una regola CSS esterna, scoping esplicito e documentato.

