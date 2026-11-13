---
title: "Adattamento pattern AFC al modulo Blog"
type: concept
module: Blog
status: decision-input
updated: 2026-10-15
tags: [inbound, afc, blog, eventi, bmad]
qmd: "afc → blog fixcity blog"
---

# AFC → Blog FixCity

## Perché

[AFC Formazione](https://www.afcformazione.it/) dimostra che inbound funziona con
promessa verticale, contenuti sul *problema*, risorse poche e CTA progressive.
FixCity riusa lo stesso *perché*, non lo stack WordPress.

## Mapping editoriale

| Pattern AFC | Campo / pratica Blog |
|---|---|
| Titolo sul comportamento concreto | `title` + `excerpt` orientati al problema civico |
| Metodo in step | `content_blocks` con lista numerata / how-to |
| Lead magnet | CTA verso guida Cms o evento `content_type=event` (non PDF gated di default) |
| Hub risorse | non in Blog: pagina Cms; Blog linka |
| Autore credibile | relazione `author_id` + bio sobria |
| Evento | `content_type=event` + `event_start_date` / `event_end_date` |

## Tipi contenuto pilota (esempi)

**Blog:** «Come una segnalazione senza foto rallenta il Comune»; «Checklist
accessibilità per operatori»; «Cosa vede il cittadino dopo l’invio».

**Event:** webinar «Adottare FixCity in 60 minuti»; laboratorio quartiere
mappatura; sessione WCAG per redattori.

## Confini

- Nessuna migrazione nuova nel pilota
- Nessuna newsletter da questo modulo (Notify + Gdpr)
- Blueprint: [blueprints/inbound-blog-events.md](./blueprints/inbound-blog-events.md)
- Dossier: [inbound-marketing](../../Fixcity/docs/bmad/inbound-marketing/README.md)
- Analisi AFC: [afc-formazione-pattern-analysis](../../Fixcity/docs/bmad/inbound-marketing/afc-formazione-pattern-analysis.md)

## Piano 90 giorni (SSoT)

[editorial-plan-afc-aferrari](../../Fixcity/docs/bmad/inbound-marketing/editorial-plan-afc-aferrari.md)
— include catalogo titoli, template, eventi, metriche da studio AFC reclami +
aferrari KPI/journey (2026-10-15).

### Mapping reclami → ticket (A2)

I 4 passaggi AFC (fatto → impatto → presa in carico → aggiornamento) sono il
modello per l'articolo pilastro A2 e per la checklist operatore nell'hub Cms.
