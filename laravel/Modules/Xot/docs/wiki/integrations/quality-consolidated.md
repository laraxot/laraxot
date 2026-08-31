---
title: "quality — Consolidated Documentation"
module: xot
type: integration
tags: [integrations, modules, xot]
created: 2026-08-24
updated: 2026-08-24
---

# quality — Consolidated Documentation

Consolidated from **11** individual files.

## Table of Contents

- [Analisi Qualità Codice - Tutti i Moduli (PHPMD)](#quality-all-modules)
- [Analisi Qualità Codice - Tutti i Moduli (PHPMD)](#quality-analysis-all-modules)
- [Quality Improvements Summary - November 18, 2025](#quality-improvements-summary-november)
- [Quality Improvements Summary - November 18, 2025](#quality-improvements-summary)
- [Quality Improvements Summary - November 18, 2025](#quality-improvements-sumy)
- [Quality Improvements Summary - November 18, 2025](#quality-improvements)
- [Filosofia degli Strumenti di Qualità - La Trinità del Codice Perfetto](#quality-tools-philosophy)
- [Status Quality Tools - Novembre 2025](#quality-tools-status-nov)
- [Status Quality Tools - Novembre 2025](#quality-tools-status)
- [Lo Zen degli Strumenti di Qualità PHP - La Grande Unificazione](#quality-tools-zen)
- [---](#quality)

---

## quality-all-modules

*Consolidated from: `quality-all-modules.md`*


**Obiettivo**: Analisi sistematica completa della qualità del codice di tutti i moduli
**Strumento**: PHPMD (PHP Mess Detector)
**Livello PHPStan**: max (già verificato - 0 errori)
**PHPInsights**: Non disponibile nel progetto

## ✅ Risultati Finali

### PHPStan

- **Status**: ✅ 0 errori (livello max)
- **Moduli puliti**: 15/15 (100%)

### PHPMD

- **Warning totali**: (analisi completa)
- **Warning critici**: Identificati e corretti
- **Warning accettabili**: Documentati

### PHPInsights

- **Status**: ❌ Non disponibile nel progetto
- **Nota**: Progetto non utilizza PHPInsights

## 🔍 Categorizzazione Warning PHPMD

### Warning Critici (Corretti)

#### UnusedLocalVariable - XotBaseRelationManager.php ✅

- **File**: `Modules/Xot/app/Filament/Resources/RelationManagers/XotBaseRelationManager.php`
- **Problema**: `$resource = static::class;` non utilizzata
- **Soluzione**: Rimossa variabile non utilizzata
- **Verifica**: PHPStan ancora passa (0 errori)

### Warning Accettabili (Non Corretti)

#### ShortVariable - $me

- **Priorità**: Bassa
- **Motivazione**: Pattern standard per accesso a `$this` in closure PHP
- **Azione**: Nessuna (accettabile)

#### StaticAccess - Assert, Arr

- **Priorità**: Bassa
- **Motivazione**: Pattern standard Laravel per utility
- **Azione**: Nessuna (accettabile)

#### Cyclomatic Complexity / NPath Complexity

- **Priorità**: Media
- **Motivazione**: Complessità dovuta a controlli di sicurezza runtime necessari
- **Azione**: Monitorare, refactoring futuro se necessario

#### UnusedFormalParameter con prefisso `_`

- **Priorità**: Bassa
- **Motivazione**: Parametri richiesti da interfaccia ma non utilizzati
- **Azione**: Nessuna (pattern accettato)

## 📊 Statistiche

**Output completo**: `/tmp/phpmd_final.txt`

## 📝 Strategia Applicata

### Correzioni Eseguite

- ✅ Rimozione codice morto (unused variables)
- ✅ Verifica PHPStan dopo ogni modifica
- ✅ Documentazione decisioni per warning ignorati

### Decisioni

- **Correggere**: Solo codice morto e bug evidenti
- **Monitorare**: Complexity warning (refactoring futuro)
- **Ignorare**: Warning stilistici accettabili (naming, static access pattern)

## ✅ Validazione Finale

- ✅ **PHPStan**: 0 errori (livello max) - mantenuto
- ✅ **PHPMD**: Warning critici corretti
- ✅ **Pint**: Stile corretto
- ❌ **PHPInsights**: Non disponibile

## 📝 Note

- **PHPInsights**: Strumento non installato nel progetto. Analisi limitata a PHPMD.
- **Focus**: Qualità codice mantenuta, codice morto rimosso
- **PHPStan**: Sempre priorità massima (0 errori mantenuto)

---

## quality-analysis-all-modules

*Consolidated from: `quality-analysis-all-modules.md`*


**Data**: 2025-12-23
**Obiettivo**: Analisi sistematica completa della qualità del codice di tutti i moduli
**Strumento**: PHPMD (PHP Mess Detector)
**Livello PHPStan**: max (già verificato - 0 errori)
**PHPInsights**: Non disponibile nel progetto

## ✅ Risultati Finali

### PHPStan

- **Status**: ✅ 0 errori (livello max)
- **Moduli puliti**: 15/15 (100%)

### PHPMD

- **Warning totali**: (analisi completa)
- **Warning critici**: Identificati e corretti
- **Warning accettabili**: Documentati

### PHPInsights

- **Status**: ❌ Non disponibile nel progetto
- **Nota**: Progetto non utilizza PHPInsights

## 🔍 Categorizzazione Warning PHPMD

### Warning Critici (Corretti)

#### UnusedLocalVariable - XotBaseRelationManager.php ✅

- **File**: `Modules/Xot/app/Filament/Resources/RelationManagers/XotBaseRelationManager.php`
- **Problema**: `$resource = static::class;` non utilizzata
- **Soluzione**: Rimossa variabile non utilizzata
- **Verifica**: PHPStan ancora passa (0 errori)

### Warning Accettabili (Non Corretti)

#### ShortVariable - $me

- **Priorità**: Bassa
- **Motivazione**: Pattern standard per accesso a `$this` in closure PHP
- **Azione**: Nessuna (accettabile)

#### StaticAccess - Assert, Arr

- **Priorità**: Bassa
- **Motivazione**: Pattern standard Laravel per utility
- **Azione**: Nessuna (accettabile)

#### Cyclomatic Complexity / NPath Complexity

- **Priorità**: Media
- **Motivazione**: Complessità dovuta a controlli di sicurezza runtime necessari
- **Azione**: Monitorare, refactoring futuro se necessario

#### UnusedFormalParameter con prefisso `_`

- **Priorità**: Bassa
- **Motivazione**: Parametri richiesti da interfaccia ma non utilizzati
- **Azione**: Nessuna (pattern accettato)

## 📊 Statistiche

**Output completo**: `/tmp/phpmd_final.txt`

## 📝 Strategia Applicata

### Correzioni Eseguite

- ✅ Rimozione codice morto (unused variables)
- ✅ Verifica PHPStan dopo ogni modifica
- ✅ Documentazione decisioni per warning ignorati

### Decisioni

- **Correggere**: Solo codice morto e bug evidenti
- **Monitorare**: Complexity warning (refactoring futuro)
- **Ignorare**: Warning stilistici accettabili (naming, static access pattern)

## ✅ Validazione Finale

- ✅ **PHPStan**: 0 errori (livello max) - mantenuto
- ✅ **PHPMD**: Warning critici corretti
- ✅ **Pint**: Stile corretto
- ❌ **PHPInsights**: Non disponibile

## 📝 Note

- **PHPInsights**: Strumento non installato nel progetto. Analisi limitata a PHPMD.
- **Focus**: Qualità codice mantenuta, codice morto rimosso
- **PHPStan**: Sempre priorità massima (0 errori mantenuto)

---

## quality-improvements-summary-november

*Consolidated from: `quality-improvements-summary-november.md`*


## Overview

This document summarizes the quality improvements made to the PTVX system on November 18, 2025, focusing on PHPStan compliance, syntax error fixes, and code quality enhancements across multiple modules.

## PHPStan Level 10 Compliance

### Issues Fixed
- **Xot Module**: Fixed syntax errors in GenerateFormByFileAction.php that were preventing PHPStan analysis
  - Added missing closing braces
  - Fixed undefined variable `$params`
  - Completed the function return logic

- **User Module**: Fixed syntax errors in UserModelTest.php
  - Fixed malformed stubUser function with missing instantiation
  - Added proper variable initialization for the team object
  - Fixed unclosed brace in the test helper function

- **User Module**: Fixed syntax errors in configuration files
  - Fixed missing semicolons in .php-cs-fixer.dist.php
  - Fixed missing semicolons in .vscode/.php-cs-fixer.php

## Code Quality Enhancements

### PHP Insights Results
The system achieved:
- **Code Quality**: 52.6/100
- **Complexity**: 93.1/100
- **Architecture**: 35.3/100
- **Style**: 60.2/100

### Key Issues Identified by PHP Insights
- Forbidden public properties in LoginForm
- Unused setters that should use constructor injection
- Property names with underscore prefixes
- Late static binding for constants disallowed
- Switch statement formatting issues
- Unused variables throughout the codebase
- Useless variable assignments
- Array indentation issues
- Empty statements and unnecessary code
- Assignment in conditions

## Module-Specific Improvements

### ServizioEsterno.php (IndennitaCondizioniLavoro Module)
- Fixed calls to `toCarbonOrNull()` method on mixed types
- Added proper type checking before calling methods
- Implemented safe handling of database attribute values
- Added type narrowing patterns for Carbon conversion

### Xot Module
- Fixed syntax errors that were blocking analysis tools
- Completed incomplete function implementations
- Added proper error handling and return values

### User Module
- Fixed syntax errors in test files
- Corrected malformed helper functions
- Fixed configuration file syntax

## PHPMD Analysis
- Identified complexity issues in several classes
- Found various code smells and anti-patterns
- Noted architecture violations and coupling issues

## Next Steps

1. **Continue PHPStan Compliance**: Work on remaining modules to achieve full Level 10 compliance
2. **Address PHP Insights Issues**: Systematically resolve issues identified by PHP Insights
3. **Refactor Complex Code**: Address classes with high cyclomatic complexity
4. **Improve Architecture**: Work on the architecture score by addressing dependency and interface issues
5. **Style Consistency**: Implement consistent coding standards across all modules

## Documentation Updates

- Update module-specific documentation to reflect the changes made
- Document the PHPStan compliance process and best practices
- Create guidelines for preventing similar issues in the future

---

## quality-improvements-summary

*Consolidated from: `quality-improvements-summary.md`*


## Overview

This document summarizes the quality improvements made to the PTVX system on November 18, 2025, focusing on PHPStan compliance, syntax error fixes, and code quality enhancements across multiple modules.

## PHPStan Level 10 Compliance

### Issues Fixed
- **Xot Module**: Fixed syntax errors in GenerateFormByFileAction.php that were preventing PHPStan analysis
  - Added missing closing braces
  - Fixed undefined variable `$params`
  - Completed the function return logic

- **User Module**: Fixed syntax errors in UserModelTest.php
  - Fixed malformed stubUser function with missing instantiation
  - Added proper variable initialization for the team object
  - Fixed unclosed brace in the test helper function

- **User Module**: Fixed syntax errors in configuration files
  - Fixed missing semicolons in .php-cs-fixer.dist.php
  - Fixed missing semicolons in .vscode/.php-cs-fixer.php

## Code Quality Enhancements

### PHP Insights Results
The system achieved:
- **Code Quality**: 52.6/100
- **Complexity**: 93.1/100
- **Architecture**: 35.3/100
- **Style**: 60.2/100

### Key Issues Identified by PHP Insights
- Forbidden public properties in LoginForm
- Unused setters that should use constructor injection
- Property names with underscore prefixes
- Late static binding for constants disallowed
- Switch statement formatting issues
- Unused variables throughout the codebase
- Useless variable assignments
- Array indentation issues
- Empty statements and unnecessary code
- Assignment in conditions

## Module-Specific Improvements

### ServizioEsterno.php (IndennitaCondizioniLavoro Module)
- Fixed calls to `toCarbonOrNull()` method on mixed types
- Added proper type checking before calling methods
- Implemented safe handling of database attribute values
- Added type narrowing patterns for Carbon conversion

### Xot Module
- Fixed syntax errors that were blocking analysis tools
- Completed incomplete function implementations
- Added proper error handling and return values

### User Module
- Fixed syntax errors in test files
- Corrected malformed helper functions
- Fixed configuration file syntax

## PHPMD Analysis
- Identified complexity issues in several classes
- Found various code smells and anti-patterns
- Noted architecture violations and coupling issues

## Next Steps

1. **Continue PHPStan Compliance**: Work on remaining modules to achieve full Level 10 compliance
2. **Address PHP Insights Issues**: Systematically resolve issues identified by PHP Insights
3. **Refactor Complex Code**: Address classes with high cyclomatic complexity
4. **Improve Architecture**: Work on the architecture score by addressing dependency and interface issues
5. **Style Consistency**: Implement consistent coding standards across all modules

## Documentation Updates

- Update module-specific documentation to reflect the changes made
- Document the PHPStan compliance process and best practices
- Create guidelines for preventing similar issues in the future

---

## quality-improvements-sumy

*Consolidated from: `quality-improvements-sumy.md`*


## Overview

This document summarizes the quality improvements made to the PTVX system on November 18, 2025, focusing on PHPStan compliance, syntax error fixes, and code quality enhancements across multiple modules.

## PHPStan Level 10 Compliance

### Issues Fixed
- **Xot Module**: Fixed syntax errors in GenerateFormByFileAction.php that were preventing PHPStan analysis
  - Added missing closing braces
  - Fixed undefined variable `$params`
  - Completed the function return logic

- **User Module**: Fixed syntax errors in UserModelTest.php
  - Fixed malformed stubUser function with missing instantiation
  - Added proper variable initialization for the team object
  - Fixed unclosed brace in the test helper function

- **User Module**: Fixed syntax errors in configuration files
  - Fixed missing semicolons in .php-cs-fixer.dist.php
  - Fixed missing semicolons in .vscode/.php-cs-fixer.php

## Code Quality Enhancements

### PHP Insights Results
The system achieved:
- **Code Quality**: 52.6/100
- **Complexity**: 93.1/100
- **Architecture**: 35.3/100
- **Style**: 60.2/100

### Key Issues Identified by PHP Insights
- Forbidden public properties in LoginForm
- Unused setters that should use constructor injection
- Property names with underscore prefixes
- Late static binding for constants disallowed
- Switch statement formatting issues
- Unused variables throughout the codebase
- Useless variable assignments
- Array indentation issues
- Empty statements and unnecessary code
- Assignment in conditions

## Module-Specific Improvements

### ServizioEsterno.php (IndennitaCondizioniLavoro Module)
- Fixed calls to `toCarbonOrNull()` method on mixed types
- Added proper type checking before calling methods
- Implemented safe handling of database attribute values
- Added type narrowing patterns for Carbon conversion

### Xot Module
- Fixed syntax errors that were blocking analysis tools
- Completed incomplete function implementations
- Added proper error handling and return values

### User Module
- Fixed syntax errors in test files
- Corrected malformed helper functions
- Fixed configuration file syntax

## PHPMD Analysis
- Identified complexity issues in several classes
- Found various code smells and anti-patterns
- Noted architecture violations and coupling issues

## Next Steps

1. **Continue PHPStan Compliance**: Work on remaining modules to achieve full Level 10 compliance
2. **Address PHP Insights Issues**: Systematically resolve issues identified by PHP Insights
3. **Refactor Complex Code**: Address classes with high cyclomatic complexity
4. **Improve Architecture**: Work on the architecture score by addressing dependency and interface issues
5. **Style Consistency**: Implement consistent coding standards across all modules

## Documentation Updates

- Update module-specific documentation to reflect the changes made
- Document the PHPStan compliance process and best practices
- Create guidelines for preventing similar issues in the future

---

## quality-improvements

*Consolidated from: `quality-improvements.md`*


## Overview

This document summarizes the quality improvements made to the PTVX system on November 18, 2025, focusing on PHPStan compliance, syntax error fixes, and code quality enhancements across multiple modules.

## PHPStan Level 10 Compliance

### Issues Fixed
- **Xot Module**: Fixed syntax errors in GenerateFormByFileAction.php that were preventing PHPStan analysis
  - Added missing closing braces
  - Fixed undefined variable `$params`
  - Completed the function return logic

- **User Module**: Fixed syntax errors in UserModelTest.php
  - Fixed malformed stubUser function with missing instantiation
  - Added proper variable initialization for the team object
  - Fixed unclosed brace in the test helper function

- **User Module**: Fixed syntax errors in configuration files
  - Fixed missing semicolons in .php-cs-fixer.dist.php
  - Fixed missing semicolons in .vscode/.php-cs-fixer.php

## Code Quality Enhancements

### PHP Insights Results
The system achieved:
- **Code Quality**: 52.6/100
- **Complexity**: 93.1/100
- **Architecture**: 35.3/100
- **Style**: 60.2/100

### Key Issues Identified by PHP Insights
- Forbidden public properties in LoginForm
- Unused setters that should use constructor injection
- Property names with underscore prefixes
- Late static binding for constants disallowed
- Switch statement formatting issues
- Unused variables throughout the codebase
- Useless variable assignments
- Array indentation issues
- Empty statements and unnecessary code
- Assignment in conditions

## Module-Specific Improvements

### ServizioEsterno.php (IndennitaCondizioniLavoro Module)
- Fixed calls to `toCarbonOrNull()` method on mixed types
- Added proper type checking before calling methods
- Implemented safe handling of database attribute values
- Added type narrowing patterns for Carbon conversion

### Xot Module
- Fixed syntax errors that were blocking analysis tools
- Completed incomplete function implementations
- Added proper error handling and return values

### User Module
- Fixed syntax errors in test files
- Corrected malformed helper functions
- Fixed configuration file syntax

## PHPMD Analysis
- Identified complexity issues in several classes
- Found various code smells and anti-patterns
- Noted architecture violations and coupling issues

## Next Steps

1. **Continue PHPStan Compliance**: Work on remaining modules to achieve full Level 10 compliance
2. **Address PHP Insights Issues**: Systematically resolve issues identified by PHP Insights
3. **Refactor Complex Code**: Address classes with high cyclomatic complexity
4. **Improve Architecture**: Work on the architecture score by addressing dependency and interface issues
5. **Style Consistency**: Implement consistent coding standards across all modules

## Documentation Updates

- Update module-specific documentation to reflect the changes made
- Document the PHPStan compliance process and best practices
- Create guidelines for preventing similar issues in the future

---

## quality-tools-philosophy

*Consolidated from: `quality-tools-philosophy.md`*


## 🎯 Overview - I Tre Pilastri

```
         QUALITY CODE
              ▲
             ╱│╲
            ╱ │ ╲
           ╱  │  ╲
          ╱   │   ╲
    STATIC │ RUNTIME
   ANALYSIS│  GUARDS
            │
      DOCUMENTATION
```

**FILOSOFIA**: La qualità del codice richiede verifiche a **3 livelli**:
1. **Static** (PHPStan + Larastan) - Compile-time
2. **Runtime** (Webmozart Assert + Safe) - Execution-time
3. **Documentation** (IDE Helper) - Developer-time

---

## 📚 STRUMENTO 1: Laravel IDE Helper

### 🙏 La Religione dell'Illuminazione

**SCOPO DIVINO**: Rivelare a PHPStan ciò che Laravel nasconde

**IL PROBLEMA ESISTENZIALE**:
```php
class User extends Model {
    // NO $email property declared!
}

$user->email;  // Funziona! (magia __get())

// Ma PHPStan è confuso:
// "Property email does not exist on User!"
```

**LA SOLUZIONE ILLUMINANTE**:
```bash
php artisan ide-helper:models --write
```

**GENERA**:
```php
/**
 * @property string $email
 * @property string $name
 * @property \Carbon\Carbon $created_at
 *
 * @property-read Profile $profile
 * @property-read Collection<int, Role> $roles
 */
class User extends Model {
    // Ora PHPStan "vede" le properties!
}
```

### Comandamenti IDE Helper

1. **Rigenera dopo migrations**: `php artisan ide-helper:models --write`
2. **Usa --nowrite per preview**: Test prima di commit
3. **Commit PHPDoc nel codice**: @property nel model stesso
4. **Relation PHPDoc specifici**: `Collection<int, Role>` non solo `Collection`

### Pattern di Utilizzo nel Progetto

**Config attuale** (da verificare):
```bash
# Check se ide-helper è configurato
cat config/ide-helper.php 2>/dev/null || echo "Config non trovato"
```

**Best Practice**:
```php
/**
 * Generate helpers after:
 * - New migration
 * - New relationship
 * - Schema changes
 */
php artisan ide-helper:models --write --reset
```

---

## 🛡️ STRUMENTO 2: Webmozart Assert

### ⚖️ La Politica del Runtime Guard

**FILOSOFIA**: "Static analysis trova bug PRIMA, runtime guards trova bug DURANTE"

**IL PROBLEMA**:
```php
// PHPStan SA che $data è mixed
function process(mixed $data): string {
    return $data['key'];
    // ✅ PHPStan: OK (mixed può essere array)
    // ❌ Runtime: BOOM se $data non è array!
}
```

**LA SOLUZIONE**:
```php
use Webmozart\Assert\Assert;

function process(mixed $data): string {
    Assert::isArray($data);  // ← Runtime guard!
    Assert::keyExists($data, 'key');

    return $data['key'];
    // ✅ PHPStan: OK
    // ✅ Runtime: Exception if data invalid
}
```

### Le 7 Categorie di Assertions

#### 1. **Type Assertions**
```php
Assert::string($value);        // Must be string
Assert::integer($value);       // Must be integer
Assert::boolean($value);       // Must be boolean
Assert::float($value);         // Must be float
Assert::numeric($value);       // Must be numeric
Assert::scalar($value);        // Must be scalar
Assert::object($value);        // Must be object
Assert::resource($value);      // Must be resource
Assert::isCallable($value);    // Must be callable
```

#### 2. **Nullability Assertions**
```php
Assert::null($value);          // Must be null
Assert::notNull($value);       // Must not be null
Assert::nullOrString($value);  // null OR string
```

#### 3. **Array Assertions**
```php
Assert::isArray($value);                 // Must be array
Assert::isMap($value);                   // Associative array
Assert::isList($value);                  // Numeric indexed array
Assert::keyExists($array, 'key');        // Key must exist
Assert::keyNotExists($array, 'key');     // Key must not exist
Assert::count($array, 5);                // Must have 5 elements
Assert::notEmpty($array);                // Must not be empty
```

#### 4. **String Assertions**
```php
Assert::stringNotEmpty($value);          // Non-empty string
Assert::startsWith($string, 'prefix');   // Must start with
Assert::endsWith($string, 'suffix');     // Must end with
Assert::contains($string, 'substring');  // Must contain
Assert::length($string, 10);             // Must be 10 chars
Assert::minLength($string, 5);           // At least 5 chars
Assert::maxLength($string, 100);         // At most 100 chars
Assert::regex($string, '/pattern/');     // Must match regex
Assert::email($string);                  // Must be valid email
Assert::uuid($string);                   // Must be UUID
```

#### 5. **Instance Assertions**
```php
Assert::isInstanceOf($object, User::class);
Assert::notInstanceOf($object, Admin::class);
Assert::isAnyOf($value, ['string', 'int']);  // Union types
```

#### 6. **Comparison Assertions**
```php
Assert::eq($value, 10);            // Equal
Assert::notEq($value, 0);          // Not equal
Assert::same($value, $expected);   // Identical (===)
Assert::greaterThan($value, 5);    // > 5
Assert::lessThan($value, 100);     // < 100
Assert::range($value, 1, 10);      // Between 1 and 10
Assert::oneOf($value, ['a', 'b']); // In list
```

#### 7. **Complex Assertions**
```php
Assert::allString($array);         // All elements are strings
Assert::allIsInstanceOf($array, User::class);  // All are Users
Assert::isList($array);            // Numeric sequential keys
Assert::isMap($array);             // String keys (assoc array)
```

### Usage Pattern nel Progetto

**392 file lo usano!** Esempio tipico:
```php
use Webmozart\Assert\Assert;

public function execute(mixed $data): Model {
    Assert::isArray($data);
    Assert::keyExists($data, 'email');
    Assert::string($data['email']);
    Assert::email($data['email']);

    return User::create($data);
}
```

**BENEFICIO**: PHPStan + Assert = **Type Safety Totale** (static + runtime)

---

## 🔥 STRUMENTO 3: Safe Functions

### 💀 La Filosofia del "Fail Fast"

**IL PROBLEMA PHP**:
```php
// PHP standard: Silent failure
$json = json_decode('invalid json');
var_dump($json);  // NULL
// ❌ Nessun errore! Bug procede silenzioso!

if ($json === null) {
    // Developer DEVE ricordare questo check
}
```

**LA SOLUZIONE SAFE**:
```php
use function Safe\json_decode;

$json = json_decode('invalid json');
// ✅ BOOM! Exception immediata!
// ✅ Stack trace chiaro!
// ✅ IMPOSSIBLE ignorare l'errore!
```

### Le Funzioni Trasformate

**234 file nel progetto usano**:

```php
// File operations
use function Safe\file_get_contents;
use function Safe\file_put_contents;
use function Safe\realpath;
use function Safe\mkdir;
use function Safe\unlink;

// String operations
use function Safe\preg_match;
use function Safe\preg_replace;
use function Safe\preg_split;

// Data operations
use function Safe\json_decode;
use function Safe\json_encode;
use function Safe\serialize;
use function Safe\unserialize;

// Array operations
use function Safe\array_combine;
use function Safe\array_flip;
use function Safe\ksort;
use function Safe\usort;

// DateTime operations
use function Safe\DateTime;
```

### Quando NON Usare Safe

```php
// ❌ Safe\in_array - Non esiste!
// ✅ in_array() - già sicuro

// ❌ Safe\isset - Non esiste!
// ✅ isset() - già sicuro

// ❌ Safe\property_exists - Non esiste!
// ✅ isset() - pattern corretto per Eloquent!
```

**REGOLA**: Safe esiste SOLO per funzioni che ritornano `false` on error.

---

## 🔍 STRUMENTO 4: phpstan-safe-rule

### 👁️ Il Vigilante

**SCOPO**: Forza l'uso di Safe functions

**IN AZIONE**:
```php
// phpstan.neon include:
includes:
    - ./vendor/thecodingmachine/phpstan-safe-rule/phpstan-safe-rule.neon

// Ora PHPStan verifica:
$content = file_get_contents('file.txt');
// ❌ PHPStan Error: "Use Safe\file_get_contents instead!"

use function Safe\file_get_contents;
$content = file_get_contents('file.txt');
// ✅ PHPStan: OK!
```

**CONFIGURAZIONE PROGETTO**:
```neon
# phpstan.neon linea 6
- ./vendor/thecodingmachine/phpstan-safe-rule/phpstan-safe-rule.neon
```

**EFFETTO**: Developer OBBLIGATO a usare Safe functions! No scelta!

---

## 🎓 STRUMENTO 5: Larastan

### 🪄 Il Mago di Laravel

**SCOPO**: Insegnare a PHPStan i magic di Laravel

**SENZA Larastan**:
```php
User::where('email', $email)->first();
// ❌ PHPStan: "Static method where() not found on User!"

// PHPStan non SA del __callStatic magic!
```

**CON Larastan**:
```php
User::where('email', $email)->first();
// ✅ PHPStan: "OK! Larastan extension understand Eloquent!"

// Larastan aggiunge "virtual methods" a PHPStan!
```

### Cosa Comprende Larastan

1. **Eloquent Query Builder**
```php
Model::where()->orWhere()->with()->get()  // ✅ Tutto compreso!
```

2. **Facades**
```php
Cache::remember()
DB::table()
File::exists()  // ✅ Tutte le Facades!
```

3. **Container Resolution**
```php
app(Service::class)  // ✅ Capisce dependency injection!
```

4. **Relationships**
```php
$user->posts()  // ✅ Capisce HasMany!
```

5. **Collections**
```php
collect()->map()->filter()  // ✅ Metodi Collection!
```

**CONFIGURAZIONE**:
```neon
includes:
    - ./vendor/larastan/larastan/extension.neon
```

---

## 📏 STRUMENTO 6: PHPMD (PHP Mess Detector)

### 🔬 Il Giudice della Complessità

**SCOPO**: Trova "code smells" che PHPStan non vede

**LE 6 CATEGORIE DI GIUDIZIO**:

#### 1. **Clean Code**
```php
// ❌ PHPMD: "StaticAccess violation"
Str::slug($name);

// Analisi: OK per Laravel Facades (accettato)
```

#### 2. **Code Size**
```php
// ❌ PHPMD: "TooManyMethods - Class has 25 methods"
class GodClass {
    // Limit: 10 methods per class
}

// ⚠️ Refactoring needed!
```

#### 3. **Controversial**
```php
// ❌ PHPMD: "Superglobals - Access to $_SERVER"
$server = $_SERVER['HTTP_HOST'];

// Analisi: Sometimes necessary (documentato)
```

#### 4. **Design**
```php
// ❌ PHPMD: "CouplingBetweenObjects - Too many dependencies"
public function __construct(
    ServiceA $a,
    ServiceB $b,
    ServiceC $c,
    // ... 15 dependencies!
) {}

// Limit: 13 dependencies
```

#### 5. **Naming**
```php
// ❌ PHPMD: "ShortVariable - $k is too short"
foreach ($array as $k => $v) {}

// Minimum length: 3 characters
// Fix: $key => $value
```

#### 6. **Unused Code**
```php
// ❌ PHPMD: "UnusedFormalParameter"
public function process($data, $unused) {
    return $data;
}

// Fix: Remove $unused or use it!
```

### Configurazione Progetto

**Check attuale**:
```bash
cat phpmd.ruleset.xml  # ← Verifica regole attive
```

**Standard run**:
```bash
./vendor/bin/phpmd Modules/User text cleancode,codesize,design
```

---

## 💎 STRUMENTO 7: PHPInsights

### 🏆 Il Certificatore di Eccellenza

**SCOPO**: Overall code quality score

**LE 4 DIMENSIONI**:

#### 1. **Code Quality** (40%)
- Complexity
- Architecture
- Code style
- Best practices

#### 2. **Complexity** (30%)
- Cyclomatic complexity
- Cognitive complexity
- Lines per method/class

#### 3. **Architecture** (20%)
- Dependencies
- Coupling
- Cohesion
- SOLID principles

#### 4. **Style** (10%)
- PSR compliance
- Naming conventions
- Documentation

### Scoring System

```
90-100: Eccellente  🏆
80-89:  Buono       ✅
70-79:  Discreto    ⚠️
60-69:  Sufficiente 🔶
<60:    Inaccettabile ❌
```

### Run Command

```bash
./vendor/bin/phpinsights analyse Modules/User --no-interaction
```

**Output Example**:
```
Code Quality: 92.5% 🏆
Complexity:   85.0% ✅
Architecture: 78.5% ⚠️
Style:        95.0% 🏆

Overall: 87.8% ✅ GOOD
```

---

## 🎭 IL TRIANGOLO DELLA VERITÀ

### Come i 3 Pilastri Collaborano

```
           DEVELOPER WRITES CODE
                     ↓
         ┌───────────┴───────────┐
         │                       │
    IDE HELPER              PHPSTAN L10
    generates              reads @property
    @property              enforces types
         │                       │
         └───────────┬───────────┘
                     ↓
              CODE WITH TYPES
                     ↓
         ┌───────────┴───────────┐
         │                       │
    WEBMOZART ASSERT        SAFE FUNCTIONS
    runtime guards          fail-fast errors
         │                       │
         └───────────┬───────────┘
                     ↓
           PRODUCTION READY CODE
```

**FLUSSO**:
1. Developer modifica database schema
2. Run `ide-helper:models` → genera @property
3. PHPStan verifica types → static safety
4. Assert guards → runtime safety
5. Safe functions → no silent failures
6. PHPMD → complexity checks
7. PHPInsights → overall quality

---

## 🧘 LO ZEN DELLA QUALITÀ

### I 10 Principi

1. **Static Prima, Runtime Dopo**: PHPStan trova bug gratis, Assert costa performance
2. **Explicit Better Than Implicit**: Type hints > PHPDoc > mixed
3. **Fail Fast, Fail Loud**: Exception > false return > null return
4. **Document What Cannot Be Typed**: PHPDoc per array shapes complessi
5. **Trust Types, Verify Values**: PHPStan verifica structure, Assert verifica content
6. **Single Source of Truth**: @property nel model, non in file separato
7. **Minimal Ignore**: @phpstan-ignore SOLO per terze parti
8. **Quality Gates**: Tutti i tool passano PRIMA di commit
9. **Continuous Improvement**: Re-run tools dopo OGNI modifica
10. **Documentation is Code**: Docs obsoleti = codice obsoleto

---

## 🎯 APPLICAZIONE AL PROBLEMA property_exists

### La Connessione Filosofica

**PERCHÉ** eliminare property_exists?

1. **IDE Helper Perspective**:
   - IDE Helper genera `@property string $email`
   - PHPStan SA che email esiste
   - `property_exists()` è RIDONDANTE!

2. **Webmozart Assert Perspective**:
   - Se vuoi verificare type: `Assert::isInstanceOf($record, User::class)`
   - Se vuoi verificare attributo: `isset($record->email)`
   - `property_exists()` è SBAGLIATO per Eloquent!

3. **Safe Functions Perspective**:
   - `property_exists()` NON ha versione Safe (perché già sicura)
   - Ma su Eloquent è SEMANTICAMENTE sbagliata!
   - `isset()` è la "safe" way per magic properties!

### Il Pattern Completo

```php
// VECCHIO APPROCCIO (property_exists)
if (is_object($record) && property_exists($record, 'email')) {
    // ❌ SEMPRE false per Eloquent attributes!
    return $record->email;
}

// NUOVO APPROCCIO (Multi-level safety)

// Level 1: Instance check (Webmozart)
Assert::isInstanceOf($record, User::class);
// ✅ PHPStan ora SA che $record è User
// ✅ PHPStan vede @property string $email via IDE Helper!

// Level 2: Value check (isset)
if (isset($record->email)) {
    // ✅ Verifica che email ha valore (via __get())
    return $record->email;  // ✅ PHPStan: string (da @property)
}

// Level 3: Null coalescing (quando appropriate)
return $record->email ?? 'default';
// ✅ Più conciso, stesso effetto!
```

---

## 📋 CHECKLIST MULTI-TOOL per OGNI FILE

Quando modifichi un file:

### Step 1: Modifica
```php
// Sostituisci property_exists con isset/Assert
```

### Step 2: IDE Helper (se model modificato)
```bash
php artisan ide-helper:models MyModel --write
```

### Step 3: PHPStan Level 10
```bash
./vendor/bin/phpstan analyse path/to/file.php --level=10
# DEVE essere: [OK] No errors
```

### Step 4: PHPMD
```bash
./vendor/bin/phpmd path/to/file.php text cleancode,codesize,design
# Verifica output, documenta warnings se accettabili
```

### Step 5: PHPInsights (per file importanti)
```bash
./vendor/bin/phpinsights analyse path/to/file.php --no-interaction
# Target: >80 score
```

### Step 6: Laravel Pint
```bash
./vendor/bin/pint path/to/file.php
# Auto-format PSR-12
```

---

## 🗺️ STRATEGIA ELIMINAZIONE property_exists

### Priority Matrix

| File Type | Count | Priority | Tools | Effort |
|-----------|-------|----------|-------|--------|
| **Models** | 5 | CRITICAL | All 4 | High |
| **Filament Resources** | 10 | HIGH | Stan+MD | Medium |
| **Actions** | 15 | MEDIUM | Stan+MD | Low |
| **Widgets** | 8 | HIGH | All 4 | Medium |
| **Tests** | 2 | LOW | Stan | Low |
| **Docs** | 49 | INFO | None | Zero |

### Workflow Per File

```
1. READ file → Comprendi contesto
2. IDENTIFY pattern → Quale dei 3 archetipi?
3. REPLACE property_exists → isset/hasAttribute/method_exists
4. ADD comment → PHPStan Level 10 reasoning
5. FORMAT → ./vendor/bin/pint
6. VERIFY:
   ✅ phpstan --level=10
   ✅ phpmd cleancode,design
   ✅ phpinsights (se critico)
7. DOCUMENT → Aggiungi a changelog modulo
```

---

## 📖 ESEMPI PRATICI DAL PROGETTO

### Esempio 1: User/Filament/Resources/BaseProfileResource

**PRIMA**:
```php
$userValue = property_exists($record, 'user') ? $record->user : null;
```

**ANALISI**:
- $record è Profile model
- 'user' è relationship (BelongsTo)
- property_exists() SEMPRE false per relationships!

**DOPO**:
```php
// PHPStan L10: isset() respects Eloquent __get() for relationships
$userValue = isset($record->user) ? $record->user : null;

// O meglio (null coalescing):
$userValue = $record->user ?? null;
```

**VERIFICA**:
```bash
./vendor/bin/phpstan analyse Modules/User/app/Filament/Resources/BaseProfileResource/Pages/ListProfiles.php --level=10
./vendor/bin/phpmd Modules/User/app/Filament/Resources/BaseProfileResource/Pages/ListProfiles.php text design
```

---

### Esempio 2: Media/Filament/Resources/MediaResource

**PRIMA**:
```php
->visible(fn($record): bool =>
    is_object($record) &&
    property_exists($record, 'type') &&
    $record->type === 'image'
)
```

**ANALISI**:
- $record è Media model con @property string $type
- property_exists() ridondante se @property esiste!

**DOPO**:
```php
// PHPStan L10: @property string $type makes isset() redundant
->visible(fn(Media $record): bool => $record->type === 'image')

// O se $record può essere mixed:
->visible(fn($record): bool =>
    is_object($record) &&
    isset($record->type) &&  // ← Cambio QUI
    $record->type === 'image'
)
```

---

### Esempio 3: Xot/Actions/Cast/SafeObjectCastAction

**PRIMA**:
```php
if (!is_object($value) || !property_exists($value, 'method')) {
    throw new Exception('Invalid');
}
```

**ANALISI**:
- Se $value NON è Model, property_exists() OK
- Se $value È Model, property_exists() SBAGLIATO
- Soluzione: Check se è Eloquent prima!

**DOPO**:
```php
if (!is_object($value)) {
    throw new Exception('Value must be object');
}

// Se è Eloquent Model, usa isset
if ($value instanceof \Illuminate\Database\Eloquent\Model) {
    if (!isset($value->method)) {
        throw new Exception('Model missing attribute');
    }
}
// Se è Standard Object, usa property_exists
else {
    if (!property_exists($value, 'method')) {
        throw new Exception('Object missing property');
    }
}
```

---

## 🎯 METRICHE DI SUCCESSO

### Before Cleanup
- `property_exists()` su Models: 89 occorrenze ❌
- PHPStan warnings: Potenziali false negatives
- Code smell: Property checks semantically wrong
- IDE support: Inconsistent autocomplete

### After Cleanup
- `property_exists()` su Models: 0 occorrenze ✅
- PHPStan: Perfetto allineamento con @property
- Code correctness: Semantically aligned with Eloquent
- IDE support: Perfect autocomplete everywhere

---

## 🔗 Collegamenti Intra-Modulo

- [eloquent-properties-best-practices.md](./eloquent-properties-best-practices.md) - Pattern specifici
- [eloquent-models-critical-rules.md](./eloquent-models-critical-rules.md) - Regole critiche
- [property-exists-elimination-philosophy.md](./property-exists-elimination-philosophy.md) - Filosofia
- [phpstan/](./phpstan/) - PHPStan guides
- [../../../docs/quality/](../../../docs/quality/) - Root quality docs

---

## ✍️ CITAZIONI FILOSOFICHE

> "Gli strumenti di qualità sono come i sensi: PHPStan vede, Assert sente, Safe previene, PHPMD giudica, PHPInsights certifica. Insieme formano la percezione completa della qualità."
>
> — **Principio della Percezione Multi-Sensoriale**

> "property_exists() chiede 'Esiste nella forma?'
> isset() chiede 'Esiste nella sostanza?'
> Per Eloquent, la sostanza (DB) è più vera della forma (class)."
>
> — **Kōan della Forma e Sostanza**

> "Un bug trovato da PHPStan costa 1€.
> Un bug fermato da Assert costa 10€.
> Un bug in produzione costa 1000€.
> La prevenzione è economia."
>
> — **Principio del Costo Esponenziale**

---

**Creato**: 5 Novembre 2025
**Scopo**: Unificare la comprensione degli strumenti
**Status**: 📘 Master Reference Document
**Revision**: 1.0

**Ora posso procedere con l'eliminazione sistematica! ⚔️**

---

## quality-tools-status-nov

*Consolidated from: `quality-tools-status-nov.md`*


## 🎯 Obiettivo

Verificare e configurare tutti i tool di qualità del codice:
- PHPStan Level 10
- PHPMD (PHP Mess Detector)
- PHPInsights (Code Quality & Architecture)

## ✅ PHPStan - COMPLETATO

### Status
**SUCCESSO TOTALE**: 0 errori PHPStan Level 10 su tutti i moduli!

### Configurazione
- File: `phpstan.neon` (nella root Laravel)
- Livello: max (Level 10)
- Memoria: illimitata (`--memory-limit=-1`)

### Comando
```bash
cd laravel
./vendor/bin/phpstan analyse Modules --memory-limit=-1
```

### Risultato
```
[OK] No errors
```

### Documentazione
- [phpstan-level10-success-nov2025.md](./phpstan-level10-success-nov2025.md)

## ⚠️ PHPMD - NON INSTALLATO

### Status
**NON DISPONIBILE**: PHPMD non è nelle dipendenze composer

### Installazione Richiesta
```bash
cd laravel
composer require --dev phpmd/phpmd
```

### Configurazione Consigliata
Dopo installazione, creare `phpmd.xml`:

```xml
<?xml version="1.0"?>
<ruleset name="Laraxot PHPMD Rules">
    <rule ref="rulesets/cleancode.xml">
        <exclude name="StaticAccess"/>
        <exclude name="ElseExpression"/>
    </rule>
    <rule ref="rulesets/codesize.xml"/>
    <rule ref="rulesets/design.xml"/>
    <rule ref="rulesets/naming.xml"/>
    <rule ref="rulesets/unusedcode.xml"/>
</ruleset>
```

### Uso
```bash
./vendor/bin/phpmd Modules text phpmd.xml
```

### Problemi Noti
- **Trait Collision**: `TransTrait` in `XotBasePage` causa collision
- Richiede risoluzione prima dell'analisi completa

## ⚠️ PHPInsights - PROBLEMI CONFIGURAZIONE

### Status
**PARZIALMENTE DISPONIBILE**: Installato ma con problemi di configurazione

### Problema
```
composer.lock not found. Try launch composer install
```

**Root Cause**: PHPInsights cerca `composer.lock` in posizione errata o ha issue con symlink

### Workaround
```bash
# Disabilitare security check
./vendor/bin/phpinsights analyse Modules/Xot \
  --disable-security-check \
  --min-quality=85 \
  --min-complexity=80 \
  --min-architecture=85
```

**Problema Persistente**: Anche con `--disable-security-check` richiede composer.lock

### Soluzione Consigliata
1. Verificare configurazione in `phpinsights.php`
2. Aggiornare PHPInsights all'ultima versione
3. Controllare issue GitHub: https://github.com/nunomaduro/phpinsights/issues

## 🔧 Tool Alternativi Disponibili

### 1. PHP-CS-Fixer
**Status**: Configurazioni esistenti trovate
- `Modules/Activity/.php-cs-fixer.php`
- `Modules/UI/.php-cs-fixer.php`
- `Modules/Notify/.php-cs-fixer.php`

**Uso**:
```bash
./vendor/bin/php-cs-fixer fix Modules/Xot --dry-run --diff
```

### 2. Rector
**Status**: DA VERIFICARE

**Configurazione**: `rector.php` (root Laravel)

**Uso**:
```bash
./vendor/bin/rector process Modules --dry-run
./vendor/bin/rector process Modules
```

### 3. Psalm
**Status**: DA VERIFICARE

**Configurazione**: `psalm.xml` (se esiste)

**Uso**:
```bash
./vendor/bin/psalm --show-info=true
```

## 📊 Priorità di Installazione

### Alta Priorità
1. **PHPMD** - Analisi code smells essenziale
2. **PHP-CS-Fixer** - Correzione automatica stile

### Media Priorità
3. **Fix PHPInsights** - Metriche architettura
4. **Rector** - Refactoring automatico

### Bassa Priorità
5. **Psalm** - Complementare a PHPStan

## 🎯 Workflow Consigliato

### Pre-Commit
```bash
# 1. PHPStan (OBBLIGATORIO)
./vendor/bin/phpstan analyse path/to/modified/file.php --level=10

# 2. PHP Syntax Check
php -l path/to/modified/file.php

# 3. PHP-CS-Fixer (se disponibile)
./vendor/bin/php-cs-fixer fix path/to/modified/file.php --dry-run
```

### Post-Feature
```bash
# 1. PHPStan completo
./vendor/bin/phpstan analyse Modules

# 2. PHPMD (quando disponibile)
./vendor/bin/phpmd Modules text phpmd.xml

# 3. PHPInsights (quando disponibile)
./vendor/bin/phpinsights analyse Modules --min-quality=90
```

## 📚 Documentazione Correlata

- [PHPStan Level 10 Success](./phpstan-level10-success-nov2025.md) - Success story
- [Code Quality Standards](./code-quality-standards.md) - Standard qualità codice
- [Quality Tools Zen](./quality-tools-zen.md) - Filosofia quality tools
- [Docs Improvements](./docs-improvements-nov2025.md) - Miglioramenti docs

## 🚀 Next Steps

1. Installare PHPMD: `composer require --dev phpmd/phpmd`
2. Creare configurazione `phpmd.xml`
3. Risolvere trait collision in `XotBasePage`
4. Verificare e fixare PHPInsights configuration
5. Eseguire analisi completa dopo installazione

## 🎓 Lezioni Apprese

### Tool Installation
- Non assumere tool installati
- Verificare sempre con `composer show`
- Documentare tool richiesti in README

### Configuration Management
- Mantenere file di configurazione in repository
- Documentare posizione e scopo di ogni config
- Testare configurazioni dopo modifiche

### Error Handling
- Tool non disponibili → documentare e continuare
- Problemi configurazione → creare workaround
- Blocchi → cercare alternative (php-parse, php -l)

**Filosofia**: "Il miglior tool è quello che funziona. Se non funziona, documentalo e vai avanti."

## Aggiornamento Tooling 2025-11-08

- PHPMD eseguito sui file aggiornati (`GetAllIconsAction`, `InlineDatePicker`, `Extra`, `XotBasePivot`, `XotBaseUuidModel`): nessuna nuova violazione rilevata.
- PHPInsights eseguito sugli stessi file: esito positivo (complessità segnalata da soglie legacy, documentata nelle relative sezioni di modulo).
- Metriche archiviate nelle docs dei moduli UI, User e Xot per garantire tracciabilità futura.
# Status Quality Tools - Novembre 2025

## 🎯 Obiettivo

Verificare e configurare tutti i tool di qualità del codice:
- PHPStan Level 10
- PHPMD (PHP Mess Detector)
- PHPInsights (Code Quality & Architecture)

## ✅ PHPStan - COMPLETATO

### Status
**SUCCESSO TOTALE**: 0 errori PHPStan Level 10 su tutti i moduli!

### Configurazione
- File: `phpstan.neon` (nella root Laravel)
- Livello: max (Level 10)
- Memoria: illimitata (`--memory-limit=-1`)

### Comando
```bash
cd laravel
./vendor/bin/phpstan analyse Modules --memory-limit=-1
```

### Risultato
```
[OK] No errors
```

### Documentazione
- [phpstan-level10-success-nov2025.md](./phpstan-level10-success-nov2025.md)

## ⚠️ PHPMD - NON INSTALLATO

### Status
**NON DISPONIBILE**: PHPMD non è nelle dipendenze composer

### Installazione Richiesta
```bash
cd laravel
composer require --dev phpmd/phpmd
```

### Configurazione Consigliata
Dopo installazione, creare `phpmd.xml`:

```xml
<?xml version="1.0"?>
<ruleset name="Laraxot PHPMD Rules">
    <rule ref="rulesets/cleancode.xml">
        <exclude name="StaticAccess"/>
        <exclude name="ElseExpression"/>
    </rule>
    <rule ref="rulesets/codesize.xml"/>
    <rule ref="rulesets/design.xml"/>
    <rule ref="rulesets/naming.xml"/>
    <rule ref="rulesets/unusedcode.xml"/>
</ruleset>
```

### Uso
```bash
./vendor/bin/phpmd Modules text phpmd.xml
```

### Problemi Noti
- **Trait Collision**: `TransTrait` in `XotBasePage` causa collision
- Richiede risoluzione prima dell'analisi completa

## ⚠️ PHPInsights - PROBLEMI CONFIGURAZIONE

### Status
**PARZIALMENTE DISPONIBILE**: Installato ma con problemi di configurazione

### Problema
```
composer.lock not found. Try launch composer install
```

**Root Cause**: PHPInsights cerca `composer.lock` in posizione errata o ha issue con symlink

### Workaround
```bash
# Disabilitare security check
./vendor/bin/phpinsights analyse Modules/Xot \
  --disable-security-check \
  --min-quality=85 \
  --min-complexity=80 \
  --min-architecture=85
```

**Problema Persistente**: Anche con `--disable-security-check` richiede composer.lock

### Soluzione Consigliata
1. Verificare configurazione in `phpinsights.php`
2. Aggiornare PHPInsights all'ultima versione
3. Controllare issue GitHub: https://github.com/nunomaduro/phpinsights/issues

## 🔧 Tool Alternativi Disponibili

### 1. PHP-CS-Fixer
**Status**: Configurazioni esistenti trovate
- `Modules/Activity/.php-cs-fixer.php`
- `Modules/UI/.php-cs-fixer.php`
- `Modules/Notify/.php-cs-fixer.php`

**Uso**:
```bash
./vendor/bin/php-cs-fixer fix Modules/Xot --dry-run --diff
```

### 2. Rector
**Status**: DA VERIFICARE

**Configurazione**: `rector.php` (root Laravel)

**Uso**:
```bash
./vendor/bin/rector process Modules --dry-run
./vendor/bin/rector process Modules
```

### 3. Psalm
**Status**: DA VERIFICARE

**Configurazione**: `psalm.xml` (se esiste)

**Uso**:
```bash
./vendor/bin/psalm --show-info=true
```

## 📊 Priorità di Installazione

### Alta Priorità
1. **PHPMD** - Analisi code smells essenziale
2. **PHP-CS-Fixer** - Correzione automatica stile

### Media Priorità
3. **Fix PHPInsights** - Metriche architettura
4. **Rector** - Refactoring automatico

### Bassa Priorità
5. **Psalm** - Complementare a PHPStan

## 🎯 Workflow Consigliato

### Pre-Commit
```bash
# 1. PHPStan (OBBLIGATORIO)
./vendor/bin/phpstan analyse path/to/modified/file.php --level=10

# 2. PHP Syntax Check
php -l path/to/modified/file.php

# 3. PHP-CS-Fixer (se disponibile)
./vendor/bin/php-cs-fixer fix path/to/modified/file.php --dry-run
```

### Post-Feature
```bash
# 1. PHPStan completo
./vendor/bin/phpstan analyse Modules

# 2. PHPMD (quando disponibile)
./vendor/bin/phpmd Modules text phpmd.xml

# 3. PHPInsights (quando disponibile)
./vendor/bin/phpinsights analyse Modules --min-quality=90
```

## 📚 Documentazione Correlata

- [PHPStan Level 10 Success](./phpstan-level10-success-nov2025.md) - Success story
- [Code Quality Standards](./code-quality-standards.md) - Standard qualità codice
- [Quality Tools Zen](./quality-tools-zen.md) - Filosofia quality tools
- [Docs Improvements](./docs-improvements-nov2025.md) - Miglioramenti docs

## 🚀 Next Steps

1. Installare PHPMD: `composer require --dev phpmd/phpmd`
2. Creare configurazione `phpmd.xml`
3. Risolvere trait collision in `XotBasePage`
4. Verificare e fixare PHPInsights configuration
5. Eseguire analisi completa dopo installazione

## 🎓 Lezioni Apprese

### Tool Installation
- Non assumere tool installati
- Verificare sempre con `composer show`
- Documentare tool richiesti in README

### Configuration Management
- Mantenere file di configurazione in repository
- Documentare posizione e scopo di ogni config
- Testare configurazioni dopo modifiche

### Error Handling
- Tool non disponibili → documentare e continuare
- Problemi configurazione → creare workaround
- Blocchi → cercare alternative (php-parse, php -l)

**Filosofia**: "Il miglior tool è quello che funziona. Se non funziona, documentalo e vai avanti."

## Aggiornamento Tooling 2025-11-08

- PHPMD eseguito sui file aggiornati (`GetAllIconsAction`, `InlineDatePicker`, `Extra`, `XotBasePivot`, `XotBaseUuidModel`): nessuna nuova violazione rilevata.
- PHPInsights eseguito sugli stessi file: esito positivo (complessità segnalata da soglie legacy, documentata nelle relative sezioni di modulo).
- Metriche archiviate nelle docs dei moduli UI, User e Xot per garantire tracciabilità futura.

---

## quality-tools-status

*Consolidated from: `quality-tools-status.md`*


## 🎯 Obiettivo

Verificare e configurare tutti i tool di qualità del codice:
- PHPStan Level 10
- PHPMD (PHP Mess Detector)
- PHPInsights (Code Quality & Architecture)

## ✅ PHPStan - COMPLETATO

### Status
**SUCCESSO TOTALE**: 0 errori PHPStan Level 10 su tutti i moduli!

### Configurazione
- File: `phpstan.neon` (nella root Laravel)
- Livello: max (Level 10)
- Memoria: illimitata (`--memory-limit=-1`)

### Comando
```bash
cd laravel
./vendor/bin/phpstan analyse Modules --memory-limit=-1
```

### Risultato
```
[OK] No errors
```

### Documentazione
- [phpstan-level10-success-nov2025.md](./phpstan-level10-success-nov2025.md)

## ⚠️ PHPMD - NON INSTALLATO

### Status
**NON DISPONIBILE**: PHPMD non è nelle dipendenze composer

### Installazione Richiesta
```bash
cd laravel
composer require --dev phpmd/phpmd
```

### Configurazione Consigliata
Dopo installazione, creare `phpmd.xml`:

```xml
<?xml version="1.0"?>
<ruleset name="Laraxot PHPMD Rules">
    <rule ref="rulesets/cleancode.xml">
        <exclude name="StaticAccess"/>
        <exclude name="ElseExpression"/>
    </rule>
    <rule ref="rulesets/codesize.xml"/>
    <rule ref="rulesets/design.xml"/>
    <rule ref="rulesets/naming.xml"/>
    <rule ref="rulesets/unusedcode.xml"/>
</ruleset>
```

### Uso
```bash
./vendor/bin/phpmd Modules text phpmd.xml
```

### Problemi Noti
- **Trait Collision**: `TransTrait` in `XotBasePage` causa collision
- Richiede risoluzione prima dell'analisi completa

## ⚠️ PHPInsights - PROBLEMI CONFIGURAZIONE

### Status
**PARZIALMENTE DISPONIBILE**: Installato ma con problemi di configurazione

### Problema
```
composer.lock not found. Try launch composer install
```

**Root Cause**: PHPInsights cerca `composer.lock` in posizione errata o ha issue con symlink

### Workaround
```bash
# Disabilitare security check
./vendor/bin/phpinsights analyse Modules/Xot \
  --disable-security-check \
  --min-quality=85 \
  --min-complexity=80 \
  --min-architecture=85
```

**Problema Persistente**: Anche con `--disable-security-check` richiede composer.lock

### Soluzione Consigliata
1. Verificare configurazione in `phpinsights.php`
2. Aggiornare PHPInsights all'ultima versione
3. Controllare issue GitHub: https://github.com/nunomaduro/phpinsights/issues

## 🔧 Tool Alternativi Disponibili

### 1. PHP-CS-Fixer
**Status**: Configurazioni esistenti trovate
- `Modules/Activity/.php-cs-fixer.php`
- `Modules/UI/.php-cs-fixer.php`
- `Modules/Notify/.php-cs-fixer.php`

**Uso**:
```bash
./vendor/bin/php-cs-fixer fix Modules/Xot --dry-run --diff
```

### 2. Rector
**Status**: DA VERIFICARE

**Configurazione**: `rector.php` (root Laravel)

**Uso**:
```bash
./vendor/bin/rector process Modules --dry-run
./vendor/bin/rector process Modules
```

### 3. Psalm
**Status**: DA VERIFICARE

**Configurazione**: `psalm.xml` (se esiste)

**Uso**:
```bash
./vendor/bin/psalm --show-info=true
```

## 📊 Priorità di Installazione

### Alta Priorità
1. **PHPMD** - Analisi code smells essenziale
2. **PHP-CS-Fixer** - Correzione automatica stile

### Media Priorità
3. **Fix PHPInsights** - Metriche architettura
4. **Rector** - Refactoring automatico

### Bassa Priorità
5. **Psalm** - Complementare a PHPStan

## 🎯 Workflow Consigliato

### Pre-Commit
```bash
# 1. PHPStan (OBBLIGATORIO)
./vendor/bin/phpstan analyse path/to/modified/file.php --level=10

# 2. PHP Syntax Check
php -l path/to/modified/file.php

# 3. PHP-CS-Fixer (se disponibile)
./vendor/bin/php-cs-fixer fix path/to/modified/file.php --dry-run
```

### Post-Feature
```bash
# 1. PHPStan completo
./vendor/bin/phpstan analyse Modules

# 2. PHPMD (quando disponibile)
./vendor/bin/phpmd Modules text phpmd.xml

# 3. PHPInsights (quando disponibile)
./vendor/bin/phpinsights analyse Modules --min-quality=90
```

## 📚 Documentazione Correlata

- [PHPStan Level 10 Success](./phpstan-level10-success-nov2025.md) - Success story
- [Code Quality Standards](./code-quality-standards.md) - Standard qualità codice
- [Quality Tools Zen](./quality-tools-zen.md) - Filosofia quality tools
- [Docs Improvements](./docs-improvements-nov2025.md) - Miglioramenti docs

## 🚀 Next Steps

1. Installare PHPMD: `composer require --dev phpmd/phpmd`
2. Creare configurazione `phpmd.xml`
3. Risolvere trait collision in `XotBasePage`
4. Verificare e fixare PHPInsights configuration
5. Eseguire analisi completa dopo installazione

## 🎓 Lezioni Apprese

### Tool Installation
- Non assumere tool installati
- Verificare sempre con `composer show`
- Documentare tool richiesti in README

### Configuration Management
- Mantenere file di configurazione in repository
- Documentare posizione e scopo di ogni config
- Testare configurazioni dopo modifiche

### Error Handling
- Tool non disponibili → documentare e continuare
- Problemi configurazione → creare workaround
- Blocchi → cercare alternative (php-parse, php -l)

**Filosofia**: "Il miglior tool è quello che funziona. Se non funziona, documentalo e vai avanti."

## Aggiornamento Tooling 2025-11-08

- PHPMD eseguito sui file aggiornati (`GetAllIconsAction`, `InlineDatePicker`, `Extra`, `XotBasePivot`, `XotBaseUuidModel`): nessuna nuova violazione rilevata.
- PHPInsights eseguito sugli stessi file: esito positivo (complessità segnalata da soglie legacy, documentata nelle relative sezioni di modulo).
- Metriche archiviate nelle docs dei moduli UI, User e Xot per garantire tracciabilità futura.

---

## quality-tools-zen

*Consolidated from: `quality-tools-zen.md`*


**Data**: 2025-01-05
**Filosofia**: Type Safety, Fail Fast, Zero Tolerance

## 🎯 La Visione Complessiva

### Il Problema Esistenziale del PHP

PHP è nato con un peccato originale: **gestione degli errori tramite return false invece di eccezioni**.

```php
// ❌ Il Peccato Originale di PHP
$content = file_get_contents('file.txt'); // Ritorna false se errore
if ($content === false) {
    // Oh no, devo ricordare di controllare!
}
```

Questo ha creato un ecosistema dove:
- Gli sviluppatori sono **pigri** (good!)
- I controlli vengono **dimenticati** (bad!)
- Gli errori vengono **scoperti tardi** (terrible!)

### La Soluzione: Un Ecosistema di 5 Pilastri

```
┌─────────────────────────────────────────────────────┐
│                   TYPE SAFETY                        │
│                                                      │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐         │
│  │ PHPStan  │  │ Larastan │  │IDE Helper│         │
│  │   L10    │  │          │  │          │         │
│  └──────────┘  └──────────┘  └──────────┘         │
│                                                      │
│  ┌──────────┐  ┌──────────┐                        │
│  │  Assert  │  │   Safe   │                        │
│  │          │  │          │                        │
│  └──────────┘  └──────────┘                        │
│                                                      │
│  ┌──────────┐  ┌──────────┐                        │
│  │  PHPMD   │  │ Insights │                        │
│  │          │  │          │                        │
│  └──────────┘  └──────────┘                        │
└─────────────────────────────────────────────────────┘
```

## 🧘 Lo Zen di Ogni Strumento

### 1. PHPStan - Il Guru della Type Safety

**Mantra**: "Conosci i tuoi tipi prima che si manifestino"

**Filosofia**:
- Analisi **statica** = nessun runtime = nessun side effect
- **Livello 10** = illuminazione totale
- **Zero baseline** = nessun peccato originale da nascondere

**Zen Pattern**:
```php
// ❌ L'Ignoranza
function process($data) {
    return $data->value;
}

// ✅ L'Illuminazione
/**
 * @param object{value: string} $data
 * @return string
 */
function process(object $data): string {
    return $data->value;
}
```

**Comandamenti**:
1. Mai abbassare il livello
2. Mai usare baseline
3. Mai ignorare errori senza @phpstan-ignore
4. Ogni mixed è un peccato da confessare

### 2. Larastan - Il Ponte tra Laravel e PHPStan

**Mantra**: "Laravel è magico, ma la magia deve essere compresa"

**Filosofia**:
- Laravel usa **magic methods** (`__get`, `__call`)
- PHPStan non vede la magia
- Larastan traduce la magia in tipi concreti

**Esempio di Magia Tradotta**:
```php
// Laravel Magic
User::where('email', $email)->first();

// Larastan capisce che ritorna User|null
// Non serve scrivere PHPDoc!
```

**Illuminazioni Fornite**:
- `Builder<Model>` types
- Eloquent relationships
- Facade auto-completion
- Collection generics

### 3. Laravel IDE Helper - Il Documentatore Automatico

**Mantra**: "La documentazione nasce dal database, non dalle dita"

**Filosofia**:
- Le **properties Eloquent sono dinamiche**
- Gli **IDE non vedono le colonne DB**
- La **soluzione è generare PHPDoc automaticamente**

**Ciclo di Vita**:
```bash
# 1. Schema cambia
php artisan migrate

# 2. PHPDoc si aggiorna
php artisan ide-helper:models -W

# 3. IDE vede tutto
# 4. PHPStan capisce tutto
# 5. Developer è felice
```

**Tre Comandi Sacri**:
```bash
php artisan ide-helper:generate  # Facades
php artisan ide-helper:models    # Models
php artisan ide-helper:meta      # PhpStorm Meta
```

### 4. Webmozart Assert - Il Guardiano dei Confini

**Mantra**: "Fail fast, fail loud, fail with dignity"

**Filosofia**:
- **Mai fidarsi dell'input**
- **Esplodere subito** se qualcosa è sbagliato
- **Messaggi chiari** > debug ore

**Pattern di Guardia**:
```php
use Webmozart\Assert\Assert;

public function process(array $data): Result
{
    // GUARDS - Fail fast
    Assert::keyExists($data, 'email', 'Email is required');
    Assert::email($data['email'], 'Invalid email format');
    Assert::stringNotEmpty($data['name'], 'Name cannot be empty');

    // LOGIC - Safe to proceed
    return new Result($data);
}
```

**Vantaggi**:
- **Type narrowing**: PHPStan capisce i tipi dopo Assert
- **Errori descrittivi**: "Expected string, got array"
- **Fail fast**: Esplode prima di danni
- **Documentazione**: Assert è documentazione vivente

### 5. Safe - Il Wrapper che Esplode

**Mantra**: "Exceptions > false"

**Filosofia**:
- PHP core functions ritornano `false` in caso di errore
- Gli sviluppatori dimenticano di controllare
- Safe riscrive tutto per lanciare eccezioni

**Trasformazione**:
```php
// ❌ PHP Standard - Può ritornare false
$content = file_get_contents('file.txt');
if ($content === false) {
    throw new Exception('File not found');
}

// ✅ Safe - Lancia eccezione automaticamente
use function Safe\file_get_contents;

$content = file_get_contents('file.txt');
// Se fallisce, esplode. Non serve if.
```

**Integrazione PHPStan**:
```neon
# phpstan.neon
includes:
    - vendor/thecodingmachine/phpstan-safe-rule/phpstan-safe-rule.neon
```

PHPStan ti avvisa: "Stai usando file_get_contents, usa Safe\file_get_contents"

### 6. PHPMD - Il Detective della Complessità

**Mantra**: "La semplicità è la massima sofisticazione"

**Filosofia**:
- **Code smell detection**
- **Complessità ciclomatica**
- **Metriche oggettive** su code quality

**Rulesets**:
```xml
<!-- phpmd.ruleset.xml -->
<ruleset>
    <rule ref="rulesets/codesize.xml"/>    <!-- Dimensioni -->
    <rule ref="rulesets/design.xml"/>      <!-- Design patterns -->
    <rule ref="rulesets/naming.xml"/>      <!-- Naming conventions -->
    <rule ref="rulesets/unusedcode.xml"/>  <!-- Dead code -->
</ruleset>
```

**Metriche Zen**:
- **Cyclomatic Complexity < 10**: Un metodo fa una cosa
- **NPath Complexity < 200**: Pochi percorsi di esecuzione
- **Method Length < 20**: Leggibile in un colpo d'occhio

### 7. PHP Insights - Il Maestro della Bellezza

**Mantra**: "Il codice è letto 10 volte più di quanto è scritto"

**Filosofia**:
- **4 pilastri**: Code, Architecture, Complexity, Style
- **Score complessivo**: Gamification della qualità
- **Best practices**: Laravel conventions

**Categorie**:
1. **Code Quality**: Errori logici
2. **Architecture**: Struttura e design
3. **Complexity**: Semplicità del codice
4. **Style**: Consistenza estetica

## 🔄 L'Integrazione Perfetta - Il Workflow

### Durante lo Sviluppo

```bash
# 1. Scrivi codice
# 2. PHPStan controlla i tipi
./vendor/bin/phpstan analyze --level=10

# 3. PHPMD controlla complessità
./vendor/bin/phpmd app text phpmd.ruleset.xml

# 4. PHP Insights controlla tutto
php artisan insights --min-quality=90
```

### Pre-Commit Hook

``bash
#!/bin/bash
# .git/hooks/pre-commit

echo "Running quality checks..."

# PHPStan
./vendor/bin/phpstan analyze --level=10 || exit 1

# PHPMD
./vendor/bin/phpmd app text phpmd.ruleset.xml || exit 1

# PHP Insights
php artisan insights --min-quality=90 --min-complexity=90 || exit 1

echo "✅ Quality checks passed!"
```

### CI/CD Pipeline

```

```yaml
# .github/workflows/quality.yml
steps:
  - name: PHPStan
    run: vendor/bin/phpstan analyze --level=10

  - name: PHPMD
    run: vendor/bin/phpmd app text phpmd.ruleset.xml

  - name: PHP Insights
    run: php artisan insights --min-quality=90
```

## 🎓 Pattern e Best Practices

### Pattern 1: Type-Safe Input Validation

```php
use Webmozart\Assert\Assert;
use function Safe\json_decode;

class UserService
{
    public function createUser(array $data): User
    {
        // Assert: Guardia ai confini
        Assert::keyExists($data, 'email');
        Assert::email($data['email']);
        Assert::keyExists($data, 'name');
        Assert::stringNotEmpty($data['name']);

        // Safe: Parsing sicuro
        $metadata = json_decode($data['metadata'] ?? '{}');

        // Type hint: PHPStan felice
        return User::create([
            'email' => $data['email'],
            'name' => $data['name'],
            'metadata' => $metadata,
        ]);
    }
}
```

### Pattern 2: Safe File Operations

```php
use function Safe\file_get_contents;
use function Safe\json_decode;
use Webmozart\Assert\Assert;

class ConfigLoader
{
    public function load(string $path): Config
    {
        // Assert: Path validation
        Assert::fileExists($path);
        Assert::readable($path);

        // Safe: No false returns
        $content = file_get_contents($path);
        $data = json_decode($content, true);

        // Assert: Data validation
        Assert::isArray($data);
        Assert::keyExists($data, 'app_name');

        return new Config($data);
    }
}
```

### Pattern 3: Eloquent con IDE Helper

```php
/**
 * @property int $id
 * @property string $email
 * @property string $name
 * @property \Illuminate\Support\Carbon $created_at
 * @property-read Profile $profile
 * @method static Builder|User whereEmail(string $email)
 */
class User extends Model
{
    // IDE Helper genera questo PHPDoc automaticamente
    // PHPStan capisce tutti i tipi
    // property_exists non serve mai
}
```

## 📊 Metriche di Successo

### PHPStan Level 10
- **0 errori** = obiettivo raggiunto
- **Type coverage** 100%
- **No baseline**

### PHPMD
- **Cyclomatic Complexity** < 10
- **NPath Complexity** < 200
- **No unused code**

### PHP Insights
- **Code Quality**: 100%
- **Architecture**: 100%
- **Complexity**: > 90%
- **Style**: > 95%

## 🚀 Getting Started - 3 Passi

### 1. Installazione

```bash
composer require --dev phpstan/phpstan
composer require --dev larastan/larastan
composer require --dev phpmd/phpmd
composer require --dev nunomaduro/phpinsights

composer require webmozart/assert
composer require thecodingmachine/safe
```

### 2. Configurazione

```neon
# phpstan.neon
includes:
    - vendor/larastan/larastan/extension.neon
    - vendor/thecodingmachine/phpstan-safe-rule/phpstan-safe-rule.neon

parameters:
    level: 10
    paths:
        - app
```

### 3. Esecuzione

```bash
# Generate PHPDoc
php artisan ide-helper:models -W

# Run checks
./vendor/bin/phpstan analyze --level=10
./vendor/bin/phpmd app text phpmd.ruleset.xml
php artisan insights
```

## 🧠 La Filosofia Finale

> "Type safety non è una scelta, è una responsabilità.
> Assertions non sono paranoia, sono professionalità.
> Exceptions non sono errori, sono comunicazione.
> Metrics non sono numeri, sono obiettivi.
> Quality non è un costo, è un investimento."

**Il progetto perfetto ha**:
- ✅ PHPStan livello 10 - 0 errori
- ✅ Tutti i modelli con PHPDoc (IDE Helper)
- ✅ Tutte le function PHP core sostituite con Safe
- ✅ Tutti gli input validati con Assert
- ✅ PHPMD complexity < 10
- ✅ PHP Insights score > 90%

**E soprattutto**:
- ❤️ Code review veloci
- 🚀 Deploy sicuri
- 😊 Developer felici
- 🎯 Bug minimizzati

---

*"Nel codice perfetto, i tipi sono evidenti, gli errori sono impossibili, e la complessità è un ricordo del passato."*

**ZEN ACHIEVED** 🧘‍♂️

---

## quality

*Consolidated from: `quality.md`*

module: theme
topic: quality
canonical: ../../../Themes/docs/shared-components/quality-analysis-sumy.md
---

See canonical documentation: ../../../Themes/docs/shared-components/quality-analysis-sumy.md
---

**Consolidated by:** Phase 2f intelligent merging
**Date:** 2026-08-04
