---
type: decision
decision: D15
status: accepted
date: 2026-10-13
owner: theme:Sixteen
related: [D12-correction-json-path-tickets, dcp-22]
qmd: "volt fragment explicit root wire:id livewire nested widget callSchemaComponentMethod tickets create"
title: "D15: il Volt della pagina generica ha una radice propria"
updated: 2026-10-13
tags: [docs, sixteen, decision-log]
---
# D15: il Volt della pagina generica ha una radice propria

**Contesto.** `pages/[container0]/[slug0]/index.blade.php` avvolgeva `<x-page>` in `@volt`. `cms::components.page` non ha elemento radice. Livewire (`Drawer/Utils.php:16`) mette `wire:id` sul primo tag a inizio riga; il `<div>` del widget stava dopo i commenti `<!--[if ENDBLOCK]-->` sulla stessa riga, quindi l'attributo del Volt finiva su `div.cmp-wizard-widget`, dentro il widget.

**Sintomo.** `Livewire Entangle Error: data.location cannot be found on component volt-anonymous-fragment` al caricamento; `MethodNotFoundException: callSchemaComponentMethod` cliccando «Avanti».

**Decisione.** Il `div.page-content` (gia' esistente) e' la radice del `@volt` per il ramo generico. DOM identico, `wire:id` spostato sul wrapper.

**Prova.** Prima: `closest('[wire:id]')` del checkbox = Volt. Dopo: = `CreateTicketWizardWidget`. Console senza errori Entangle, «Avanti» porta a 2/3, nessun nuovo errore nel log Laravel dopo le 14:40:00 locali. Snapshot di `/it/auth/login`, `/it/auth/register`, `/it/tickets/track`, `/it/tickets`: solo whitespace e token.

**Alternativa scartata.** Override di `callSchemaComponentMethod` in `XotBaseWizardWidget`: no-op (`parent::` funziona solo perche' il metodo e' gia' pubblico in `InteractsWithSchemas`), attivo dalle 12:34 UTC mentre l'errore continuava alle 12:39-12:40.

**Regola.** Un blocco Livewire dentro una pagina servita da un Volt richiede che il Volt abbia una radice a inizio riga.
