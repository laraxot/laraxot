---
id: IM-BLOG-002
title: "Authoring Filament per contenuti blog ed eventi"
type: story
module: Blog
epic: "III Authoring Filament"
status: backlog
priority: P1
owner: module:Blog
scope: "laravel/Modules/Blog/app/Filament/Resources/ArticleResource/"
created: 2026-10-15
updated: 2026-10-15
tags: [bmad, filament, blueprint, blog, event]
qmd: "Filament ArticleResource content_type post event authoring"
related: [IM-BLOG-001, IM-BLOG-003, IM-BLOG-004]
issues: []
discussions: []
---
# Authoring Filament per post ed eventi

## Richiesta / scopo
Come editor voglio creare e revisionare **post** ed eventi usando l'`ArticleResource`
esistente, senza introdurre un modello Event duplicato.

## Perché
Nel dominio il concetto agnostico è **post** (`content_type=post|event`).
La classe PHP resta `Article` (Ponytail). La colonna Parental `type` non è
tassonomia. Vedi [brainstorming-post-vs-article.md](../brainstorming-post-vs-article.md).

## Acceptance Criteria
- [ ] `content_type=post` mostra i campi editoriali e `content_type=event` mostra i campi evento pertinenti.
- [ ] Il form conserva localizzazione, contenuti a blocchi, autore e media esistenti.
- [ ] Gli stati draft/published/closed restano coerenti con il lifecycle corrente.
- [ ] Autorizzazione, tenant scope e audit sono verificati prima del rilascio.

## Tasks / Subtasks
- [ ] Mappare schema Filament esistente e riusare i componenti presenti.
- [ ] Aggiungere solo la condizione minima sui campi per tipo.
- [ ] Coprire create/edit/deny con test del modulo.

## Owned File / Module Scope
`laravel/Modules/Blog/app/Filament/Resources/ArticleResource/` e test Blog strettamente necessari.

## Testing / Verifica
PHPStan scoped, Pest resource, controllo manuale dei due tipi e `view:cache` quando si tocca Blade.

## Dipendenze
IM-BLOG-001; IM-BLOG-003 per le regole sulle date.
