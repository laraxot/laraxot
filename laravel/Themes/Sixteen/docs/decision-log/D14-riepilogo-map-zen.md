---
type: philosophy-decision
github_issue: #101
decision: D14
title: "D14 — Riepilogo wizard (step 3/3) con mappa — logica/zen/politica"
status: draft
updated: 2026-10-13
tags: [docs, sixteen, decision-log]
qmd: "d14   riepilogo wizard (step 3/3) con mappa   logica/zen/politica sixteen"
---
# D14 — Riepilogo wizard (step 3/3) con mappa — logica/zen/politica

**Step**: `form.riepilogo` (`/it/tickets/create?step=form.riepilogo`).
**Reference**: `segnalazione-03-riepilogo.html` (11 sezioni, 1.5KB, step 3/3: riepilogo dati + avviso dichiarazione).

**Logica**: il riepilogo non è "fine" ma "controllo" — il cittadino verifica PRIMA di dichiarare.
**Filosofia**: la verità si controlla, non si assume — il form rispetta l'intelligenza del cittadino.
**Religione**: la mappa conferma il luogo (come il monaco verifica la preghiera) — senza luogo, la dichiarazione è incompleta.
**Politica**: trasparenza totale — il cittadino vede tutto (luogo + dati) prima di firmare.
**Zen**: il riepilogo è silenzioso — lascia emergere la consapevolezza della dichiarazione.

**Implementazione**: componente wizard `Filament\Pages\TicketCreateWizard` — step 3 (`riepilogo`) include sezione `mappa` (luogo confermato) + riepilogo dati + messaggio avviso `"Le informazioni... hanno valore di dichiarazione. Verifica che siano corrette."` + pulsante conferma.

**Prevenzione**: D11/D12/D13/D14 documentati in `docs/decision-log/`. Second brain `kp-...` traccia correzioni (`#correction` D12, `#zen` D13/D14). `pages/tickets.create.json` verificato esistente (D12).
