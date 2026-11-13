---
id: IM-BLOG-001
title: "Governance editoriale per blog ed eventi"
type: story
module: Blog
epic: "I Governance editoriale"
status: blocked
priority: P1
owner: module:Blog
scope: "laravel/Modules/Blog/docs/"
created: 2026-10-15
updated: 2026-10-15
tags: [bmad, inbound-marketing, governance]
qmd: "blog eventi owner revisore calendario policy editoriale FixCity"
related: [IM-BLOG-002, IM-BLOG-003, IM-GDPR-001]
issues: []
discussions: []
---
# Governance editoriale per blog ed eventi

## Richiesta / scopo
Come organizzazione civica vogliamo sapere chi propone, revisiona e pubblica un contenuto, così ogni articolo o evento ha una responsabilità verificabile.

## Perché
I pattern studiati in AFC, AFerrari e Asso della Vendita funzionano perché promessa, metodo e CTA sono coerenti. In FixCity questo va adattato a fiducia pubblica, accessibilità, privacy e neutralità istituzionale.

## Acceptance Criteria
- [ ] Ogni contenuto ha owner, revisore, pubblico, pillar, fonti e CTA primaria.
- [ ] La checklist distingue articolo, evento e comunicazione istituzionale.
- [ ] La pubblicazione senza revisione o senza fonti è bloccata dal processo editoriale.
- [ ] Il calendario pilota di 90 giorni è approvato e versionato nel modulo owner.

## Tasks / Subtasks
- [ ] Nominare owner e revisore per tenant.
- [ ] Formalizzare tono, fonti, diritti media e criteri di correzione.
- [ ] Documentare calendario e retrospettiva mensile.

## Owned File / Module Scope
Solo `laravel/Modules/Blog/docs/` e relativo `ArticleResource` quando la story sarà pronta allo sviluppo.

## Testing / Verifica
Review documentale con checklist; nessun test runtime finché non è assegnato l'owner editoriale.

## Dipendenze / blocchi
Bloccata da nomina owner, privacy evento e calendario pilota.

## Dev Notes
Non introduce una tabella editoriale né un nuovo modulo.
