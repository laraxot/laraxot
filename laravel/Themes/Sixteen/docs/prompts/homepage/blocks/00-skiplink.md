# Block 00: Skiplink (Accessibilità)

**Fonte**: `https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html`
**Posizione**: Prima di `<header>`, fuori da `<main>`

## Descrizione

Link di accessibilità nascosti che permettono agli utenti di tastiera e screen reader di saltare direttamente ai contenuti principali o al footer, senza dover navigare tutto l'header/navbar.

## Struttura HTML (Reference)

```html
<div class="skiplink">
  <a class="visually-hidden-focusable" href="#main-container">
    Vai ai contenuti
  </a>
  <a class="visually-hidden-focusable" href="#footer">
    Vai al footer
  </a>
</div>
```

## Comportamento

- Invisibili normalmente (`visually-hidden-focusable` = `position: absolute; clip: rect(0,0,0,0)`)
- Diventano visibili quando ricevono il focus da tastiera
- Il primo link punta a `#main-container` (il `<h1>` visually-hidden dentro `<main>`)
- Il secondo link punta a `#footer`

## Corrispondenza locale

Verificare che l'implementazione locale abbia:
- `div.skiplink`
- Due `<a class="visually-hidden-focusable">`
- `href="#main-container"` e `href="#footer"`

## Note

Questo blocco è fondamentale per l'accessibilità (WCAG 2.1 criterio 2.4.1).
Non richiede CSS speciali oltre le classi Bootstrap Italia / Tailwind equivalenti.
