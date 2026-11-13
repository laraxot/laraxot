---
type: philosophy-decision
github_issue: #101
decision: D15
title: "D15 — Conferma post-save (segnalazione-04-conferma, 10 sezioni, 1.7KB)"
status: draft
updated: 2026-10-13
tags: [docs, sixteen, decision-log]
qmd: "d15   conferma post-save (segnalazione-04-conferma, 10 sezioni, 1.7kb) sixteen"
---
# D15 — Conferma post-save (segnalazione-04-conferma, 10 sezioni, 1.7KB)

**Step**: dopo salvataggio wizard (`riepilogo` → submit) → redirect a pagina conferma.
**Reference**: `segnalazione-04-conferma.html` (ID `AN4059281`, email conferma, link lista).

**Logica**: il cittadino non resta su form vuoto — riceve ID univoco + conferma + link lista.
**Filosofia**: la dichiarazione ha un'eco — il sistema riconosce l'atto.
**Religione**: come il monaco che riceve la benedizione — il numero è la prova dell'offerta.
**Politica**: trasparenza — il cittadino vede il proprio atto (ID, email, link alla lista).
**Zen**: silenzio dopo l'atto — solo il riconoscimento, nessuna richiesta ulteriore.

**Note**: nessuna mappa necessaria (luogo già dichiarato ai passaggi precedenti — D13/D14). Solo riepilogo con ID + link + email.

**Prevenzione**: D15 aggiunto; D11-D14 già documentati; JSON `pages/tickets.create.json` verificato; `callSchemaComponentMethod` ancora da implementare (next session).

Correzione verificata 2026-10-13 (DCP-35 S4): `callSchemaComponentMethod` non va implementato, esiste già come metodo `public` in `Filament\Schemas\Concerns\InteractsWithSchemas` (`vendor/filament/schemas/src/Concerns/InteractsWithSchemas.php`, riga 83); l'errore su «Avanti» dipendeva dal Volt senza radice propria, vedi [D15-volt-fragment-explicit-root.md](D15-volt-fragment-explicit-root.md). Nota: il numero D15 è usato da due file (questo e quello del Volt); il prossimo numero libero è D16 secondo DCP-35, ma `D16-scope-data-dc-pagine-reali.md` esiste già.
