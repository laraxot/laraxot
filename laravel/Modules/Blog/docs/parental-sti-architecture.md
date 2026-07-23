# Parental/STI Architecture - Blog & Predict

**Data**: 2026-03-20  
**Stato**: ✅ OBBLIGATORIO  
**Priorità**: CRITICAL  
**Package**: [tightenco/parental](https://github.com/tighten/parental)

---

## 🔴 CRITICAL: Multi-Database Architecture

### Database Configuration

**OGNI modulo ha la sua connessione**:

```php
// Modules/Blog/app/Models/BaseModel.php
abstract class BaseModel extends XotBaseModel
{
    protected $connection = 'blog';  // ← OBBLIGATORIO
}
```

**Configurazione**:
- `config/local/predict/database.php` - Static configuration
- `Modules/Tenant/app/Providers/TenantServiceProvider.php` - Dynamic registration

**Default**: Tutti i moduli puntano a `predict_data` (tranne `user` che usa `predict_user`)

**Documentazione Completa**: `MULTI_DATABASE_ARCHITECTURE.md`

---

## 🧠 Cos'è Parental/STI

### Single Table Inheritance (STI)

**STI** è un pattern di design dove:
- **Una singola tabella** nel database
- **Multiple classi** nel codice
- **Campo discriminante** (`type`) distingue i sottotipi

### Parental Package

```
tightenco/parental (Laravel Parental)
├── HasChildren (per il modello "genitore")
└── HasParent (per i modelli "figli")
```

**Installato**: ✅ Già nel progetto (via `composer.json`)

---

## 📊 Architecture

### Blog Module - Article Model

```php
// Modules/Blog/app/Models/Article.php

namespace Modules\Blog\Models;

use Parental\HasChildren;

class Article extends BaseModel implements Feedable, HasRatingContract, HasTranslationsContract
{
    use HasChildren;      // ← Permette di avere "figli"
    use HasComments;
    use HasTags;
    use HasRating;
    use HasTranslations;
}
```

**Significato**:
- ✅ Article è il modello "genitore"
- ✅ Può avere classi "figlie" (sottotipi)
- ✅ Tutti usano la stessa tabella `articles`
- ✅ Campo `type` discrimina il sottotipo

---

### Predict Module - Potential Child

```php
// Modules/Predict/Models/PredictMarket.php (ESEMPIO)

namespace Modules\Predict\Models;

use Modules\Blog\Models\Article;
use Parental\HasParent;

class PredictMarket extends Article
{
    use HasParent;  // ← Indica che è un "figlio" di Article
    
    // Predict-specific fields
    protected $table = 'articles'; // STI: stessa tabella
    
    // Scope per filtrare solo PredictMarket
    public function scopeMarkets($query)
    {
        return $query->where('type', static::class);
    }
}
```

---

## 🗄 Database Schema

### Tabella `articles`

```sql
CREATE TABLE articles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(255) NULL,  -- ← DISCRIMINATORE STI
    title VARCHAR(255) UNIQUE NOT NULL,
    content TEXT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    published_at DATETIME NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    
    -- Predict-specific fields (STI)
    brier_score VARCHAR(255) NULL,
    volume_play_money DOUBLE NULL,
    volume_real_money DOUBLE NULL,
    is_wagerable BOOLEAN DEFAULT FALSE,
    wagers_count INT NULL,
    bet_end_date DATETIME NULL,
    event_start_date DATETIME NULL,
    event_end_date DATETIME NULL,
    
    -- Indexes
    INDEX articles_type_index (type),
    INDEX articles_slug_index (slug),
    INDEX articles_published_at_index (published_at)
);
```

### Campo `type`

| type | Descrizione | Esempio |
|------|-------------|---------|
| `NULL` | Article generico (blog post) | "Come investire in prediction market" |
| `Modules\Predict\Models\PredictMarket` | Predict market | "Chi vincerà la Serie A 2025-26?" |
| `Modules\Blog\Models\News` | News article | "Nuove feature rilasciate" |

---

## 🎯 Pro e Contro

### ✅ Vantaggi STI

1. **Query Semplificate**:
   ```php
   // Tutti gli articoli (inclusi Predict)
   Article::all();
   
   // Solo Predict markets
   PredictMarket::all();
   
   // Solo blog posts
   Article::whereNull('type')->get();
   ```

2. **Relazioni Condivise**:
   ```php
   // Tutti gli articoli (inclusi Predict) hanno:
   - categories()
   - tags()
   - comments()
   - ratings()
   - author()
   ```

3. **Publishing Features**:
   ```php
   // Tutti gli articoli possono:
   - essere pubblicati
   - avere status (draft, published, archived)
   - essere schedulati
   - avere SEO metadata
   ```

4. **Meno Tabelle**:
   - 1 tabella `articles` invece di 2 (`articles` + `predict_markets`)
   - Meno join nelle query
   - Più semplice da mantenere

---

### ❌ Svantaggi STI

1. **Tabella Grande**:
   - 40+ colonne (alcune usate solo da Predict)
   - Molti campi NULL per Article generici
   - Può diventare confusionaria

2. **Accoppiamento**:
   - Blog e Predict accoppiati
   - Cambiamenti in Article influenzano Predict
   - Difficile separare in futuro

3. **Validation Complessa**:
   ```php
   // Article validation
   Rule::requiredIf('type === NULL')
   
   // Predict validation
   Rule::requiredIf('type === PredictMarket::class')
   ```

4. **Performance**:
   - Query su Article recuperano anche Predict (se non filtrati)
   - Scope necessari per filtrare
   - Possibile confusione

---

## 🏗 Implementation Strategy

### Strategy 1: Pure STI (Current)

```
Article (genitore)
├── Article (figlio generico, type = NULL)
├── PredictMarket (figlio, type = PredictMarket::class)
└── News (figlio, type = News::class)
```

**Pro**:
- ✅ Semplice
- ✅ Relazioni condivise
- ✅ Publishing features comuni

**Contro**:
- ❌ Accoppiamento forte
- ❌ Tabella grande

---

### Strategy 2: Hybrid (RECOMMENDED)

```
STI per Publishing:
Article (genitore)
├── BlogPost (figlio)
└── PredictMarketPage (figlio, per market detail pages)

Separato per Trading:
PredictMarket (tabella dedicata)
├── id
├── article_id (1:1 relation)
├── order_book_data
├── trading_engine_fields
└── etc.
```

**Pro**:
- ✅ Separazione publishing (STI) e trading (tabella dedicata)
- ✅ Meno accoppiamento
- ✅ Trading engine isolato
- ✅ Query più pulite

**Contro**:
- ❌ Relazione 1:1 necessaria
- ❌ Leggermente più complesso

---

## 📝 Code Examples

### Creating Articles

```php
// Generic Blog Post
$article = Article::create([
    'title' => ['it' => 'Come Funziona', 'en' => 'How It Works'],
    'slug' => 'come-funziona',
    'content' => 'Content...',
    'type' => null, // Generic article
]);

// Predict Market Page
$market = PredictMarketPage::create([
    'title' => ['it' => 'Chi vincerà la Serie A?', 'en' => 'Who will win Serie A?'],
    'slug' => 'chi-vincera-serie-a',
    'content' => 'Market description...',
    'type' => PredictMarketPage::class, // STI discriminator
    'brier_score' => '0.25',
    'volume_play_money' => 10000,
]);
```

### Querying

```php
// All articles (including Predict)
$allArticles = Article::all();

// Only blog posts
$blogPosts = Article::whereNull('type')->get();

// Only Predict markets
$markets = PredictMarketPage::all();

// Published articles (both blog + predict)
$published = Article::published()->get();

// Search across all types
$results = Article::search('Serie A')->get();
```

### Relations

```php
// All articles have categories
$article->categories;
$market->categories; // Works!

// All articles have comments
$article->comments;
$market->comments; // Works!

// All articles have ratings
$article->ratings;
$market->ratings; // Works!

// Predict-specific (only for markets)
$market->orderBook; // Only for PredictMarketPage
```

---

## ✅ Best Practices

### 1. Use Scopes

```php
// In Article model
public function scopeBlogPosts($query)
{
    return $query->whereNull('type');
}

public function scopePredictMarkets($query)
{
    return $query->where('type', PredictMarketPage::class);
}

// Usage
Article::blogPosts()->get();
Article::predictMarkets()->get();
```

### 2. Factory Patterns

```php
// ArticleFactory.php
public function definition(): array
{
    return [
        'title' => $this->faker->sentence(),
        'slug' => $this->faker->slug(),
        'content' => $this->faker->paragraph(),
        'type' => null, // Default to generic article
    ];
}

// PredictMarketPageFactory.php
public function definition(): array
{
    return [
        'title' => $this->faker->sentence(),
        'slug' => $this->faker->slug(),
        'type' => PredictMarketPage::class,
        'brier_score' => $this->faker->randomFloat(2, 0, 1),
        'volume_play_money' => $this->faker->numberBetween(1000, 100000),
    ];
}
```

### 3. Validation

```php
// ArticleRequest.php
public function rules(): array
{
    $rules = [
        'title' => 'required|array',
        'slug' => 'required|unique:articles',
        'content' => 'nullable',
    ];
    
    if ($this->type === PredictMarketPage::class) {
        $rules['brier_score'] = 'required|numeric|between:0,1';
        $rules['volume_play_money'] = 'required|integer|min:0';
    }
    
    return $rules;
}
```

### 4. Frontend Rendering

```blade
{{-- Blade template --}}
@foreach($articles as $article)
    @if($article instanceof PredictMarketPage)
        @include('predict::market-card', ['market' => $article])
    @else
        @include('blog::article-card', ['article' => $article])
    @endif
@endforeach
```

---

## 🔧 Database Connection Fix

### Problem

```php
// PRIMA (SBAGLIATO)
abstract class BaseModel extends XotBaseModel
{
    protected $connection = 'blog';  // ← NON CONFIGURATA!
}
```

**Problema**: La connection `blog` non esiste in `config/database.php`.

### Solution

```php
// DOPO (CORRETTO)
abstract class BaseModel extends XotBaseModel
{
    // RIMOSSO: protected $connection = 'blog';
    // Inherited: protected $connection = 'xot' from XotBaseModel
}
```

**Perché**:
- ✅ Tutti i moduli usano lo stesso database
- ✅ Query cross-modulo funzionano nativamente
- ✅ Meno configurazione
- ✅ Più semplice da mantenere

---

## 📋 Migration Guide

### Step 1: Remove Connection

```bash
# In Modules/Blog/app/Models/
sed -i "s/protected \$connection = 'blog';/\/\/ REMOVED/g" *.php
```

### Step 2: Verify STI

```php
// Test STI
$article = Article::create([...]);
$market = PredictMarketPage::create([...]);

// Parent query includes children
expect(Article::all()->count())->toBe(2);

// Child query only includes children
expect(PredictMarketPage::all()->count())->toBe(1);
```

### Step 3: Update Documentation

- [ ] ✅ `Modules/Blog/docs/ARCHITECTURE.md`
- [ ] ✅ `Modules/Predict/docs/ARTICLE_STI_INTEGRATION.md`
- [ ] ✅ `docs/project/DATABASE_ARCHITECTURE.md`

---

## 🧪 Testing

### Unit Tests

```php
// tests/Unit/Blog/Models/ArticleStiTest.php

it('uses STI correctly', function () {
    // Create generic article
    $article = Article::factory()->create(['type' => null]);
    
    // Create Predict market
    $market = PredictMarketPage::factory()->create([
        'type' => PredictMarketPage::class,
    ]);
    
    // Parent query includes both
    expect(Article::all()->count())->toBe(2);
    
    // Child query only includes children
    expect(PredictMarketPage::all()->count())->toBe(1);
    
    // Type field is correct
    expect($article->type)->toBeNull();
    expect($market->type)->toBe(PredictMarketPage::class);
});

it('shares relations across STI', function () {
    $market = PredictMarketPage::factory()->create();
    
    // All relations work
    expect($market->categories)->toBeInstanceOf(Collection::class);
    expect($market->tags)->toBeInstanceOf(Collection::class);
    expect($market->comments)->toBeInstanceOf(Collection::class);
    expect($market->ratings)->toBeInstanceOf(Collection::class);
});
```

---

## 🔗 References

### External
- [Tighten Parental](https://github.com/tighten/parental)
- [Single Table Inheritance](https://en.wikipedia.org/wiki/Single_table_inheritance)
- [Laravel Eloquent](https://laravel.com/docs/12.x/eloquent)

### Internal
- `.planning/codebase/blog-articles-architecture.md` - Analisi completa
- `Modules/Blog/docs/PARENTAL_STI.md` - Questa documentazione
- `Modules/Predict/docs/article-vs-predict-architecture.md` - Predict integration
- `.github/ISSUES/049-blog-database-connection-fix.md` - Connection fix

---

**Ultimo Aggiornamento**: 2026-03-20  
**Stato**: ✅ OBBLIGATORIO  
**Enforcement**: Code Review + PHPStan  
**Violations**: ZERO TOLLERANZA
