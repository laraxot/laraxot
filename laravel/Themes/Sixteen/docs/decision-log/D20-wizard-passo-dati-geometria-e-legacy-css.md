---
title: "D20: geometria del wizard /tickets/create (passo dati) e CSS legacy rimosso"
type: decision
status: accepted
created: 2026-10-13
updated: 2026-10-13
related:
  - Modules/Fixcity/docs/stories/dcp-146-wizard-passo-dati-mappa-sempre-funzionante.story.md
  - docs/architecture/css-scoping-rule.md
tags: [wizard, parity, mobile, css, design-comuni]
qmd: "D20 wizard passo dati geometria stepper max-width 760 1152 legacy css dcp-146"
---

# D20: geometria del passo dati e CSS legacy

## Causa radice dello "si vede male"
Tre strati di CSS legacy a specificita' alta, scritti per il vecchio look del wizard, stringevano il markup Design Comuni:
1. `app/12-wizard-dc-form-shell.css`: `.wizard-dc-form-shell{max-width:1152px !important}` (stepper x=72 w=1152 dentro la colonna da 1296: fuori centro di 72 px) e
   `.fi-sc-wizard-step{max-width:760px !important; margin:auto}` (passo largo 760 invece della griglia indice 306 + contenuto 856 del riferimento).
2. `components/filament-wizard-parity.css`: `form{max-width:720px}`, `.col-lg-8{max-width:720px; margin:0 auto}` (annullava `offset-lg-1`).
3. Regole dei campi in `12-...` (`border:none !important`, sfondo trasparente, `appearance:auto` con freccia propria) che cancellavano il filetto
   dei campi `cmp-input` e raddoppiavano la freccia della select. Sistemate in parallelo con `:not(.it-page-section *)` (DCP-128).
4. `app/12-...` regole `.steppers*`/`.stepper-step` (markup che il tema non emette) e `mobile-header-fix.css` `.cmp-wizard-widget{margin-top:1rem !important}`
   (pagina spinta di 16 px sotto i 992 px).

## Decisione
- Il guscio e il passo riempiono la colonna del widget (1296 a 1440). Il passo con la griglia (`.steppers-content`) emette la sua `.row` e prende la geometria
  dal markup di riferimento (indice x=72 w=306, contenuto x=512 w=856). I passi senza griglia (privacy, riepilogo) stanno in `col-lg-8` centrata
  (`calc((100% + 24px) * 2 / 3 - 24px)`, 856 px, solo da 992 px).
- Fascia contatti del passo dati: stessa larghezza di `col-lg-5` (x=457 w=526) solo quando il passo attivo ha `.steppers-content` (`overrides.css`).
- Bersagli tattili 44 px solo con `pointer: coarse` (campi, link posizione, Indietro, accordion Autore): il riferimento ha 26-40 px, il desktop resta uguale.
- Niente `!important` nuovo salvo dove la regola sconfitta e' a sua volta `!important` (il passo).

## File
`resources/css/app/12-wizard-dc-form-shell.css`, `resources/css/design-comuni/overrides.css`, `resources/css/mobile-header-fix.css`.
