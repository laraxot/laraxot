---
qmd: "vendor css data-dc guard esenzione postcss isolate-legacy-important leaflet codemirror easymde editor markdown filament fi- cmt-"
issues: []
discussions: []
title: CSS di terzi sulle pagine data-dc, esenzione nel guard PostCSS
type: concept
tags: [sixteen, design-comuni, postcss, css, filament, leaflet, markdown-editor, commenti]
created: 2026-10-26
updated: 2026-10-26
sources:
  - ../../../postcss/isolate-legacy-important.cjs
  - ../../../tests/Unit/PostcssMapVendorRulesTest.php
  - ../../../../../Modules/Comment/docs/stories/cmt-022.fo-comments-write-reply-markdown-editor.story.md
  - ../../../../../Modules/Comment/docs/bmad/fo-comments/architecture.md
status: active
---

# CSS di terzi sulle pagine `data-dc`

## Regola

Su una pagina Design Comuni (`body:has(.page-content[data-dc])`) **ogni regola CSS e' spenta** da `postcss/isolate-legacy-important.cjs`, tranne quelle esenti per nome.
Vale anche per `fi-*` di Filament e per il CSS di una libreria di terzi: senza esenzione, il componente compare ma senza stile.

Quindi, prima di montare un componente di terzi su blog, eventi, ticket, login o wizard:

1. aprirlo nel browser a 375, 768 e 1280 px, non basta un test di Livewire verde;
2. se le sue regole sono spente, **esentare per nome** i selettori del vendor nel plugin (come `isMapVendorSelector` per Leaflet, DCP-146, e `isEditorVendorSelector` per l'editor Markdown, CMT-022)
   e aggiungere i casi a `PostcssMapVendorRulesTest` (regole esenti **e** nomi simili che devono restare isolati);
3. per tutto il resto (pulsanti, link, campi) usare classi del modulo proprietario (es. `cmt-*`) con specificita' maggiore dei reset del tema, mai `!important`.

## Perche'

- Il tema accumula CSS legacy con `!important` e specificita' alta; il guard lo tiene fuori dalle pagine Design Comuni. Il prezzo e' che anche il vendor viene spento.
- Sintomi misurati: Leaflet con tile impilati in colonna (DCP-146); editor Markdown con pulsanti larghi 0 px e una riga «xxxxxxxxxx» (`.CodeMirror-measure`) sotto il campo (CMT-022).
- NON esentare gli altri `.fi-*`: i campi del wizard sono tarati con il guard acceso.

## Trappole misurate (CMT-022)

- `body:has(.page-content[data-dc]) button { background: transparent; padding: 0; color: inherit }` ha specificita' `(0,2,2)`: `.cmt-btn` da solo perde. Il modulo usa `.cmt-section button.cmt-btn[type]` `(0,3,1)`.
- `.fi-fo-markdown-editor` imposta il monospace: `font-family: inherit` su `.CodeMirror` lo riceveva dal padre. Va impostato sul riquadro `.fi-fo-markdown-editor`.
- Un componente figlio Livewire ha un solo elemento radice, sempre presente: un `@if` in testa lascia un commento HTML e il secondo render dice «Invalid Livewire child tag name».
- Il CSS inline del modulo (`<x-comment::styles />`) e' ammesso dalla CSP (`style-src 'unsafe-inline'`); la build del tema e' `flock /tmp/sixteen-build.lock npx vite build` in `laravel/Themes/Sixteen`.

## Controllo

```bash
cd laravel && bash ../bashscripts/tools/pest-scratch-db.sh Themes/Sixteen/tests/Unit/PostcssMapVendorRulesTest.php
```
