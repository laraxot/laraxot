---
title: "no app/Support — business logic in QueueableAction"
type: concept
tags: [blog, actions, queueable-action, support, refactor]
created: 2026-07-12
updated: 2026-10-22
qmd: "Blog module no app Support ArticleDelegates QueueableAction"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/372"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/273"
related:
  - ../../../../../../docs/second-brain/process/no-app-support-queueable-actions.md
  - ../../../../../../docs/wiki/rules/queueable-action-trait-mandatory.md
status: draft
---

# no `app/Support/` — business logic in QueueableAction

## Scopo

Nel modulo Blog **non** esiste più `app/Support/`. Presentazione articolo e feed RSS sono **Spatie QueueableAction** sotto `app/Actions/Article/`.

## Stato (2026-07-13)

Tutti i 6 file `app/Support/*.php` rinominati a `.bak` (consumatori già su Action).

## Migrazione (2026-07-12)

| Legacy `app/Support/` | Action |
|---------------------|--------|
| `ArticleDelegates` | eliminato — consumer chiamano Action direttamente |
| `ArticleTranslationResolver` | `ResolveArticleTranslationAction` |
| `ArticleMainImageResolver` | `ResolveArticleMainImageFromAttributesAction` |
| `ArticleContentBlockFilter` | `FilterArticleContentBlocksOnlyAction`, `FilterArticleContentBlocksExceptAction` |
| `ArticleTimeLeftFormatter` | `FormatArticleTimeLeftForHumansAction` |
| `ArticleReadTimeFormatter` | `FormatArticleHumanReadTimeAction` |
| (da `ArticleDelegates`) | `ConvertArticleToFeedItemAction`, `FormatArticlePublishedDateAction`, `ResolveArticleThumbnailAction`, `ResolveArticleMainImageUrlAction` |

## Perché

- **Religione Laraxot:** no classi statiche di dominio in `Support/`
- **Testabilità:** `app(FooAction::class)->execute()`
- **Coda:** stesso contratto sync/async con `QueueableAction`

## Collegamenti

- [queueable-action-trait-mandatory](../../../../../../docs/wiki/rules/queueable-action-trait-mandatory.md)

## Wave 2026-10-22

Article presentation Support → `Actions/Article/*` + `Adapters/ArticlePresentationAdapter`.
View composers: `View/Composers/{Data,Queries,Composition,Rendering}/` (ex `View/Composers/Support/`).
`ArticleDelegatesAction` faux rimosso (zero caller).

## View composers (2026-10-22 deep audit)

Cartella `View/Composers/Support/` eliminata. Classi rinomiate senza suffisso Support:
`ThemeArticleBag`, `ThemeCategoryBag`, `ThemeCatalogBag`, `ThemeBannerBag`,
`ThemeComposerBag` sotto `Data/` / `Composition/`.
