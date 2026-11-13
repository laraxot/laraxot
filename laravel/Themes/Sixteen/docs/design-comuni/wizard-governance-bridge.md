---
title: "wizard governance bridge"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "wizard governance bridge"
issues: []
discussions: []
status: draft
---

# wizard governance bridge

## scopo

Bridge tra regole di tema e regole di modulo per il wizard segnalazione.
Il tema documenta la **parity visiva**; il modulo documenta la **business logic**.

## confini

- tema Sixteen: HTML/CSS parity, layout, componenti visuali.
- modulo Fixcity: schema step, payload ticket, submit flow.
- modulo Xot: policy wizard condivise e hook riusabili.

## regola pratica

- non duplicare spiegazioni funzionali del wizard in docs tema;
- nel tema mantenere solo impatti visuali e link al canonico modulo.

## canonici

- modulo (business): [wizard governance philosophy](../../../../Modules/Fixcity/docs/wizard-governance-philosophy.md)
- modulo (tecnico): [CreateTicketWizardWidget](../../../../Modules/Fixcity/docs/CreateTicketWizardWidget.md)
- xot (base): [XotBaseWizardWidget](../../../../Modules/Xot/docs/wiki/concepts/filament-wizard-architecture-right-way.md)
- tema (parity): [Ticket creation wizard](./TICKET-CREATION-WIZARD.md)

## collegamenti bidirezionali

- [Design comuni index](./00-index.md)
- [Theme 00-index](../00-INDEX.md)
- [Fixcity docs index](../../../../Modules/Fixcity/docs/index.md)
