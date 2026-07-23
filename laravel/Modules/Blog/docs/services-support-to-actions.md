---
title: Blog — Services/Support → Actions
---

# Migrazione `app/Support/` → `app/Actions/Article/`

`app/Support/` non esiste più in questo modulo. `ArticleDelegates` era una facade statica che smistava a 5 resolver in `Support/`; ciascun metodo è ora una `QueueableAction` dedicata con `execute()` in `app/Actions/Article/`.

## Mapping

| Legacy (`.bak`) | Metodo | Nuova Action |
|---|---|---|
| `Support/ArticleDelegates.php` | `translation()` | `Actions/Article/GetArticleTranslationAction` |
| `Support/ArticleDelegates.php` | `humanReadTime()` | `Actions/Article/FormatArticleReadTimeAction` |
| `Support/ArticleDelegates.php` | `timeLeftForHumans()` | `Actions/Article/FormatArticleTimeLeftAction` |
| `Support/ArticleDelegates.php` | `onlyContentBlocks()` | `Actions/Article/FilterArticleContentBlocksOnlyAction` |
| `Support/ArticleDelegates.php` | `exceptContentBlocks()` | `Actions/Article/FilterArticleContentBlocksExceptAction` |
| `Support/ArticleDelegates.php` | `mainImage()` | `Actions/Article/ResolveArticleMainImageAction` |
| `Support/ArticleDelegates.php` | `toFeedItem()` | `Actions/Article/GetArticleFeedItemAction` |
| `Support/ArticleDelegates.php` | `formattedDate()` | `Actions/Article/FormatArticleDateAction` |
| `Support/ArticleDelegates.php` | `thumbnail()` | `Actions/Article/GetArticleThumbnailAction` |
| `Support/ArticleDelegates.php` | `mainImageUrl()` | `Actions/Article/GetArticleMainImageUrlAction` |
| `Support/ArticleTranslationResolver.php` | `resolve()` | confluito in `GetArticleTranslationAction` |
| `Support/ArticleReadTimeFormatter.php` | `fromAttributes()` | confluito in `FormatArticleReadTimeAction` |
| `Support/ArticleTimeLeftFormatter.php` | `forHumans()` | confluito in `FormatArticleTimeLeftAction` |
| `Support/ArticleContentBlockFilter.php` | `only()`/`except()` | split in 2 Actions (una execute() per use case, non 2 metodi su una classe) |
| `Support/ArticleMainImageResolver.php` | `fromAttributes()` | confluito in `ResolveArticleMainImageAction` |

## Perché lo split di `ArticleContentBlockFilter`

QueueableAction impone un solo entrypoint pubblico (`execute()`). Una classe con `only()`/`except()` diventa due Action separate invece di un'unica classe con due metodi — evita ambiguità su quale sia "il" metodo chiamabile via `app(Classe::class)->execute()`.

## Chiamanti aggiornati

- `app/Models/Article.php` — 9 chiamate `ArticleDelegates::xxx()` → `app(XxxAction::class)->execute(...)`.
- `app/Models/Concerns/ArticleFeedable.php` — `ArticleDelegates::toFeedItem()` → `app(GetArticleFeedItemAction::class)->execute($this)`.

## Quality gate

- `php -l` su tutti i file toccati: OK.
- `phpstan analyse Modules/Blog`: 1 errore, preesistente (`@mixin` ignore-pattern non matchato, non collegato a questa conversione).
- Nessun test esistente copriva le classi Support rimosse (nessun test da aggiornare).
