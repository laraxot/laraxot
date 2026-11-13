---
title: PHPStan Blog — etichetta del filtro categoria in ArticlesTable (cast.string)
type: story
module: Blog
status: done
created: 2026-11-10
updated: 2026-11-10
qmd: "phpstan blog cast.string getTranslation category title filtro ArticlesTable SafeStringCastAction"
related:
  - ./5.61.phpstan-blog-fix.story.md
  - ../../../../../bashscripts/ai/wiki/rules/11-forms/cast-actions-usage.md
---

# PHPStan Blog: etichetta del filtro categoria

## Scopo

Nella tabella articoli il filtro `category` mostra il titolo tradotto della categoria nella lingua corrente. `categories` non ha `name`: il nome è `title`, un JSON traducibile (Spatie `HasTranslations`).

## Causa

Errore emerso dopo il pull dei moduli (2026-11-10):

| Riga | Identifier | Messaggio |
|---|---|---|
| `ArticlesTable.php:68` | `cast.string` | Cannot cast mixed to string. |

`Category::getTranslation()` viene da Spatie e restituisce `mixed`. Il cast `(string)` su un `mixed` non è sicuro: un array o un oggetto produrrebbe un errore o un'etichetta sbagliata.

## Modifica

`app/Filament/Resources/ArticleResource/Tables/ArticlesTable.php`: il closure di `getOptionLabelFromRecordUsing` usa `SafeStringCastAction::cast(...)` (Xot) al posto di `(string)`, come chiede la regola sui cast centralizzati. Se manca la traduzione, l'etichetta è una stringa vuota.

## Verifica

```bash
cd laravel && ./vendor/bin/phpstan analyse Modules/Blog   # [OK] No errors
vendor/bin/pint --test Modules/Blog/app/Filament/Resources/ArticleResource/Tables/ArticlesTable.php   # passed
```
