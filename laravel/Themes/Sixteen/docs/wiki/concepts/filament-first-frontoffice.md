---
title: "Filament-first sul frontoffice Sixteen"
type: concept
status: active
created: 2026-05-28
tags: [filament, frontoffice, tabs, design-comuni]
related:
  - ../../../../../../docs/wiki/rules/filament-first-rule.md
  - ./no-standalone-livewire-frontoffice.md
  - ../../design-comuni/visual-comparison/it-vs-ticket-list.md
  - ../../../../../Modules/Fixcity/docs/wiki/concepts/ticket-list-map-architecture.md
---

# Filament-first sul frontoffice Sixteen

## Scopo

Il tema Sixteen pubblica pagine Design Comuni con **Tailwind + BI semantics**, ma per pattern UI già coperti da Filament 5 si usa il componente Blade ufficiale, con eventuale **skin CSS** per parity PA.

## Prerequisito runtime

`resources/views/layouts/main.blade.php` carica `@filamentStyles` e `@filamentScripts` — senza pannello admin i tag `<x-filament::*>` restano validi.

## Tab `/it` (ticket-list)

- **Implementazione:** [ticket-list-filament-tabs.md](./ticket-list-filament-tabs.md) · story [STORY-065](../../../../../../docs/stories/STORY-065-it-segnalazioni-filament-tabs.md)
- **Docs Filament:** [Tabs](https://filamentphp.com/docs/5.x/components/tabs) — `alpine-active` + `x-on:click` sullo stesso `x-data` dei pannelli (`segnalazioniLayout`)
- **Skin:** classi su `.ticket-list .fi-tabs` in `style-apply.css` (non sostituire con `ul.nav-tabs` nuovi)

## Dettaglio ticket `/it/tickets/{id}`

- **CMS:** `tickets.view.json` → blocco `type: widget` (non `type: ticket` + Blade custom).
- **Widget:** `Modules\Fixcity\Filament\Widgets\Ticket\ViewWidget` (`XotBaseInfolistWidget`). Naming: [filament-widgets-domain-folder-naming](../../../../Modules/Xot/docs/wiki/concepts/filament-widgets-domain-folder-naming.md).
- **Schema:** `TicketInfolist::getInfolistSchema()` — stesso SSoT del backoffice `ViewTicket`.
- **Mount:** `ui::components.blocks.widget.simple` passa `slug0` / `container0` dal data bag + `blockData`.
- **ADR:** [ticket-fo-detail-filament-widget-infolist.md](../../../../../../docs/wiki/decisions/ticket-fo-detail-filament-widget-infolist.md)
- **Vietato:** `pub_theme::components.blocks.ticket.detail` (rimossa).

## Boundary

| Consentito FO | Vietato FO |
|---------------|------------|
| `<x-filament::tabs>`, `::icon`, `::button` | Blade FO che duplica campi già in Infolist/Resource |
| Blocco CMS `widget` + `XotBaseInfolistWidget` | `type: ticket` + view tema ~100 righe |
| `map-lit`, `map-filter-lit` (Lit Geo) | Duplicare logica tab in `app.js` se Filament basta |

## Collegamenti

- Regola root: [filament-first-rule.md](../../../../../../docs/wiki/rules/filament-first-rule.md)
- Modulo UI: [filament-components.md](../../../../../Modules/UI/docs/blade/filament-components.md)
