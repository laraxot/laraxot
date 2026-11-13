---
title: "Inbound — blog ed eventi nel FO Sixteen"
type: concept
theme: Sixteen
status: decision-input
updated: 2026-10-15
tags: [inbound, blog, eventi, design-comuni, bmad]
qmd: "blog ed eventi nel front office sixteen"
---

# Blog ed eventi nel front office

## Perché Sixteen

Il tema veste le liste e i dettagli pubblici (Design Comuni: `eventi`,
`evento-dettaglio`, card articoli). Non possiede il dominio editoriale (Blog)
né la strategia economica.

Scopo di prodotto (prima del vestito):
[purpose](../../../Modules/Fixcity/docs/bmad/inbound-marketing/purpose.md).

## Cosa impariamo da AFC (solo UX)

- Hero con promessa unica, non dashboard di promo
- CTA progressive (leggi → partecipa → collabora), non urgenza commerciale
- Hub risorse scarsissimo e ordinato per bisogno
- Eventi accessibili: luogo/modalità, programma, a11y in pagina

## Contratto FO (post-GO)

| Superficie | Fonte dati | Vestito |
|---|---|---|
| Lista / dettaglio post | Blog Article `content_type=article` | Sixteen blocks/pages |
| Lista / dettaglio eventi | Blog Article `content_type=event` (± Meetup) | Design Comuni eventi |
| Hub risorse | Cms page JSON | Sixteen page shell |
| Nav header/footer | Cms sections | già JSON SSoT |

## Non fare

- Hardcode articoli in Blade
- Caroselli sponsor in lista eventi
- Pixel di tracking «per misurare inbound»

## Collegamenti

- Dossier: [inbound-marketing](../../../Modules/Fixcity/docs/bmad/inbound-marketing/README.md)
- Blueprint: [inbound-blog-events](../../../Modules/Blog/docs/blueprints/inbound-blog-events.md)
- Meetup: [inbound-events](../../Meetup/docs/inbound-events.md)
- Piano legacy: [bmad/plans/inbound-marketing-plan.md](./bmad/plans/inbound-marketing-plan.md)
- Parity eventi: story DC-018/DC-019 in `bmad/stories/catalog/`
- Analisi AFC: [afc-formazione-pattern-analysis](../../../Modules/Fixcity/docs/bmad/inbound-marketing/afc-formazione-pattern-analysis.md)

## Studio Design Comuni (gate)

Prima di abilitare: [design-comuni-novita-eventi-fo.md](./design-comuni-novita-eventi-fo.md)
· [studio Fixcity](../../../Modules/Fixcity/docs/bmad/design-comuni-static-pages-study.md).

Nav: Novità/Eventi già in header/footer JSON — nessuna modifica Blade.
