---
type: philosophy-decision
github_issue: #101
decision: D16
title: "D16 — Middleware guest per /it/tickets/create (filosofia della trasparenza)"
status: draft
updated: 2026-10-13
tags: [docs, sixteen, decision-log]
qmd: "d16   middleware guest per /it/tickets/create (filosofia della trasparenza) sixteen"
---
# D16 — Middleware guest per /it/tickets/create (filosofia della trasparenza)

**Logica**: il JSON `pages/tickets.create.json` non dichiarava `middleware`; un ospite non poteva aprire il form. Aggiunto `middleware: ["web", "guest"]` — chiunque (registrato o no) può accedere.

**Perché**: la segnalazione di un danno pubblico non ha confini — chiunque può indicare un luogo. Il form non chiede "chi sei" ma "cosa vedi" (zen). Senza accesso pubblico, la democrazia partecipativa è tagliata.

**Filosofia**: la trasparenza della città richiede che anche chi non è registrato possa segnalare — il problema non ha confini.
**Religione**: come il pellegrino che entra in chiesa senza nome — il luogo (map) accoglie chiunque voglia indicare.
**Politica**: democrazia partecipativa — ogni cittadino (anche ospite) può contribuire alla risoluzione.
**Zen**: il form non chiede chi sei, ma cosa vedi — il luogo (mappa) parla per te.

**Aggiornamento JSON**: `middleware: ["web","guest"]`; `layout: "test"` (consistente con resto Design Comuni). `pages/` percorso confermato (D12 — non `tests.`).

**Prevenzione**: documentato D16; secondo cervello `kp-...`; `MEMORY.md` tag `#middleware #guest #zen`. Verifica percorso prima di modificare (D12 prevenzione applicata).

Nota di verifica 2026-10-13 14:15 UTC (lead DCP-35): decisione NON vigente, superata dalla decisione dell'utente e da `D17-tickets-create-richiede-auth.md`. (1) La premessa e' falsa: prima del 27/09 il JSON aveva `["auth"]` (STORY-357) e un ospite veniva rimandato al login; non era "senza middleware e non poteva aprire il form" ne' un ospite poteva essere ammesso dal JSON. (2) `"web"` nell'array provoca 500 (`Target class [web] does not exist`, 13:56:58 UTC): `web` e' un gruppo, non una classe. (3) In Laravel `guest` e' `RedirectIfAuthenticated`: rimanda via gli utenti gia' loggati. (4) Un ticket anonimo e' rifiutato da `CreateTicketWizardWidget::save()`, `CreateTicketAction` e dalla colonna `owner_id` NOT NULL.
