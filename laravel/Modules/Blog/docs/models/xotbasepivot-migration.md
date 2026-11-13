---
title: "xotbasepivot migration"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "xotbasepivot migration"
issues: []
discussions: []
status: draft
---

# Blog Module - XotBasePivot Migration

## 📊 Overview

Il modulo Blog ha un **caso speciale**: utilizza `SoftDeletes` nei suoi Pivot models.

**Statistiche:**
- 🟡 **3 Pivot concreti** da aggiornare
- 📉 **~90 righe** di codice duplicate eliminate
- ⏱️ **30 minuti** effort stimato
- 🎯 **Priorità: MEDIA**
- ⚠️ **Caso Speciale:** SoftDeletes trait

---
## 🔧 Pivot Models Impattati

### 1. CategoryPost

**File:** `Modules/Blog/app/Models/CategoryPost.php`

**Relazione:** Category ↔ Post (many-to-many)

**Configurazione:**
```php
use Modules\Blog\Models\BasePivot;

class CategoryPost extends BasePivot
{
    protected $fillable = ['category_id', 'post_id'];
}
```

**Cambio:**
- ❌ `extends BasePivot`
- ✅ `extends XotBasePivot` tramite `Blog\Models\BasePivot`

---

### 2. Taggable

**File:** `Modules/Blog/app/Models/Taggable.php`

**Relazione:** Polymorphic - qualsiasi model può avere tag

**Tipo:** MorphPivot

**Cambio:**
- ❌ `extends BaseMorphPivot`
- ✅ `extends XotBaseMorphPivot` tramite `Blog\Models\BaseMorphPivot`

---

### 3. ArticleCategory

**File:** `Modules/Blog/app/Models/ArticleCategory.php`

**Relazione:** Article ↔ Category (many-to-many)

**Configurazione:**
```php
use Modules\Blog\Models\BasePivot;

class ArticleCategory extends BasePivot
{
    protected $fillable = ['category_id', 'article_id'];
}
```

---

## ⚠️ Caso Speciale: SoftDeletes

### Problema

Il modulo Blog utilizza `SoftDeletes` per i suoi Pivot:

```php
// Blog/Models/BasePivot.php (ATTUALE)
use Illuminate\Database\Eloquent\SoftDeletes;

abstract class BasePivot extends Pivot
{
    use SoftDeletes;  // ← Feature specifica Blog
    use Updater;
    
    // ... configurazioni comuni
}
```

**Questo è specifico del modulo Blog**, NON di tutti i moduli.

---

### Soluzione: Mantenere BasePivot Intermedio

**✅ STRATEGIA RACCOMANDATA:**

Mantenere `Blog\Models\BasePivot` come classe intermedia che aggiunge SoftDeletes:

```php
<?php

declare(strict_types=1);

namespace Modules\Blog\Models;

use Modules\Xot\Models\XotBasePivot;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Blog module specific Pivot with SoftDeletes.
 * 
 * All Blog Pivot models should extend this class
 * to automatically get soft delete functionality.
 */
abstract class BasePivot extends XotBasePivot
{
    use SoftDeletes;
    
    /**
     * Additional casts for soft deletes.
     * Merged with parent casts.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return array_merge(parent::casts(), [
            'deleted_at' => 'datetime', // Already in parent, but explicit here
        ]);
    }
}
```

**Analogamente per BaseMorphPivot:**

```php
<?php

declare(strict_types=1);

namespace Modules\Blog\Models;

use Modules\Xot\Models\XotBaseMorphPivot;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Blog module specific MorphPivot with SoftDeletes.
 */
abstract class BaseMorphPivot extends XotBaseMorphPivot
{
    use SoftDeletes;
}
```

---

### Perché Mantenere BasePivot Intermedio?

✅ **VANTAGGI:**

1. **SoftDeletes Centralizzato**
   - Tutti i Pivot del Blog hanno soft deletes
   - Configurazione in 1 posto
   - Nessuna ripetizione nei Pivot concreti

2. **Estensibilità Blog-Specific**
   - Spazio per altre configurazioni specifiche del Blog
   - Esempio: cache tags, searchable config, etc.

3. **Chiaro Intent**
   - `Blog\Models\BasePivot` → "Pivot del Blog con soft deletes"
   - Developer sa subito che c'è SoftDeletes

4. **Zero Duplicazione**
   - Configurazioni comuni: ✅ ereditate da `XotBasePivot`
   - SoftDeletes: ✅ aggiunto in `Blog\Models\BasePivot`
   - Nessuna duplicazione di codice comune

✅ **DRY COMPLIANCE:**

```
XotBasePivot (Xot)           → Configurazioni COMUNI a tutti i moduli
    ↓ extends
Blog\BasePivot (Blog)        → Configurazioni SPECIFICHE del Blog (SoftDeletes)
    ↓ extends
CategoryPost/Taggable/etc.   → Business logic specifico del Pivot
```

**Pattern a 3 livelli:**
1. **Xot level:** Configurazioni universali
2. **Module level:** Configurazioni specifiche del modulo
3. **Model level:** Business logic specifico

Questo è **perfettamente DRY e KISS**!

---

## 🚀 Script di Migration

### Step 1: Aggiorna BasePivot del Blog

**File:** `Modules/Blog/app/Models/BasePivot.php`

**Prima:**
```php
<?php

namespace Modules\Blog\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Xot\Traits\Updater;

abstract class BasePivot extends Pivot
{
    use SoftDeletes;
    use Updater;

    public static $snakeAttributes = true;
    public $incrementing = true;
    protected $perPage = 30;
    protected $connection = 'blog';
    protected $appends = [];
    protected $primaryKey = 'id';

    protected function casts(): array
    {
        return [
            'id' => 'string',
            'uuid' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
```

**Dopo:**
```php
<?php

declare(strict_types=1);

namespace Modules\Blog\Models;

use Modules\Xot\Models\XotBasePivot;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Blog module specific Pivot with SoftDeletes.
 * 
 * All Blog Pivot models should extend this class
 * to automatically get soft delete functionality.
 */
abstract class BasePivot extends XotBasePivot
{
    use SoftDeletes;
}
```

**Riduzione:** 61 righe → 15 righe (-75%)

---

### Step 2: Aggiorna BaseMorphPivot del Blog

**File:** `Modules/Blog/app/Models/BaseMorphPivot.php`

**Analogamente:**

```php
<?php

declare(strict_types=1);

namespace Modules\Blog\Models;

use Modules\Xot\Models\XotBaseMorphPivot;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Blog module specific MorphPivot with SoftDeletes.
 */
abstract class BaseMorphPivot extends XotBaseMorphPivot
{
    use SoftDeletes;
}
```

---

### Step 3: Pivot Concreti (Nessuna Modifica)

I Pivot concreti **NON** hanno bisogno di modifiche:

```php
// CategoryPost.php - NESSUNA MODIFICA NECESSARIA
use Modules\Blog\Models\BasePivot;

class CategoryPost extends BasePivot
{
    protected $fillable = ['category_id', 'post_id'];
}
```

✅ Continuano a estendere `Blog\Models\BasePivot`  
✅ Ereditano automaticamente SoftDeletes  
✅ Ereditano automaticamente configurazioni da XotBasePivot

---

## 🧪 Testing

### Test SoftDeletes

```php
<?php

namespace Modules\Blog\Tests\Unit\Models;

use Modules\Blog\Models\CategoryPost;
use Tests\TestCase;

class CategoryPostTest extends TestCase
{
    public function test_category_post_has_soft_deletes(): void
    {
        $pivot = CategoryPost::factory()->create();
        
        // Delete
        $pivot->delete();
        
        $this->assertSoftDeleted($pivot);
        $this->assertNotNull($pivot->deleted_at);
    }
    
    public function test_category_post_can_be_restored(): void
    {
        $pivot = CategoryPost::factory()->create();
        $pivot->delete();
        
        // Restore
        $pivot->restore();
        
        $this->assertNull($pivot->deleted_at);
        $this->assertDatabaseHas('category_post', [
            'id' => $pivot->id,
            'deleted_at' => null,
        ]);
    }
    
    public function test_category_post_can_be_force_deleted(): void
    {
        $pivot = CategoryPost::factory()->create();
        
        $pivot->forceDelete();
        
        $this->assertDatabaseMissing('category_post', [
            'id' => $pivot->id,
        ]);
    }
}
```

### Test Ereditarietà

```php
public function test_category_post_extends_xot_base_pivot(): void
{
    $pivot = new CategoryPost();
    
    $this->assertInstanceOf(
        \Modules\Xot\Models\XotBasePivot::class, 
        $pivot
    );
}

public function test_category_post_extends_blog_base_pivot(): void
{
    $pivot = new CategoryPost();
    
    $this->assertInstanceOf(
        \Modules\Blog\Models\BasePivot::class, 
        $pivot
    );
}

public function test_connection_is_blog(): void
{
    $pivot = new CategoryPost();
    
    $this->assertEquals('blog', $pivot->getConnectionName());
}
```

---

## ✅ Checklist Migration

### Pre-Migration

- [ ] ✅ Backup completo modulo Blog
- [ ] ✅ XotBasePivot disponibile in Xot module
- [ ] ✅ Comprensione SoftDeletes pattern

### Migration

- [ ] ✅ BasePivot aggiornato (estende XotBasePivot + SoftDeletes)
- [ ] ✅ BaseMorphPivot aggiornato (estende XotBaseMorphPivot + SoftDeletes)
- [ ] ✅ CategoryPost verificato (nessuna modifica)
- [ ] ✅ Taggable verificato (nessuna modifica)
- [ ] ✅ ArticleCategory verificato (nessuna modifica)

### Testing

- [ ] ✅ Test SoftDeletes passano
- [ ] ✅ Test ereditarietà passano
- [ ] ✅ Test integrazione Blog
- [ ] ✅ PHPStan Level 9 zero errori
- [ ] ✅ Post-category relationships OK
- [ ] ✅ Tagging system OK

### Post-Migration

- [ ] ✅ Documentazione aggiornata
- [ ] ✅ CHANGELOG entry
- [ ] ✅ Commit e push

---

## 📈 Benefits Specifici per Blog Module

### Before Migration

**Code Duplication:**
- 📄 BasePivot: 61 righe
- 📄 BaseMorphPivot: 112 righe
- 📊 **Totale: ~173 righe**
- 🔴 **Duplicate code:** 90% (tutto tranne SoftDeletes)

### After Migration

**Minimal Duplication:**
- 📄 BasePivot: 15 righe (solo SoftDeletes)
- 📄 BaseMorphPivot: 12 righe (solo SoftDeletes)
- 📊 **Totale: 27 righe**
- 🟢 **Duplicate code:** 0% (solo configurazioni specifiche Blog)

**Reduction:** -84% codice (-146 righe)

---

## 🎯 Pattern Best Practice

### ✅ Questo Pattern è IDEALE per:

1. **Module-Specific Features**
   - SoftDeletes solo nel Blog
   - Altri trait specifici
   - Configurazioni custom

2. **Clear Separation of Concerns**
   - XotBasePivot: universal configurations
   - Blog\BasePivot: module-specific features
   - Pivot models: business logic

3. **Maintainability**
   - Modifiche universali: 1 posto (XotBasePivot)
   - Modifiche Blog: 1 posto (Blog\BasePivot)
   - Business logic: specifico per Pivot

### ✅ Applicare ad Altri Moduli

**Se altri moduli hanno feature specifiche:**

```php
// Esempio: Fixcity module con trait custom
namespace Modules\Fixcity\Models;

use Modules\Xot\Models\XotBasePivot;
use Modules\Fixcity\Traits\Trackable;

abstract class BasePivot extends XotBasePivot
{
    use Trackable; // Feature specifica Fixcity
}
```

**Se moduli NON hanno feature specifiche:**

```php
// Esempio: Rating module - nessuna config speciale
namespace Modules\Rating\Models;

use Modules\Xot\Models\XotBaseMorphPivot;

// ❌ NON serve BasePivot intermedio
class RatingMorph extends XotBaseMorphPivot  // Direttamente!
{
    protected $fillable = [/*...*/];
}
```

---

## 🎓 Lessons Learned

### Pattern a 3 Livelli è Ottimale

**Livello 1 - Universal (Xot):**
- Configurazioni comuni a TUTTI i moduli
- XotBasePivot, XotBaseMorphPivot
- DRY: elimina duplicazione 95%

**Livello 2 - Module (Blog, User, etc.):**
- Configurazioni specifiche del modulo
- Blog\BasePivot con SoftDeletes
- KISS: solo se necessario

**Livello 3 - Model (CategoryPost, etc.):**
- Business logic specifico del Pivot
- Relazioni, fillable, accessors
- Single Responsibility

### Quando Mantenere BasePivot Intermedio?

**✅ MANTIENI se:**
- Module ha feature comuni ai suoi Pivot (es. SoftDeletes)
- Module ha trait specifici
- Module ha configurazioni custom

**❌ ELIMINA se:**
- Nessuna feature specifica
- Solo duplicazione di XotBasePivot
- Preferisci estendere direttamente XotBasePivot

---

*Documento Blog Module specifico*  
*Versione: 1.0*  
*Status: READY FOR IMPLEMENTATION*  
*Priority: 🟡 MEDIUM*  
*Special Case: ⚠️ SoftDeletes*  
*Pattern: ✅ 3-Level Inheritance*  
*Effort: 30 minuti*

