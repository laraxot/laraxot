---
title: "trait — Consolidated Documentation"
module: xot
type: integration
tags: [integrations, modules, xot]
created: 2026-08-24
updated: 2026-08-24
---

# trait — Consolidated Documentation

Consolidated from **8** individual files.

## Table of Contents

- [Trait Collision Resolution Summary](#trait-collision-resolution-fix)
- [---](#trait-collision-resolution)
- [Risoluzione Conflitto Trait: NavigationLabelTrait e XotBasePage](#trait-conflict-resolution)
- [](#trait-method-signature-rules-variant)
- [Trait Method Signature Rules](#trait-method-signature-rules)
- [---](#trait-method-signature)
- [---](#trait-resolution)
- [](#trait_method_signature_rules)

---

## trait-collision-resolution-fix

*Consolidated from: `trait-collision-resolution-fix.md`*


## Issue
During PHPStan analysis, a trait method collision error was encountered:
```
Trait method Modules\Xot\Filament\Traits\TransTrait::getKeyTransFunc has not been applied as Modules\Xot\Filament\Resources\XotBaseResource::getKeyTransFunc, because of collision with Modules\Xot\Filament\Traits\NavigationLabelTrait::getKeyTransFunc
```

## Root Cause
The collision occurred in classes that use both:
- `HasXotTable` trait (which uses `TransTrait` internally)
- `NavigationLabelTrait` (which uses `TransFuncTrait` internally)

Both `TransTrait` and `TransFuncTrait` define the same methods (`getKeyTransFunc`, `transFunc`), causing PHP to throw a fatal error during trait resolution.

## Solution Applied
1. **Code Cleanup**: Removed unused trait import in `XotBaseManageRelatedRecords.php`:
   - Removed: `use Modules\Xot\Filament\Traits\TransTrait as XotTransTrait;`
   - This import was not being used but could potentially cause conflicts

2. **Architecture Validation**: Confirmed that the framework's design already handles this correctly:
   - `TransFuncTrait` was specifically created to avoid conflicts with `NavigationLabelTrait`
   - The precedence rules documented in `/Modules/Xot/docs/trait-conflict-resolution.md` are properly implemented

## Files Modified
- `Modules/Xot/app/Filament/Resources/XotBaseResource/Pages/XotBaseManageRelatedRecords.php` - Removed unused TransTrait import

## Verification
- ✅ PHPStan Level 10 passes without errors
- ✅ All translation functionality preserved
- ✅ No breaking changes to existing functionality
- ✅ Trait collision error resolved

## Architecture Pattern Confirmed
This confirms the documented pattern from `/Modules/Xot/docs/trait-conflict-resolution.md`:
- Use `TransFuncTrait` (not full `TransTrait`) in `NavigationLabelTrait` to avoid conflicts
- Keep trait method signatures compatible
- Use trait precedence rules when necessary

---

## trait-collision-resolution

*Consolidated from: `trait-collision-resolution.md`*

module: theme
topic: trait-collision-resolution
canonical: ../../../Themes/docs/shared-components/trait-collision-resolution-fix.md
---

See canonical documentation: ../../../Themes/docs/shared-components/trait-collision-resolution-fix.md
---

## trait-conflict-resolution

*Consolidated from: `trait-conflict-resolution.md`*


## Problema

Si verificava un conflitto di firma del metodo `trans()` quando `NavigationLabelTrait` veniva usato insieme a `XotBasePage`:

```
PHP Fatal error: Declaration of Modules\Xot\Filament\Traits\NavigationLabelTrait::trans(...)
must be compatible with Modules\Xot\Filament\Pages\XotBasePage::trans(...)
```

### Causa

- `NavigationLabelTrait` usava `TransTrait` che definisce:
  ```php
  public static function trans(string $key, bool $exceptionIfNotExist = false, array $params = []): string
  ```

- `XotBasePage` definisce:
  ```php
  public static function trans(string $key, array $replace = [], ?string $locale = null, bool $useFallback = true): string
  ```

- Quando una classe usa `NavigationLabelTrait` e estende `XotBasePage`, entrambi definiscono `trans()` con firme incompatibili.

## Soluzione

Creato `TransFuncTrait` che contiene solo i metodi necessari per `NavigationLabelTrait`:
- `transFunc()` - traduzione basata su nome funzione
- `getKeyTransFunc()` - generazione chiave traduzione da nome funzione

`NavigationLabelTrait` ora usa `TransFuncTrait` invece di `TransTrait`, evitando il conflitto con `trans()`.

### File Modificati

1. **Creato**: `Modules/Xot/app/Filament/Traits/TransFuncTrait.php`
   - Contiene solo `transFunc()` e `getKeyTransFunc()`
   - NON contiene `trans()` per evitare conflitti

2. **Modificato**: `Modules/Xot/app/Filament/Traits/NavigationLabelTrait.php`
   - Cambiato da `use TransTrait;` a `use TransFuncTrait;`
   - `NavigationLabelTrait` usa solo `transFunc()`, non `trans()`

3. **Modificato**: `Modules/Xot/app/Filament/Resources/XotBaseResource/Pages/XotBaseManageRelatedRecords.php`
   - Rimosse precedence rules per metodi non più presenti in `NavigationLabelTrait`
   - Mantenute solo per `getKeyTransFunc()` e `transFunc()`

## Pattern Architetturale

### Quando Usare TransTrait

Usa `TransTrait` quando hai bisogno di:
- `trans()` - traduzione generica con gestione errori
- `getKeyTrans()` - generazione chiave traduzione
- `transClass()` - traduzione basata su classe
- `transChoice()` - traduzione con pluralizzazione

### Quando Usare TransFuncTrait

Usa `TransFuncTrait` quando:
- Hai bisogno solo di `transFunc()` e `getKeyTransFunc()`
- Vuoi evitare conflitti con altri trait che definiscono `trans()`
- Stai creando trait che verranno usati insieme a classi che hanno il proprio `trans()`

### Quando Usare NavigationLabelTrait

Usa `NavigationLabelTrait` per:
- Metodi di navigazione Filament (`getNavigationLabel()`, `getNavigationGroup()`, ecc.)
- Quando NON hai bisogno di `trans()` diretto
- Quando vuoi traduzioni automatiche basate su nomi di metodi

## Verifica

Dopo la modifica, verifica con:

```bash
./vendor/bin/phpstan analyse Modules/Xot/app/Filament/Traits/NavigationLabelTrait.php \
  Modules/Xot/app/Filament/Traits/TransFuncTrait.php \
  Modules/Xot/app/Filament/Pages/XotBasePage.php \
  --level=10
```

## Note Importanti

- `NavigationLabelTrait` NON deve mai usare `TransTrait` direttamente se viene usato insieme a classi che definiscono `trans()`
- `TransFuncTrait` è un subset di `TransTrait` creato specificamente per evitare conflitti
- Se hai bisogno di `trans()` in una classe che usa `NavigationLabelTrait`, usa `XotBasePage::trans()` o definisci il tuo metodo

---

*Risolto: 2025-01-10*
*Architecture Version: XotBase 2.1*

---

## trait-method-signature-rules-variant

*Consolidated from: `trait-method-signature-rules-variant.md`*


---

## trait-method-signature-rules

*Consolidated from: `trait-method-signature-rules.md`*


## 🚨 Critical Rule: Static vs Non-Static Methods

**NEVER declare methods as static in traits unless they are explicitly designed to be static.**

## 📋 Method Signature Compatibility

### Filament Method Expectations

| Method | Expected Signature | Purpose |
|--------|-------------------|---------|
| `getModelLabel()` | `public function getModelLabel(): string` | Returns singular model label |
| `getPluralModelLabel()` | `public function getPluralModelLabel(): string` | Returns plural model label |
| `getTitle()` | `public function getTitle(): string` | Returns page title |
| `getHeading()` | `public function getHeading(): string` | Returns page heading |
| `getSubHeading()` | `public function getSubHeading(): string` | Returns page subheading |

## ⚠️ Common Error Pattern

**WRONG:** Static method in trait
```php
trait NavigationPageLabelTrait
{
    public static function getModelLabel(): string // ❌ WRONG
    {
        return static::trans('navigation.name');
    }
}
```

**CORRECT:** Non-static method in trait
```php
trait NavigationPageLabelTrait
{
    public function getModelLabel(): string // ✅ CORRECT
    {
        return static::trans('navigation.name');
    }
}
```

## 🔍 Root Cause Analysis

The error "Cannot make static method non static" occurs when:

1. A trait declares a method as `static`
2. A class using the trait inherits from a parent class
3. The parent class already declares the same method as non-static
4. PHP cannot reconcile the conflicting signatures

## 🛡️ Prevention Strategy

### 1. Always Check Parent Class First
Before adding methods to a trait, verify the method signatures in:
- Parent classes
- Interfaces implemented
- Framework base classes

### 2. Use Interface-Driven Development
```php
interface NavigationLabelInterface
{
    public function getModelLabel(): string;
    public function getPluralModelLabel(): string;
}

// Then implement in trait
```

### 3. Documentation First
Document expected method signatures before implementation:
```php
/**
 * @method string getModelLabel() Returns the singular model label
 * @method string getPluralModelLabel() Returns the plural model label
 */
trait NavigationPageLabelTrait
{
    // Implementation
}
```

## 🧪 Validation Checklist

Before committing any trait:

1. [ ] Verify all methods match parent class signatures
2. [ ] No methods are declared as `static` unless absolutely necessary
3. [ ] Method return types match expected signatures
4. [ ] Parameter types and counts match
5. [ ] Access levels (public/protected) match

## 📚 Framework-Specific Rules

### Filament Specifics
- **Resources**: Methods are generally non-static
- **Pages**: Methods are generally non-static
- **Widgets**: Methods are generally non-static
- **Actions**: Methods are generally non-static

### Laravel Conventions
- **Service Providers**: Static registration methods
- **Models**: Mixed static/non-static methods
- **Controllers**: Primarily non-static methods
- **Commands**: Primarily non-static methods

## 🔧 Automatic Validation

Add PHPStan rules to detect static method issues:

```neon
# phpstan.neon
rules:
  - rule: StaticMethodInTraitRule
    traits:
      - Modules\Xot\Filament\Traits\*
```

## 🚨 Emergency Fix Procedure

If you encounter this error:

1. **Identify the conflicting method** in the trait
2. **Remove the `static` keyword** from the method declaration
3. **Verify the method signature** matches parent expectations
4. **Test thoroughly** to ensure no breaking changes

## 📖 Related Documentation

- [PHP Trait Manual](https://www.php.net/manual/en/language.oop5.traits.php)
- [Filament Method Signatures](https://filamentphp.com/docs)
- [Laravel Code Standards](../laravel_code_standards.md)

---

*Last Updated: 2025-08-27*
*Trait Standards Version: 2.0*


---

## trait-method-signature

*Consolidated from: `trait-method-signature.md`*

module: theme
topic: trait-method-signature
canonical: ../../../Themes/docs/shared-components/trait-method-signature-rules.md
---

See canonical documentation: ../../../Themes/docs/shared-components/trait-method-signature-rules.md
---

## trait-resolution

*Consolidated from: `trait-resolution.md`*

module: theme
topic: trait-resolution
canonical: ../../../Themes/docs/shared-components/trait-conflict-resolution.md
---

See canonical documentation: ../../../Themes/docs/shared-components/trait-conflict-resolution.md
---

## trait_method_signature_rules

*Consolidated from: `trait_method_signature_rules.md`*


---

**Consolidated by:** Phase 2f intelligent merging
**Date:** 2026-08-04
