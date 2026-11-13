---
title: "Blog come canale inbound civico"
type: bmad-reference
status: discovery
updated: 2026-10-15
tags: [docs, blog]
qmd: "inbound marketing blog"
---

Il Blog module è il luogo naturale per guide, casi d'uso, FAQ e changelog
narrati che aiutano cittadini e PA a capire FixCity. Ogni articolo deve avere
destinatario, problema, evidenza, data di revisione e CTA non aggressiva.

## Dossier canonico (2026-10-15)

- Scopo: [purpose.md](../../Fixcity/docs/bmad/inbound-marketing/purpose.md)
- [inbound-marketing dossier](../../Fixcity/docs/bmad/inbound-marketing/README.md)
- Miglioramenti: [improvement-reasoning.md](../../Fixcity/docs/bmad/inbound-marketing/improvement-reasoning.md)
- Asso deep: [asso-pattern-deep-study.md](../../Fixcity/docs/bmad/inbound-marketing/asso-pattern-deep-study.md)
- Analisi AFC: [afc-formazione-pattern-analysis](../../Fixcity/docs/bmad/inbound-marketing/afc-formazione-pattern-analysis.md)
- Adattamento locale: [afc-pattern-adaptation.md](./afc-pattern-adaptation.md)
- Blueprint Filament: [blueprints/inbound-blog-events.md](./blueprints/inbound-blog-events.md)
- Audit Ponytail: [blueprints/inbound-blog-events-ponytail-audit.md](./blueprints/inbound-blog-events-ponytail-audit.md)
- Stories: [playbook](./stories/inbound-playbook-resources.story.md) ·
  [demo seeder](./stories/inbound-demo-content-seeder.story.md) · indice Fixcity
- Dominio: **post** agnostico · `content_type=post|event` · model PHP `Article`
  ([brainstorming post](./brainstorming-post-vs-article.md))
- Verdict Event: **no** modulo Event ora
  ([brainstorming](../../Fixcity/docs/bmad/inbound-marketing/brainstorming-event-module-vs-article-type.md))
- Seed: `php artisan db:seed --class="Modules\\Blog\\Database\\Seeders\\DemoContentSeeder"`
- Nota storica (ancorata a footer-sponsorship):
  [`inbound-marketing.md`](../../../../bmad-output/footer-sponsorship/inbound-marketing.md)

Non aggiungere tracking, newsletter, sponsor o campi senza policy e readiness:
la discovery precede qualunque implementazione.

Piano editoriale: [editorial-plan](../../Fixcity/docs/bmad/inbound-marketing/editorial-plan-afc-aferrari.md).
