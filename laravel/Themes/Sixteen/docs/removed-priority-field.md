---
title: "REMOVED PRIORITY FIELD"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "REMOVED PRIORITY FIELD"
issues: []
discussions: []
status: draft
---

# Rimosso campo 'priority' dalla UI del wizard

Motivazione
- Il select per 'priority' generava problemi di visualizzazione nel passo wizard (form.data::data::wizard-step).
- Per risolvere rapidamente l'issue UX, il campo è stato rimosso dalla UI front-end.

Cosa è stato fatto
- Campo `priority` rimosso dal form Filament usato per il wizard (Modules/Fixcity TicketForm).
- Aggiornare e ricostruire il tema se necessario: `cd Themes/Sixteen && npm run build && npm run copy`.

Note
- Verificare che non ci siano riferimenti JS/CSS specifici a `.fi-select-input` legati a questo campo; la rimozione semplifica il markup.
