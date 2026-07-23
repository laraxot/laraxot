# Modelli del Modulo Blog

## Modelli Attivi

### Article
Modello principale per la gestione degli articoli blog.

**Caratteristiche**:
- Supporto multilingua
- Categorizzazione
- Tagging
- Media attachments
- SEO optimization

**Documentazione**: *Da completare*

### Profile
Estensione del profilo utente specifico per il modulo Blog.

**Caratteristiche**:
- Relazione con User
- Gestione avatar
- Crediti utente
- Rating system integration

**File**: `app/Models/Profile.php`

### Category
Gestione categorie per gli articoli.

**Caratteristiche**:
- Struttura gerarchica
- Slug per URL friendly
- Icone personalizzabili

**File**: `app/Models/Category.php`

### Comment
Sistema di commenti per articoli.

**Caratteristiche**:
- Nested comments (thread)
- Moderazione
- Spam protection

**File**: `app/Models/Comment.php`

### Banner
Gestione banner pubblicitari o promozionali.

**File**: `app/Models/Banner.php`

### Menu
Sistema di navigazione e menu dinamici.

**File**: `app/Models/Menu.php`

## Modelli Disabilitati

### Transaction (Disabilitato: 2025-10-15)

**Motivo**: Sistema di crediti/transazioni non necessario per questo progetto

**File Mantenuti**:
- `app/Models/Transaction.php.old` - Modello originale
- `app/Models/Transaction.to_predict.old` - Variante prediction
- `database/factories/TransactionFactory.php.disabled` - Factory disabilitata

**Nota**: In passato esistevano duplicati tra `database/factories/` e `database/Factories/`, ma ora si utilizza solo la convenzione corretta minuscola.

**Funzionalità**:
Il modello Transaction gestiva un sistema di crediti per utenti con le seguenti feature:
- Tracking transazioni crediti
- Relazioni polimorfiche con altri modelli
- Integrazione con sistema rating
- Storico movimenti

**Perché Disabilitato**:
- ❌ Nessun sistema di crediti attivo nel progetto
- ❌ Nessuna gamification implementata
- ❌ Complessità non necessaria
- ✅ Architettura semplificata

**Se Servisse in Futuro**:
1. Rinominare file `.disabled` / `.old` rimuovendo suffisso
2. Verificare compatibilità con codice corrente
3. Creare migration per tabella `transactions`
4. Aggiornare relazioni in Profile se necessarie
5. Testare integrazioni con Rating/Credits

**Documentazione Completa**: [transaction-removal.md](./transaction-removal.md)

## Factory Available

Factory attive nel modulo (directory `database/factories/`):

- ✅ `ArticleFactory.php`
- ✅ `BannerFactory.php`
- ✅ `CategoryFactory.php`
- ✅ `CategoryPostFactory.php`
- ✅ `CommentFactory.php`
- ✅ `MenuFactory.php`
- ✅ `PostViewFactory.php`
- ✅ `ProfileFactory.php`
- ✅ `TextWidgetFactory.php`
- ✅ `UpvoteDownvoteFactory.php`

Factory disabilitate:
- ⏸️ `TransactionFactory.php.disabled`

## Note sulla Struttura

### Convenzione Naming Database

Le directory del database seguono la convenzione Laravel standard:
- `database/factories/` (minuscolo) - **CORRETTO** - Standard Laravel
- `database/migrations/` (minuscolo) - **CORRETTO** - Standard Laravel  
- `database/seeders/` (minuscolo) - **CORRETTO** - Standard Laravel

**Importante**: Non utilizzare mai le versioni con iniziale maiuscola (`Factories`, `Migrations`, `Seeders`).

**TODO Futuro**: Consolidare in una sola directory.

## Collegamenti

### Documentazione Locale
- [Transaction Removal](./transaction-removal.md)
- [Blog Module README](../README.md)
- [Structure](../structure.md)

### Documentazione Root
- [Transaction Fix](../../../../docs/transaction-removal-fix-2025-10-15.md)
- [Architecture](../../../../docs/architecture/)

### Moduli Correlati
- [Rating Module](../../../Rating/docs/README.md) - Integrazione rating
- [User Module](../../../User/docs/README.md) - Gestione utenti
- [Media Module](../../../Media/docs/README.md) - Gestione media

## Convenzioni

### Naming
- File modelli: PascalCase (es. `ArticleCategory.php`)
- Tabelle database: snake_case plurale (es. `article_categories`)
- Factory: `{Model}Factory.php`

### Struttura Base
```php
<?php

declare(strict_types=1);

namespace Modules\Blog\Models;

class ModelName extends BaseModel
{
    protected $connection = 'blog';
    
    protected $fillable = [
        // ...
    ];
}
```

### Best Practices
1. ✅ Sempre `declare(strict_types=1)`
2. ✅ Type hints espliciti per tutti i metodi
3. ✅ PHPDoc completo per proprietà e relazioni
4. ✅ Usare enums per stati fissi
5. ✅ Factory per ogni modello

## Principi Applicati

> **DRY**: Una responsabilità, un modello  
> **KISS**: Logica semplice, relazioni chiare  
> **YAGNI**: Disabilitare ciò che non serve

