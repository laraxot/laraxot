# Laravel NestedSet Migration Best Practices

## Overview

Questo documento descrive le best practices per implementare migrazioni con strutture ad albero (nested sets) utilizzando il pacchetto `kalnoy/laravel-nestedset` nel contesto del progetto TechPlanner.

## Cos'è un Nested Set

Un nested set è una struttura dati gerarchica che rappresenta relazioni genitore-figlio in modo efficiente. A differenza delle tabelle di giunzione tradizionali, i nested set utilizzano:

- **_lft** (left): valore del nodo sinistro
- **_rgt** (right): valore del nodo destro
- **parent_id**: ID del nodo genitore
- **depth**: profondità del nodo nella gerarchia

Questo approccio permette query gerarchiche molto più veloci rispetto alle join ricorsive.

## Installazione

```bash
composer require kalnoy/laravel-nestedset
```

## Pattern di Migrazione Base

### 1. Struttura Standard

```php
<?php

use Illuminate\Database\Schema\Blueprint;
use Kalnoy\Nestedset\NestedSet;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class extends XotBaseMigration
{
    protected ?string $model_class = Category::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            
            // Campi personalizzati
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            
            // NestedSet aggiunge automaticamente i campi necessari
            NestedSet::columns($table);
            
            // Opzionale: soft deletes
            $table->softDeletes();
            
            $table->timestamps();
        });

        // -- UPDATE --
        $this->tableUpdate(function (Blueprint $table): void {
            // Per aggiungere nuovi campi in modo sicuro
            if (!$this->hasColumn('slug')) {
                $table->string('slug')->nullable()->unique();
            }
            
            $this->updateTimestamps($table, true);
        });
    }

    public function down(): void
    {
        $this->tableDrop();
    }
}
```

### 2. Con Campi Personalizzati

```php
<?php

return new class extends XotBaseMigration
{
    protected ?string $model_class = Category::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            
            // Campi aggiuntivi prima di NestedSet::columns()
            $table->string('slug')->nullable()->unique();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            
            // NestedSet columns
            NestedSet::columns($table);
            
            $table->timestamps();
        });
    }
}
```

### 3. Con Relazioni Polimorfe

```php
<?php

return new class extends XotBaseMigration
{
    protected ?string $model_class = Category::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            
            // Campi standard
            $table->string('name');
            $table->string('slug')->unique();
            
            // NestedSet per gerarchia
            NestedSet::columns($table);
            
            // Relazioni polimorfe
            $table->morphs('modelable');
            $table->unsignedBigInteger('modelable_id');
            $table->string('modelable_type');
            
            $table->timestamps();
        });
    }
}
```

## Best Practices

### 1. Sempre Usare `NestedSet::columns()`

**✅ CORRETTO**: Il metodo `NestedSet::columns()` aggiunge automaticamente i campi necessari:
- `_lft`
- `_rgt` 
- `parent_id`

**❌ SBAGLIATO**: Definire manualmente i campi nested set
```php
// ❌ NON FARE QUESTO!
$table->unsignedBigInteger('_lft');
$table->unsignedBigInteger('_rgt');
$table->unsignedBigInteger('parent_id');
```

### 2. Nomenclatura Campi

Seguire una nomenclatura consistente:

```php
// ✅ CORRETTO
$table->string('name');
$table->string('slug');
$table->text('description');

// ✅ CORRETTO per campi speciali
$table->integer('sort_order')->default(0);
$table->boolean('is_active')->default(true);
$table->decimal('price', 10, 2)->nullable();
```

### 3. Indici per Performance

```php
public function up(): void
{
    $this->tableCreate(function (Blueprint $table): void {
        $table->id();
        $table->string('name');
        
        // NestedSet columns
        NestedSet::columns($table);
        
        // Indici per performance
        $table->index('_lft');
        $table->index('_rgt');
        $table->index('parent_id');
        
        // Indici composti per query comuni
        $table->index(['parent_id', 'is_active']);
        
        $table->timestamps();
    });
}
```

### 4. Soft Deletes con NestedSet

```php
public function up(): void
{
    $table->softDeletes();
    NestedSet::columns($table);
    $table->timestamps();
}
```

## Pattern Avanzato: Categorie con Metadata

```php
<?php

return new class extends XotBaseMigration
{
    protected ?string $model_class = Category::class;

    public function up(): void
    {
        $table->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            
            // Metadata JSON per dati flessibili
            $table->json('metadata')->nullable();
            
            // NestedSet per gerarchia
            NestedSet::columns($table);
            
            // Campi specifici per categoria
            $table->string('icon')->nullable();
            $table->string('color')->default('#6b7280');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            
            $table->timestamps();
        });
    }
}
```

## Pattern per Menu di Navigazione

```php
<?php

return new class extends XotBaseMigration
{
    protected ?string $model_class = NavigationItem::class;

    public function up(): void
    {
        $table->tableCreate(function (Blueprint $table): void {
            $table->id();
            
            // Campi navigazione
            $table->string('title');
            $table->string('url')->nullable();
            $table->string('icon')->nullable();
            $table->string('target')->nullable(); // _self, _blank, _parent
            
            // NestedSet per gerarchia menu
            NestedSet::columns($table);
            
            // Ordinamento specifico per menu
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
        });
    }
}
```

## Pattern per Strutture Organizzativa

```php
<?php

return new class extends XotBaseMigration
{
    protected ?string $model_class = OrganizationalUnit::class;

    public function up(): void
    {
        $table->tableCreate(function (query) {
            $table->id();
            
            // Campi organizzazione
            $table->string('name');
            $table->string('code')->unique()->nullable();
            $table->string('type'); // department, division, team, etc.
            
            // Metadata per dati organizzativi
            $table->json('metadata')->nullable();
            
            // NestedSet per gerarchia
            NestedSet::columns($table);
            
            // Relazioni gerarchiche
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->foreign('manager_id')->references('id')->on('organizational_units');
            
            $table->timestamps();
        });
    }
}
```

## Integrazione con Modelli Eloquent

```php
<?php

namespace Modules\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Category extends Model
{
    use NodeTrait;
    
    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'sort_order',
        'is_visible',
        'metadata',
    ];
    
    protected $casts = [
        'metadata' => 'array',
        'is_visible' => 'boolean',
        'sort_order' => 'integer',
    ];
    
    // Scopes per query nested set
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    // Accessors per gerarchia
    public function getParent(): ?Category
    {
        return $this->parent()->first();
    }
    
    public function getChildren(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->children()->orderBy('sort_order')->get();
    }
    
    public function getDescendants(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->descendants()->orderBy('sort_order')->get();
    }
    
    public function getAncestors(): \Illuminate\Database\Eloquent\Collection
    {
        return $this->ancestors()->get();
    }
}
```

## Pattern per Validazioni

```php
<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class CreateCategoriesTable extends Migration
{
    public function up(): void
    {
        // Creare tabella
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            
            // NestedSet columns
            NestedSet::columns($table);
            
            // Vincoli unici per garantire integrità
            $table->unique(['name', 'parent_id']);
            
            $table->timestamps();
        });
    }
}
```

## Pattern per Migrazioni Dati

```php
<?php

return new class SeedCategories extends Migration
{
    public function up(): void
    {
        // Categoria radice
        $rootId = DB::table('categories')->insertGetId([
            'name' => 'Root Category',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // Sottocategorie
        $electronicsId = DB::table('categories')->insertGetId([
            'name' => 'Electronics',
            'parent_id' => $rootId,
            '_lft' => $rootId + 1,
            '_rgt' => $rootId + 2,
            'created_at' => now(),
            'update_at' => now(),
        ]);
        
        // Sotto-sottocategorie
        DB::table('categories')->insert([
            [
                'name' => 'Smartphones',
                'parent_id' => $electronicsId,
                '_lft' => $electronicsId + 1,
                '_rgt' => $electronicsId + 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Laptops',
                'parent_id' => $electronicsId,
                '_lft' => $electronicsId + 3,
                '_rgt' => $electronicsId + 4,
                'created_at' => now(),
                'node
```

## Performance Considerations

### 1. Indici Fondamentali

```php
// Essenziali per performance
$table->index('_lft');
$table->index('_rgt');
$table->index('parent_id');
```

### 2. Query Ottimizzate

```php
// ✅ Ottimizzato: usa i campi nested set
$rootCategories = Category::root()->get();

// ❌ Non ottimizzato: query ricorsiva
$allCategories = Category::all()->filter(function ($category) {
    return $category->ancestors()->count() === 0;
});
```

### 3. Batch Operations

```php
// Per migrazioni con molti dati
$categories = collect(range(1, 1000))->map(function ($i) {
    return [
        'name' => "Category {$i}",
        'created_at' => now(),
        'updated_at' => now(),
    ];
});

DB::table('categories')->insert($categories->toArray());
```

## Troubleshooting Comuni

### 1. "Column '_lft' already exists"

**Problema**: Si tenta di eseguire la migrazione più volte.

**Soluzione**: 
```php
public function up(): void
{
    if (!Schema::hasTable('categories')) {
        $this->tableCreate(...);
    }
}
```

### 2. "Cannot add column '_lft'"

**Problema**: La colonna esiste già ma viene aggiunta di nuovo.

**Soluzione**: Usare `hasColumn()` check:
```php
if (!$this->hasColumn('_lft')) {
    NestedSet::columns($table);
}
```

### 3. Performance Lenta su Gerarchie Profonde

**Problema**: Query su gerarchie molto profonde possono essere lente.

**Soluzioni**:
- Aggiungere indici composti
- Limitare la profondità delle query
- Usare materialized path per query frequenti

## Integrazione con TechPlanner

### 1. Categorie Prodotti/Servizi

```php
<?php

return new class extends XotBaseMigration
{
    protected ?string $model_class = ProductCategory::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            
            // Metadata per prodotto
            $table->json('product_attributes')->nullable();
            
            // NestedSet per gerarchia categorie
            NestedSet::columns($table);
            
            // Campi specifici per prodotto
            $table->string('icon')->nullable();
            $table->string('color')->default('#6b7280');
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
        });
    }
}
```

### 2. Struttura Organizzativa Multi-Livello

```php
<?php

return new class extends XotBaseMigration
{
    protected ?string $model_class = OrganizationalUnit::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // company, department, team
            $table->json('metadata')->nullable();
            
            // NestedSet per gerarchia multi-livello
            NestedSet::columns($table);
            
            // Relazioni gerarchiche
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->unsignedBigInteger('headquarters_id')->nullable();
            
            $table->timestamps();
        });
    }
}
```

## Riferimenti

- [Laravel NestedSet Documentation](https://github.com/kalnoy/laravel-nestedset)
- [Nested Set Theory](https://en.wikipedia.org/wiki/Nested_set_model)
- [MySQL Pattern for Hierarchical Data](https://dev.mysql.com/doc/refman/en/innodb-indexes/innodb-indexes.html)
- [PostgreSQL ltree Extension](https://www.postgresql.org/docs/current/ltree.html)

## Note Tecniche

- Il pacchetto `kalnoy/laravel-nestedset` è compatibile con Laravel 8+
- Supporta sia MySQL che PostgreSQL
- Il trait `NodeTrait` fornisce i metodi helper per gestire gerarchie
- Le performance sono eccellenti per strutture con letture moderate (<10k nodi)