- Inventario [ridondanze cross-modulo](../docs/redundancy-report.md)
- Concetti [ridondanze cross-cutting](../Xot/docs/wiki/concepts/ridondanze-cross-cutting-codebase.md)

# Redundancy Report — Modulo Blog

> Generato: 2026-05-21 | Analisi automatica deep-scan

## Problemi Trovati

### 1. 🔴 ArticleData — 2 copie nello stesso modulo + 1 in Xot

| File | Namespace | Note |
|------|-----------|------|
| `app/Datas/ArticleData.php` | `Modules\Blog\Datas` | Completo, con Carbon, Collection, GetBloodline |
| `app/DataObjects/ArticleData.php` | `Modules\Blog\DataObjects` | Alternativa con ArticleStatus enum, DateTimeInterfaceCast |
| `Modules/Xot/app/Datas/ArticleData.php` | `Modules\Xot\Datas` | Minimale, solo Data base |

**Azione suggerita**: Scegliere una sola versione in Blog. La versione in `Datas/` è la più completa. Eliminare `DataObjects/ArticleData.php` (se non usata) e la copia in Xot.

### 2. 🟠 ProfileResource — Duplicato con User e Gdpr

**File**: `app/Filament/Resources/ProfileResource.php`

Il ProfileResource esiste anche in:
- `Modules/User/app/Filament/Resources/ProfileResource.php` (canonico)
- `Modules/Gdpr/app/Filament/Clusters/Profile/Resources/ProfileResource.php`
- `Modules/Gdpr/app/Filament/Resources/ProfileResource.php`

**Azione suggerita**: Se il modulo Blog necessita di gestire profili, dovrebbe referenziare il ProfileResource di User, non duplicarlo.

### 3. 🟠 BaseTreeModel — Duplicato con Cms e Xot

**File**: `app/Models/BaseTreeModel.php`

| Modulo | Extends | Note |
|--------|---------|------|
| Blog | `Model` | Con `HasPathByParentId`, `SortableTrait` |
| Cms | `Model` | Con `MenuFactory`, `MediaCollection` |
| Xot | (base) | Con `HasRecursiveRelationshipsContract` |

**Azione suggerita**: Blog e Cms dovrebbero estendere `Xot\Models\BaseTreeModel` e aggiungere solo i trait specifici.

### 4. 🟡 BaseMorphPivot — Estende XotBaseMorphPivot ma aggiunge SoftDeletes

**File**: `app/Models/BaseMorphPivot.php`

Aggiunge `SoftDeletes` e `Updater` rispetto al base. Accettabile, ma il trait `Updater` è ridondante se `XotBaseMorphPivot` lo include già.

### 5. 🟡 Comment model — Duplicato con modulo Comment

| File | Modulo |
|------|--------|
| `app/Models/Comment.php` | Blog |
| `Modules/Comment/app/Models/Comment.php` | Comment |

Verificare se il modello Blog\Comment è un wrapper del Comment module o una copia indipendente.

## Riepilogo

| Priorità | Problema | File interessati |
|----------|----------|-----------------|
| 🔴 | ArticleData 3 copie | 3 file (2 in Blog, 1 in Xot) |
| 🟠 | ProfileResource duplicato cross-modulo | 1 file |
| 🟠 | BaseTreeModel non conforme | 1 file |
| 🟡 | BaseMorphPivot ridondanza Updater | 1 file |
| 🟡 | Comment model vs modulo Comment | 1 file |
