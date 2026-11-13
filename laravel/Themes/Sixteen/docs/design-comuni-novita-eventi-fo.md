---
title: "Design Comuni — contratto FO novità ed eventi"
type: architecture
theme: Sixteen
status: decision-input
created: 2026-10-15
updated: 2026-10-15
tags: [design-comuni, novita, eventi, sixteen, bmad, ponytail]
qmd: "Sixteen FO novita eventi argomenti lista risorse header footer JSON"
related:
  - ./inbound-marketing-blog-eventi.md
  - ./bmad/header-footer-sections-contract.md
  - ../../../Modules/Fixcity/docs/bmad/design-comuni-static-pages-study.md
  - ../../../Modules/Blog/docs/blueprints/inbound-blog-events.md
issues: []
discussions: []
---

# Contratto FO — Novità ed Eventi (prima dell’abilitazione)

## Scopo

Il cittadino si orienta come su Design Comuni: temi → aggiornamenti →
occasioni di partecipazione. Sixteen **veste**; Blog **possiede** i contenuti;
Cms **aggancia** hub/argomenti; header/footer JSON **espongono** i link.

Non abilitare liste/dettagli pubblici finché i gate dello studio Fixcity
passano. Fonti:
[design-comuni-static-pages-study.md](../../../Modules/Fixcity/docs/bmad/design-comuni-static-pages-study.md).

## Mapping pagine ufficiali → superficie FixCity

| Pagina Design Comuni | Superficie | Dominio |
|---|---|---|
| argomenti.html | Hub temi / pillar | Cms + categorie |
| argomento.html | Hub tema aggregatore | Cms |
| lista-risorse*.html | Pattern lista + evidenza ± categorie | Cms / UI cards |
| novita.html | Lista Novità | `Article` `content_type=post` → `/blog` |
| novita-dettaglio.html | Dettaglio novità | `/blog/{slug}` |
| eventi.html («Vivere il comune») | Lista Eventi | `Article` `content_type=event` → `/events` |
| evento-dettaglio.html | Dettaglio evento | dettaglio evento |

Pagine test JSON già presenti (`tests.novita`, `tests.eventi`, …) = parity
visiva; **non** sostituiscono le route prodotto `/blog` e `/events`.

## Nav (già in JSON — non hardcodare)

```text
header.navigation → Novità=/blog · Eventi=/events
footer.columns + groups.news / groups.living → stessi path
```

SSoT section: `laravel/Modules/Fixcity/config/database/content/sections/{header,footer}.json`
(+ copie `config/local` e `config/eu`). Contratto:
[header-footer-sections-contract.md](./bmad/header-footer-sections-contract.md).

**Verifica 2026-10-16:** Novità/Eventi presenti in SSoT, local ed eu (it/en/de/es).
Nessuna patch JSON obbligatoria — bastava (e basta) aggiornare quei file se
mancassero; oggi sono allineati.

## Must UI (quando IM-SIXTEEN-001 sblocca)

- Breadcrumb, evidenza, badge tipo (notizia/comunicato/avviso), empty state
- Dettaglio: data, indice, «a cura di», ultimo aggiornamento
- Evento: periodo, orari, luogo, patrocinio ≠ sponsor, no banner ads
- WCAG 2.2 AA; dati solo da Blog/Cms

## Ponytail / Blueprint

Nessun modulo Eventi. Wiring Filament = Blog Blueprint
[inbound-blog-events.md](../../../Modules/Blog/docs/blueprints/inbound-blog-events.md).
Story: [inbound-blog-event-listing.story.md](./stories/inbound-blog-event-listing.story.md).
