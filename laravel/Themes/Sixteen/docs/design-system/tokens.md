---
id: ds-sixteen-tokens
type: design-system
title: "Design Tokens — Tema Sixteen / Design Comuni"
status: active
updated: 2026-10-11
tags: [docs, sixteen, design-system]
qmd: "design tokens   tema sixteen (design comuni v2.4.0) sixteen"
---

# Design Tokens — Tema Sixteen (Design Comuni v2.4.0)

## Colori PA (Design Comuni / UI Kit Italia)
```js
primary: '#007A52',  // Verde istituzionale PA
'italia-blue': '#0066CC', // Blue Design Comuni
'italia-blue-dark': '#003D73',
'italia-gray': '#333333',
```
Regola: un solo accent (primary o italia-blue, mai entrambi nello stesso componente). Nessun viola, indigo o gradiente.

## Typography Scale (Modular 1.25)
```text
display: clamp(2.75rem, 7vw, 6.75rem) — h1 hero
display-medium: 2.5rem — h2 sezioni
heading: 1.75rem — h3 card
body-large: 1.125rem — testo evidenziato
body: 1rem / 1.6 — corpo, 45-75ch max
small: 0.875rem — metadata, note
```
Font: `Titillium Web` (sans), `Lora` (serif display opzionale per editoriali). `tabular-nums` per numeri.

## Spacing (8px Grid)
```text
xs: 4px (0.25rem) — solo gap interni molto stretti
sm: 8px (0.5rem)
md: 16px (1rem)
lg: 24px (1.5rem)
xl: 32px (2rem)
2xl: 48px (3rem) — sezione gap
3xl: 64px (4rem) — hero gap
```

## Radius
```text
none: 0px — regole istituzionali, bordi netti
sm: 2px — chip, input
md: 4px (default) — card, button
lg: 8px — card evidenziata, hero
xl: 16px — solo elementi decorativi isolati
```

## Elevation (Named Levels Only)
```text
none: 0 0 0 0
sm: 0 1px 3px rgba(0,0,0,0.12) + 0 1px 2px rgba(0,0,0,0.24)
md: 0 4px 6px rgba(0,0,0,0.1) + 0 2px 4px rgba(0,0,0,0.06)
lg: 0 10px 15px rgba(0,0,0,0.1) + 0 4px 6px rgba(0,0,0,0.05)
xl: 0 25px 50px rgba(0,0,0,0.25) — solo modal / dropdown aperto
```
Regola: nessuna ombra inventata fuori da queste 5 ricette.

## Surface Budget (3 Surfaces, 1 Family)
```text
base: #FFFFFF (carta) o #F8F9FA (sfondo molto chiaro)
mid: #F2F2F2 (tinted paper) — per sezioni alternate
inverse: #003D73 (dark ink) — per footer, hero dark
accent: #007A52 — solo CTA, badge attivi, link attivi
```
Regola: header e nav ereditano base, non introducono un colore nuovo. Zero superfici non correlate.

## Componenti Mappati (DaisyUI + Flowbite)
| Elemento Design Comuni | DaisyUI / Flowbite / Custom Lit |
|------------------------|--------------------------------|
| `.btn` | DaisyUI `btn` (primary / outline / ghost) |
| `.card` | DaisyUI `card` con `card-body` |
| `.chip` / `.badge` | DaisyUI `badge` + `badge-outline` |
| `.navbar` | Lit `<muni-navbar>` (responsive, dropdown) |
| `.breadcrumb` | DaisyUI `breadcrumbs` |
| `.form-control` | DaisyUI `input` / `select` / `textarea` |
| `.modal` | Lit `<muni-modal>` con `x-transition` |
| `.table` | DaisyUI `table` con `overflow-x-auto` |
| `.pagination` | DaisyUI `join` (pagination custom) |
| `.hero` | Custom `section.hero` con `bg-gradient-to-r` sottile |
| `.skiplink` | Custom `.skiplink` con `:focus-visible` |

## States Contract (Per Componente Interattivo)
```text
default → hover → focus-visible → active → disabled
+ error (campo non valido) + empty (campo vuoto) + loading (in attesa)
```
Motion: ogni transizione / animazione deve avere `prefers-reduced-motion` fallback.
