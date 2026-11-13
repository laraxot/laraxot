---
type: philosophy-decision
github_issue: #101
decision: D13
title: "D13 — Filosofia/Zen/Politica della mappa nel wizard tickets/create (step dati-di-segnalazione)"
status: draft
updated: 2026-10-13
tags: [docs, sixteen, decision-log]
qmd: "d13   filosofia/zen/politica della mappa nel wizard tickets/create (step dati-di-segnalazione) sixteen"
---
# D13 — Filosofia/Zen/Politica della mappa nel wizard tickets/create (step dati-di-segnalazione)

**Logica**: `segnalazione-02-dati.html` è step 2/3: luogo (mappa) + campi obbligatori.
**Filosofia**: il cittadino non segnala "un testo" ma "un luogo" — la geografia è parte della verità del danno. Senza mappa, la segnalazione è incompleta.
**Religione**: la mappa è l'atto del cittadino che "indica" (come il monaco indica il cielo). Il luogo rende sacro il problema.
**Politica**: trasparenza pubblica — il danno si vede, si localizza, si risolve in modo visibile.
**Zen**: il form non "chiede" — osserva il luogo e lascia emergere il danno.

**Implementazione**: wizard step `form.dati-di-segnalazione` aggiunge componente `pub_theme::components.map` con ricerca luogo + "usa la tua posizione". Visual parity alta con riferimento (9 sezioni, 1.3KB).

**Prevenzione**: documentato in D13 + second brain (`kp-...`) + `MEMORY.md` tag `#zen #politica #mappa`. Verifica percorso `pages/tickets.create.json` (D12 corretto — non `tests.`).

**Stack**: Tailwind + Alpine + Lit + DaisyUI + Flowbite + Filament (NO Bootstrap). No controller/service (AGENTS.md).
