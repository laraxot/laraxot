---
title: Theme CSS discipline - global site CSS, no per-page selectors (wizard parity)
type: concept
updated: 2026-04-23
tags: [sixteen, css, parity, bootstrap-italia, wizard, best-practices, false-friends]
---

# Regola

Nel tema Sixteen non si introducono regole CSS per pagina tipo:

- `.page-content[data-slug="..."] { ... }`
- `.ticket-wizard-root { ... }`

La parity (Design Comuni / Bootstrap Italia) va ottenuta con **CSS globale di sito** e/o component-level, non con "override per singola pagina".

# Best practices

- Lavorare su classi semantiche riusabili (header, nav, stepper, form, map container).
- Evitare inline `<style>` nei Blade dei moduli: la CSS deve stare nel tema (SSoT).
- Dopo modifiche tema: eseguire `npm run build` e `npm run copy` dalla cartella `laravel/Themes/Sixteen`.

# Bad practices

- Patch "una-tantum" per far tornare una pagina specifica: crea divergenza tra wizard e riduce mantenibilita'.

# False friends

- "Solo questa pagina ha il problema quindi selettore per pagina e' ok": no, rompe la regola di parity e rende impossibile riusare layout/componenti.

