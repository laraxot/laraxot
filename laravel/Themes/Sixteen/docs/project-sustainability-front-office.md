---
title: "Sostenibilità — front office Sixteen"
type: concept
theme: Sixteen
status: decision-input
updated: 2026-10-15
tags: [sustainability, sixteen, fo, wcag, bmad]
qmd: "front office (ownership sixteen) sixteen"
---

# Front office (ownership Sixteen)

## Perché il tema

Sixteen è il **vestito**: markup, Design Comuni, WCAG, localizzazione URL. Non
possiede la strategia economica né i JSON di contenuto.

## Scopo business

Quando (e se) esisterà contenuto Cms per partner/CTA, Sixteen lo renderà in modo
accessibile, sobrio e coerente col chrome istituzionale — senza ads.

## Contratto FO proposto

| Elemento | Comportamento |
|---|---|
| Partner nel footer | Loghi statici + nome + disclosure; no carosello |
| CTA «Scopri come sostenere» | Link a pagina Cms; copy non urgente |
| Empty state | Se `enabled=false` o lista vuota → nessun titolo vuoto |
| URL | Interni via `FrontofficeUrl`; esterni marcati |
| Tracking | Zero pixel sponsor |

Allineato a: [footer-partner-sponsorship-marketing](./wiki/concepts/footer-partner-sponsorship-marketing.md)
e [footer-sponsorship UX](../../../../bmad-output/footer-sponsorship/ux-design.md).

## Idee UX migliori dei banner

- Impact strip fuori hero (numeri aggregati Fixcity)
- Pagina dedicata full narrative
- Recognition partner solo tier alto nel footer (resto in pagina)

## Confini

- Nessun hardcode di loghi/prezzi in Blade
- Nessuna implementazione finché readiness sustainability = CONCERNS
- DCP-222 resta: JSON SSoT, Blade vestito

## Collegamenti

- Dossier: [project-sustainability](../../../../bmad-output/project-sustainability/README.md)
- Cms: [project-sustainability-content.md](../../Modules/Cms/docs/project-sustainability-content.md)
- Fixcity impact: [project-sustainability-impact-and-mission.md](../../Modules/Fixcity/docs/project-sustainability-impact-and-mission.md)
- Story: [footer-sponsor-marketing.story.md](./bmad/stories/footer-sponsor-marketing.story.md)
