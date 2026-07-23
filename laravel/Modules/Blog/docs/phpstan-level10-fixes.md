# Correzioni PHPStan Livello 10 - Modulo Blog

Questo documento traccia gli errori PHPStan di livello 10 identificati nel modulo Blog e le relative soluzioni implementate.

## Errori Identificati

### 1. Tipi mixed in ArticleService.php

```php
public function getBySlug(mixed $slug): mixed
{
    // ...
}
```

**Problema**: Il metodo `getBySlug` utilizza il tipo `mixed` sia per il parametro che per il valore di ritorno, rendendo difficile la tipizzazione statica e aumentando il rischio di errori a runtime.

**Soluzione**:
1. Definire tipi più specifici in base alla logica effettiva del metodo:
   ```php
   /**
    * @return \Modules\Blog\Models\Article|null
    */
   public function getBySlug(string $slug)
   {
       // ...
   }
   ```

### 2. Uso di mixed in Post.php e Category.php

I modelli `Post.php` e `Category.php` contengono annotazioni PHPDoc con proprietà di tipo `mixed`:

```php
/**
 * @property mixed $content_html
 * @property mixed $featured_image_url
 */
```

**Problema**: La mancanza di tipi specifici per queste proprietà può causare errori durante l'analisi statica.

**Soluzione da implementare**:
1. Determinare i tipi effettivi di queste proprietà analizzando il codice
2. Aggiornare le annotazioni PHPDoc con tipi più specifici:
   ```php
   /**
    * @property string $content_html
    * @property string|null $featured_image_url
    */
   ```

### 3. Tipi di ritorno impliciti in PostController.php

```php
public function show($id)
{
    // ...
}
```

**Problema**: Metodi senza un tipo di ritorno esplicito che potrebbero restituire diversi tipi di valori.

**Soluzione da implementare**:
1. Aggiungere tipi di ritorno espliciti a tutti i metodi del controller:
   ```php
   public function show($id): \Illuminate\View\View|\Illuminate\Http\RedirectResponse
   {
       // ...
   }
   ```

## Principi Applicati

1. **Specificità dei tipi**: Preferire sempre tipi specifici rispetto a `mixed`.
2. **Documentazione completa**: Fornire annotazioni PHPDoc accurate per tutte le proprietà e i metodi.
3. **Tipi di ritorno espliciti**: Dichiarare esplicitamente i tipi di ritorno per tutti i metodi.
4. **Consistenza**: Mantenere un approccio coerente alla gestione dei tipi in tutto il modulo.

## Prossimi Passi

1. Completare la revisione di tutti i file nel modulo Blog per identificare ulteriori usi di `mixed` o tipi mancanti.
2. Implementare le correzioni proposte per i modelli e i controller.
3. Eseguire l'analisi PHPStan a livello 10 per verificare che le correzioni risolvano effettivamente gli errori.
4. Estendere i principi applicati ad altri moduli del progetto. 