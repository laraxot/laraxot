---
title: "Blog — BMAD dossier"
type: bmad-module-dossier
module: Blog
status: baseline
updated: 2026-10-07
tags: [bmad, blog, content]
qmd: "Blog module purpose architecture PRD epics gaps release"
issues: ["https://github.com/laraxot/base_fixcity_fila5/issues/383"]
discussions: ["https://github.com/laraxot/base_fixcity_fila5/discussions/392"]
---
# Blog BMAD dossier

## Kit BMAD owner (canonico)

- [BMAD Planning](../bmad-planning.md)
- [BMAD Brainstorming](../bmad-brainstorming.md)
- [Filament blueprint](./filament-blueprint.md)
- [Project context](./project-context.md)
- [Decision log](./decision-log.md)
**Purpose / product brief:** publish municipal/editorial articles, categories, tags and feeds without mixing editorial content with Ticket workflow.
**Architecture:** module-owned Eloquent models, Filament administration, Cms/Lang/Seo integration; no domain logic in themes.
**PRD:** article lifecycle, draft/publish visibility, localized metadata, author permissions and feed correctness.
**Epics:** editorial lifecycle; moderation/permissions; localization and SEO.
**Discovered gaps:** verify publish scheduling, tenant scoping, media rights, moderation and feed/sitemap integration against current models/tests.
**Release gate:** editor/guest smoke, authorization denial, locale fallback, feed validation and PHPStan/Pest evidence.

## Inbound marketing

- [Contratto `content_type` e Parental STI](content-type-contract.md)
- [Governance](../stories/inbound-editorial-governance.story.md)
- [Authoring blog/eventi](../stories/inbound-article-authoring.story.md)
- [Validazione date evento](../stories/inbound-event-date-validation.story.md)
- [Qualità e CTA](../stories/inbound-editorial-quality-cta.story.md)
- [Pillar e tassonomia](../stories/inbound-taxonomy-pillars.story.md)
- [Dossier coordinatore Fixcity](../../../Fixcity/docs/bmad/inbound-marketing/README.md)

## Front-office (presentazione Sixteen)

- Lista/dettaglio Novità: [inbound-fo-blog](../../../../Themes/Sixteen/docs/bmad/blueprints/inbound-fo-blog.md)
- Lista/dettaglio Eventi: [inbound-fo-events](../../../../Themes/Sixteen/docs/bmad/blueprints/inbound-fo-events.md)
- Hub tema: [inbound-fo-index](../../../../Themes/Sixteen/docs/bmad/inbound-fo-index.md)
- Commenti FO: [fo-comments](../../../Comment/docs/bmad/fo-comments/)
- Indice modulo: [../00-INDEX.md](../00-index.md)