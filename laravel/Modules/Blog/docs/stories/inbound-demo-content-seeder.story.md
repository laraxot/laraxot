---
id: IM-BLOG-007
title: "Dataset demo Blog/Eventi con commenti e media realistici"
type: story
module: Blog
epic: "II Contenuti pilota"
status: done
priority: P0
owner: module:Blog
scope: "laravel/Modules/Blog/database/, laravel/Modules/Comment/database/factories/"
created: 2026-10-15
updated: 2026-10-15
tags: [bmad, demo, seeder, factory, comment, media, ponytail]
qmd: "DemoContentSeeder Article content_type event Comment Design Comuni images"
related: [IM-BLOG-002, IM-FIXCITY-004, IM-SIXTEEN-001]
issues: []
discussions: []
---

# Dataset demo Blog/Eventi con commenti e media realistici

## Richiesta / scopo
Come stakeholder demo voglio vedere Novità ed Eventi con testi italiani realistici,
foto locali, commenti approvati/pending e risposte — senza Lorem Ipsum.

## Perché
Una demo vuota o con filler distrugge fiducia. Blog + Comment esistono già;
Dominio **post** agnostico; `content_type=post|event` su model `Article`
(no modulo Event). Brainstorming:
[brainstorming-post-vs-article.md](../brainstorming-post-vs-article.md). Event:
[brainstorming-event-module-vs-article-type.md](../../../Fixcity/docs/bmad/inbound-marketing/brainstorming-event-module-vs-article-type.md).

## Acceptance Criteria
- [x] `DemoContentSeeder` con ≥8 `content_type=post` e ≥4 `event`, asset Design Comuni.
- [x] Commenti morph Comment su Article (approvati, pending, reply editor).
- [x] Factory `demoArticle` / `demoEvent` / Comment `forArticle` senza Lorem.
- [x] Guardia `local|testing|demo`; richiamabile da `FixcityDemoSeeder`.
- [x] Seed eseguito con successo (13 post/eventi, 2026-10-15).
- [x] `ThemeArticleQueries` filtra `content_type=post|event` (comingSoon = event + date).

## Tasks / Subtasks
- [x] Catalogo narrativo + immagini + credit.
- [x] Wiring FixcityDemoSeeder step Blog/Eventi.
- [x] `php artisan db:seed --class=Modules\\Blog\\Database\\Seeders\\DemoContentSeeder`
- [x] Smoke conteggi Article/Comment in DB.

## Owned File / Module Scope
Blog seeders/factories; Comment factory; docs Fixcity inbound brainstorming.

## Testing / Verifica
Seed idempotente; nessun Lorem; ambienti non ammessi = no-op.

## Dev Notes
Ponytail: riuso Article + Comment. Modulo Event solo con requisiti operativi. Il seeder usa
`DemoCommentator`, un adapter locale al modulo Blog che aggiunge `CanComment` al modello User senza
far dipendere il core User dal modulo Comment; il morph resta quello del modello User condiviso.

## Verifica 2026-10-15 (multi-agente)

Brainstorm: NO modulo Event. Test Pest `DemoContentSeederTest` (5/5):
8 post + 5 event, credit media, Comment morph (approved/pending/reply),
idempotenza, skip in production.
