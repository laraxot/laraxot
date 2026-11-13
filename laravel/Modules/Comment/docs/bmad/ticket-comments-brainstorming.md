---
title: "BMAD Brainstorming — discussione cittadino/operatore"
type: bmad-brainstorming
status: synthesized
module: Comment
created: 2026-10-16
tags: [bmad, brainstorming, comments, privacy, moderation]
related: [./ticket-comments-planning.md, ./fo-comments/filament-blueprint.md]
updated: 2026-10-17
qmd: "esplorazione comment"
---

# Esplorazione

## Alternative

| Opzione | Vantaggio | Rischio | Decisione |
|---|---|---|---|
| commenti liberi pubblici | trasparenza | PII, abusi, moderazione complessa | no, finché manca policy |
| commenti solo proprietario/PA | controllo e contesto operativo | meno trasparenza | sì |
| commenti come eventi timeline | una sola storia visiva | confonde audit e conversazione | solo proiezione sanitizzata |
| nuovo componente ticket | rapido localmente | doppio motore | no |

## Failure modes

- un altro cittadino indovina l'id e legge una discussione;
- il client cambia `readOnly=false`;
- il client cambia `commentableType` verso un modello diverso;
- un commento non approvato compare nella cronologia pubblica;
- le notifiche espongono il contenuto a destinatari non autorizzati.

## Insight

La sicurezza non può dipendere dalla UI: il widget deve risolvere il modello,
verificare l'attore e applicare la policy anche durante l'azione Livewire.

