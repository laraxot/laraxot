---
id: IM-BLOG-006
title: "Playbook e checklist come risorse operative"
type: story
module: Blog
epic: "V Sistema editoriale e misurazione"
status: backlog
priority: P1
owner: module:Blog
scope: "laravel/Modules/Blog/docs/"
created: 2026-10-15
updated: 2026-10-15
tags: [bmad, inbound-marketing, playbook, checklist, afc, asso]
qmd: "playbook checklist strumento CTA hub Cms Article Blog FixCity"
related: [IM-CMS-001, IM-BLOG-004, IM-BLOG-005, IM-FIXCITY-003]
issues: []
discussions: []
---

# Playbook e checklist come risorse operative

## Richiesta / scopo
Come redazione civica vogliamo trasformare teoria e guide in **strumenti**
brevi (checklist/playbook) collegati agli articoli, così ogni pezzo porta
a una decisione o a un'azione concreta.

## Perché
AFC offre checklist e tool; Asso collega articoli a playbook con CTA
«parti da dove si rompe». FixCity adatta: poche risorse, accessibili,
senza gate e senza UTM invasivi.

## Acceptance Criteria
- [ ] Definiti 3–5 asset pilota (es. checklist ticket completo, guida a11y
      evento, checklist adozione operatori, FAQ stato segnalazione).
- [ ] Ogni asset ha owner, pillar, formato (MD/PDF/sezione Cms) e CTA.
- [ ] Hub Cms (IM-CMS-001) elenca solo risorse approvate («poche e utili»).
- [ ] Articoli evergreen linkano al massimo un asset primario.

## Tasks / Subtasks
- [ ] Inventario asset esistenti nei moduli/temi (no duplicare).
- [ ] Outline contenuti mancanti; versionare in docs Blog.
- [ ] Criteri qualità: lunghezza breve, WCAG, linguaggio chiaro, no
      manipolazione.

## Owned File / Module Scope
`laravel/Modules/Blog/docs/` (+ link a Cms hub). Presentazione FO via
Sixteen/Cms, non in questa story.

## Testing / Verifica
Review: uno stakeholder completa l'asset in tempo dichiarato; a11y check
base sul testo.

## Dipendenze / blocchi
IM-BLOG-001; coordina IM-CMS-001 e IM-MEDIA-001 per diritti.

## Dev Notes
Non creare CMS parallelo nel modulo Blog. Riusare Article + sezioni Cms.
Pattern: [asso-pattern-deep-study.md](../../../Fixcity/docs/bmad/inbound-marketing/asso-pattern-deep-study.md).

## Evidenza Asso / AFC

Playbook = bridge tra contenuto e decisione («parti da dove si rompe»).
Hub ≤6 risorse; checklist presa in carico da pattern AFC reclami + Asso CRM.
