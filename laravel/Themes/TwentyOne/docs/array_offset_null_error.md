# Errore: Accesso a offset di array su null

## Contesto

- File: `Themes/TwentyOne/resources/views/components/blocks/article_list/play_money_markets/list_of_markets/article.blade.php`
- Errore: `ErrorException: Trying to access array offset on null`

## Soluzione

Per risolvere l'errore, è necessario utilizzare l'helper `data_get` per accedere in modo sicuro al campo `content_blocks` ed estrarre il valore `data.view`. Esempio di implementazione:

```php
// Recupero sicuro di content_blocks per la lingua corrente
$articleRatingsBlock = data_get($article, 'content_blocks.' . app()->getLocale(), []);
// Estrazione del primo blocco di tipo rating
$firstRatingBlock = collect($articleRatingsBlock)->firstWhere('type', 'rating');
// Recupero sicuro del viewType
$articleRatingsBlockView = data_get($firstRatingBlock, 'data.view', 'predict::components.blocks.rating.rating_with_options');
```

## Passi di implementazione

1. Aggiornare `article.blade.php` sostituendo l'accesso diretto a `content_blocks` con `data_get`.
2. Utilizzare `collect()->firstWhere` per trovare il blocco di tipo `rating`.
3. Testare localmente e verificare l'assenza dell'errore.
4. Aggiornare la documentazione del tema per riflettere la modifica.
