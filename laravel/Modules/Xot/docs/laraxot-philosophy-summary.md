<<<<<<< HEAD
# Riassunto Filosofia Laraxot - Gennaio 2026

**Data**: 8 Gennaio 2026
**Autore**: Super Mucca AI
**Versione**: 1.0
**Status**: Documento Vivo

## 🎯 Panoramica

Questo documento sintetizza le conoscenze chiave acquisite sull'architettura Laraxot durante l'analisi approfondita del codicebase. È un riepilogo pratico delle regole, principi e best practices fondamentali.

## 🏛️ Filosofia Fondamentale (DRY + KISS + SOLID + Robust)

### Principi Guida
1. **DRY (Don't Repeat Yourself)**: Unica fonte di verità per ogni entità
2. **KISS (Keep It Simple, Stupid)**: Soluzioni semplici e dirette
3. **SOLID**: Principi di design orientato agli oggetti
4. **Robust**: Architettura resiliente e sicura

### Regole Ortodossia Laraxot
- **Nessuna estensione diretta di classi Filament** - SEMPRE usare XotBase*
- **Nessun `property_exists()` su modelli Eloquent** - usare `isset()` o `hasAttribute()`
- **Nessun controller per frontoffice** - usare Folio + Volt
- **Tutti i test in Pest** - mai PHPUnit class-based
- **Model estendono sempre BaseModel del modulo** - mai Model direttamente

## 🚨 Regole Critiche

### 1. Architettura Filament
```php
// ❌ MAI
class MyPage extends Filament\Resources\Pages\EditRecord

// ✅ SEMPRE
class MyPage extends Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord
```

### 2. Model Inheritance
```php
// ❌ MAI
class MyModel extends Model

// ✅ SEMPRE
class MyModel extends BaseModel  // del modulo
```

### 3. Eloquent Magic Properties
```php
// ❌ MAI con Eloquent
if (property_exists($model, 'name')) { ... }

// ✅ SEMPRE con Eloquent
if (isset($model->name)) { ... }
// OPPURE
if ($model->hasAttribute('name')) { ... }
```

### 4. Traduzioni Filament
```php
// ❌ MAI hardcoded
TextInput::make('name')->label('Nome')

// ✅ SEMPRE da traduzioni
TextInput::make('name')  // label automatica da file di traduzione
```

## 🏗️ Architettura Moduli

### Struttura Gerarchica
```
 Illuminate\Database\Eloquent\Model
    ↓
 Modules\Xot\Models\XotBaseModel  (Core Framework)
    ↓
 Modules\{Module}\Models\BaseModel  (Per modulo)
    ↓
 Tuoi Modelli  (Business Logic)
```

### Migration Philosophy
- **Una sola `create_table` migration per ogni tabella**
- **Usare sempre `XotBaseMigration`**
- **Schema evolution con `tableUpdate()`, mai nuova `create_table`**

## 🧪 Testing con Pest

### Best Practices
```php
// Usare sempre TestCase di Xot
uses(\Modules\Xot\Tests\TestCase::class);

// Mockare XotData in beforeEach()
beforeEach(function (): void {
    mockXotData();
});

// Test strutturati correttamente
test('widget can be rendered', function () {
    Livewire::test(WidgetName::class)
        ->assertStatus(200);
});
```

## 🔧 Code Quality Standards

### PHPStan Level 10
- **Obiettivo**: Zero errori
- **Approccio**: Type safety massima
- **Pattern**: Strict types, type narrowing, union types

### PHPMD & Code Style
- **Pattern**: Rispettare regole di complessità
- **Formattazione**: Laravel Pint o PHP CS Fixer
- **Qualità**: Nessun code smell

## 🌐 Frontoffice: Folio + Volt

### Regole Architetturali
- **Nessun controller per pagine pubbliche**
- **Routing basato su file con Laravel Folio**
- **Componenti interattivi con Livewire Volt**
- **Layout con `x-layouts.app`, `x-layouts.guest`, ecc.**

## 📚 Documentazione

### Convenzioni Naming
- **File `.md`** solo in cartelle `docs` esistenti
- **Nomi minuscolo** con trattini, mai maiuscole (eccetto `README.md`)
- **Nessun `readme.md`** se esiste `README.md`
- **Link relativi** sempre, mai path assoluti

### Processo Documentazione
1. **Prima** di modificare: studiare docs esistenti
2. **Durante** lo sviluppo: aggiornare docs
3. **Dopo** lo sviluppo: finalizzare docs

## 🎯 Checklist Implementazione

### Prima di Iniziare
- [ ] Studiare docs del modulo target
- [ ] Comprendere la business logic
- [ ] Identificare pattern esistenti
- [ ] Scegliere priorità (autonomamente)

### Durante lo Sviluppo
- [ ] Seguire regole Laraxot
- [ ] Usare XotBase classi
- [ ] Applicare PHPStan level 10
- [ ] Scrivere test in Pest

### Dopo lo Sviluppo
- [ ] Verificare PHPStan 0 errori
- [ ] Eseguire test Pest
- [ ] Aggiornare documentazione
- [ ] Controllare PHPMD/PHP Insights

## 🔄 Git Workflow
- **Solo forward** - mai andare indietro
- **Analizzare** versioni precedenti ma non ripristinare
- **Mantenere** coerenza con versione corrente

## 📋 Quick Reference

### Classi da Estendere
| Categoria | Classe Base | Esempio |
|-----------|-------------|---------|
| Resource | `XotBaseResource` | `Modules\Xot\Filament\Resources\XotBaseResource` |
| Page | `XotBasePage` | `Modules\Xot\Filament\Resources\Pages\XotBasePage` |
| Widget | `XotBaseWidget` | `Modules\Xot\Filament\Widgets\XotBaseWidget` |
| Model | `BaseModel` | `Modules\{Module}\Models\BaseModel` |
| Migration | `XotBaseMigration` | `Modules\Xot\Database\Migrations\XotBaseMigration` |

### Pattern Comuni
- **Actions**: Usare Spatie Queueable Actions per business logic
- **Translations**: File di traduzione, mai stringhe hardcoded
- **Validation**: Form Requests o validazione nei componenti
- **Security**: Autorizzazione e autenticazione basate su Spatie Permission

## 🧠 Conoscenze Applicate

Questo documento rappresenta la sintesi delle conoscenze acquisite attraverso:
- Analisi approfondita della documentazione esistente
- Studio dei pattern di codice nel codebase
- Comprensione della filosofia architetturale
- Identificazione delle best practices consolidate

---

**Ultimo Aggiornamento**: 8 Gennaio 2026
**Stato**: Documento Vivo - Aggiornare con nuove scoperte
**Principio**: La documentazione è la memoria viva del sistema
=======
# Laraxot Philosophy - Complete Summary

## Core Principles

### 1. **Single Source of Truth**

**Every entity has exactly ONE authoritative definition:**

- **Tables**: One `create_table` migration per table per module
- **Files**: One location for database files (migrations, seeders, factories)
- **Models**: One base class inheritance chain
- **Resources**: One Filament base resource class

### 2. **Consistency Over Flexibility**

**Predictable behavior is more valuable than unlimited options:**

- Same file structure across all modules
- Same inheritance patterns for all models
- Same migration philosophy for all tables
- Same autoloader behavior in all environments

### 3. **DRY/KISS Compliance**

**Eliminate redundancy and keep it simple:**

- No duplicate migrations for the same table
- No duplicate file locations for the same classes
- No redundant method overrides in models
- No unnecessary configuration complexity

## Specific Rules

### Migration Philosophy

**🚨 ONE TABLE, ONE MIGRATION**

- Each table gets exactly ONE `create_table` migration
- Schema changes use separate migration files
- NEVER create multiple `create_table` migrations for the same table
- Use `XotBaseMigration` for auto-discovery and idempotent operations

### File Structure Philosophy

**🚨 ONE LOCATION, ONE FILE**

- Database files exist in ONE location only
- Traditional Laravel structure (`database/` directory) recommended
- NEVER mix traditional and app-centric structures
- Empty directories confuse autoloader and must be removed

### Model Architecture Philosophy

**🚨 ONE INHERITANCE CHAIN**

- All models extend module-specific base classes
- Base classes extend `XotBaseModel`
- NEVER extend Laravel Model directly
- Connection auto-discovery from namespace

### Filament Philosophy

**🚨 ONE BASE RESOURCE CLASS**

- All Filament resources extend `XotBaseResource`
- Auto-discovery of models and pages
- Translation-first approach for labels
- Consistent form and infolist schemas

## Why These Rules Matter

### Technical Benefits

1. **Predictable Autoloading**: No ambiguous class resolution
2. **Consistent Behavior**: Same results in all environments
3. **Easy Maintenance**: Clear, unambiguous code structure
4. **Fast Debugging**: Obvious source of truth for each entity

### Business Benefits

1. **Reduced Development Time**: Less time spent on configuration
2. **Fewer Bugs**: Eliminates whole categories of errors
3. **Easier Onboarding**: Clear patterns for new developers
4. **Scalable Architecture**: Consistent patterns scale well

## Violation Examples

### ❌ Migration Violation

```
Modules/User/database/migrations/
├── 2023_01_01_000011_create_roles_table.php  # ❌ DUPLICATE
├── 2023_01_01_000012_create_roles_table.php  # ❌ DUPLICATE
└── 2024_01_01_000011_create_roles_table.php  # ✅ AUTHORITATIVE
```

### ❌ File Structure Violation

```
Modules/Cms/
├── database/                    # ❌ HAS FILES
│   └── factories/PageFactory.php
└── app/
    ├── Database/                # ❌ EMPTY DIRECTORIES
    │   └── Factories/           # (confuses autoloader)
    └── ...
```

### ❌ Model Violation

```php
// ❌ WRONG - Direct Model extension
class User extends Model { }

// ✅ CORRECT - Module base class
class User extends BaseUser { }
```

## Implementation Checklist

### For New Modules

- [ ] Use traditional `database/` structure
- [ ] Create ONE `create_table` migration per table
- [ ] Extend module-specific base models
- [ ] Extend `XotBaseResource` for Filament
- [ ] Follow translation-first approach

### For Existing Modules

- [ ] Consolidate duplicate migrations
- [ ] Remove empty/duplicate directories
- [ ] Verify inheritance chains
- [ ] Test autoloader behavior

## Documentation References

- **Migration Philosophy**: `Modules/Xot/docs/migration-philosophy.md`
- **File Structure**: `Modules/Xot/docs/file-structure-philosophy.md`
- **Model Architecture**: `Modules/Xot/docs/models/MODEL_ARCHITECTURE.md`
- **Filament Resources**: `CLAUDE.md` Filament section

## Testing Philosophy Compliance

```bash
# Check for duplicate migrations
find Modules -name "*create_*_table.php" | sort

# Check for duplicate file locations
find Modules -name "*.php" | grep -E "(factories|seeders)" | sort

# Check model inheritance
grep -r "extends Model" Modules/*/app/Models/

# Test autoloader
composer dump-autoload
```

---

**Philosophy Summary**: Laraxot values simplicity, consistency, and predictability above all else. Follow these principles to build maintainable, scalable applications with minimal technical debt.
>>>>>>> 7f6cf6be (.)
