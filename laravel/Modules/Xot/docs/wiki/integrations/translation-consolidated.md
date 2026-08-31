---
title: "translation — Consolidated Documentation"
module: xot
type: integration
tags: [integrations, modules, xot]
created: 2026-08-24
updated: 2026-08-24
---

# translation — Consolidated Documentation

Consolidated from **15** individual files.

## Table of Contents

- [Laraxot Translation Philosophy](#translation-philosophy)
- [Regole per i file di traduzione in Laraxot PTVX](#translation-rules-regole-per-i-file-di-traduzione-in-larax)
- [Regole per i file di traduzione in Laraxot PTVX](#translation-rules)
- [](#translation-standardization-rules)
- [Struttura Traduzioni Espansa - Modulo Xot](#translation-structure-expanded)
- [---](#translation-structure)
- [Sistema di Traduzione](#translation-system-sistema-di-traduzione)
- [](#translation-system-standardization)
- [Sistema di Traduzione](#translation-system)
- [---](#translation)
- [Sistema di Traduzione ](#translation_system)
- [Traduzioni: Best Practices in Laraxot](#translations-best-practices)
- [Translations - Documentazione Consolidata DRY + KISS](#translations-consolidated)
- [---](#translations-navigation)
- [### Versione HEAD](#translations)

---

## translation-philosophy

*Consolidated from: `translation-philosophy.md`*


## Overview

This document outlines the Laraxot philosophy for handling translations in a consistent, maintainable, and scalable way across all modules.

## Core Principles

### 1. **No Hardcoded Text**
- **NEVER** use hardcoded labels, placeholders, or tooltips in Filament components
- **ALWAYS** use translation keys that resolve through the `LangServiceProvider`
- Translation keys are automatically resolved based on field name and module context

### 2. **Translation File Structure**
```
Modules/{ModuleName}/lang/{locale}/
├── {resource}.php
├── {model}.php
└── {component}.php
```

### 3. **Navigation Translation Standards**

#### Navigation Structure
Every navigation section should have complete translation data:

```php
'navigation' => [
    'name' => 'Proper Italian Name',           // Singular name
    'plural' => 'Proper Italian Plural Name',  // Plural name
    'group' => [
        'name' => 'Module Group Name',         // Navigation group
        'description' => 'Group description',  // Optional description
    ],
    'label' => 'Navigation Label',             // Display label
    'sort' => 85,                              // Sort order
    'icon' => 'heroicon-o-chart-bar',          // Actual icon identifier
],
```

#### Critical Rule: No `.navigation` Placeholders
**🚨 CRITICAL**: The string `.navigation` anywhere in a translation value indicates an **incomplete/placeholder translation** that MUST be fixed immediately.

**How to fix `.navigation` translations:**
1. **Read other module translation files** to understand the correct pattern
2. **navigation.label** should be a proper translation, not a placeholder
3. **navigation.icon** should reference an actual icon identifier, not contain `.navigation`
4. **navigation.group.name** should describe the navigation group properly

### 4. **Multi-Language Support**
- All modules must support at minimum: **Italian (it)**, **English (en)**, **German (de)**
- Translation files should be complete across all supported languages
- Use consistent terminology across languages

## Implementation Guidelines

### Filament Components
```php
// ❌ WRONG - Hardcoded text
TextInput::make('name')
    ->label('Full Name')
    ->placeholder('Enter your name')
    ->tooltip('This is required');

// ✅ CORRECT - Uses translation files
TextInput::make('name');
// Translation keys auto-resolved from:
// - resources/lang/{locale}/{module}::{field}.label
// - resources/lang/{locale}/{module}::{field}.placeholder
// - resources/lang/{locale}/{module}::{field}.tooltip
```

### Navigation Examples

#### Good Example (Activity/stored_event.php)
```php
'navigation' => [
    'name' => 'Eventi Archiviati',
    'plural' => 'Eventi Archiviati',
    'group' => [
        'name' => 'Monitoraggio',
        'description' => 'Gestione degli eventi di sistema archiviati',
    ],
    'label' => 'Eventi Archiviati',
    'sort' => 62,
    'icon' => 'activity-stored-event-animated',
],
```

#### Bad Example (Contains `.navigation` placeholders)
```php
'navigation' => [
    'label' => 'question chart.navigation',  // BAD - placeholder
    'icon' => 'survey pdf.navigation',        // BAD - placeholder
],
```

## Quality Assurance

### Commands for Checking Translations
```bash
# Find all translations with '.navigation' placeholders
grep -r "\.navigation" Modules/*/lang/**/*.php

# Check for missing translation files
find Modules -name "*.php" -path "*/lang/it/*" | sed 's|.*/lang/it/||' | sort
find Modules -name "*.php" -path "*/lang/en/*" | sed 's|.*/lang/en/||' | sort
find Modules -name "*.php" -path "*/lang/de/*" | sed 's|.*/lang/de/||' | sort

# Verify navigation structure consistency
grep -A 10 "'navigation' =>" Modules/*/lang/it/*.php | grep -E "(label|group|icon|sort)"
```

### Audit Gennaio 2026
**Data**: 2026-01-22
**Modulo**: User
**File corretti**: 11 file con traduzioni `.navigation` sistemate
**Documentazione**: [User/docs/navigation-translations-fixes-january-2026.md](../../User/docs/navigation-translations-fixes-january-2026.md)

### Automated Fixes
When you find `.navigation` placeholders:
1. **Study existing patterns** from well-structured modules (Activity, Geo, etc.)
2. **Use proper Italian translations** for labels and descriptions
3. **Reference actual icon identifiers** from Heroicons or custom icons
4. **Maintain consistent sort order** across the navigation

## Common Patterns

### Module Group Names
- **Geo**: Geographic data management
- **Survey**: Survey management and reporting
- **Monitoraggio**: System monitoring and tracking
- **LimeSurvey**: External system integration

### Icon Standards
- Use `heroicon-o-*` for standard icons
- Use module-specific custom icons when needed
- Never use placeholder strings as icon identifiers

## Maintenance

### Regular Audits
- Monthly audit of all translation files
- Check for `.navigation` placeholders
- Verify consistency across all languages
- Update documentation as patterns evolve

### New Module Setup
When creating a new module:
1. Create complete translation files for all supported languages
2. Follow established navigation patterns
3. Use proper translations from the start
4. Never commit placeholder translations

---

**Maintained by**: Xot Module (Core Laraxot Engine)
**Last Updated**: 2025-11-17

---

## translation-rules-regole-per-i-file-di-traduzione-in-larax

*Consolidated from: `translation-rules-regole-per-i-file-di-traduzione-in-larax.md`*


## Struttura dei file di traduzione

Ogni modulo deve avere i propri file di traduzione nella directory `Modules/<NomeModulo>/lang/<lingua>/`. La struttura standard di questi file deve seguire il seguente schema:

```php
<?php

return [
    'navigation' => [
        'group' => [
            'label' => 'Nome Gruppo',
        ],
        'resource' => [
            'label' => 'Nome Risorsa',
            'plural' => 'Nome Risorse',
        ],
    ],
    'page' => [
        'title' => 'Titolo Pagina',
        'description' => 'Descrizione Pagina',
    ],
    'fields' => [
        'nome_campo' => [
            'label' => 'Etichetta Campo',
            'placeholder' => 'Placeholder Campo',
            'tooltip' => 'Tooltip Campo',
            'help' => 'Testo di aiuto',
        ],
    ],
    'actions' => [
        'nome_azione' => [
            'label' => 'Etichetta Azione',
            'tooltip' => 'Tooltip Azione',
            'success' => 'Messaggio di successo',
            'error' => 'Messaggio di errore',
        ],
    ],
    'validation' => [
        'required' => 'Il campo :attribute è obbligatorio',
        'email' => 'Il campo :attribute deve essere un indirizzo email valido',
    ],
    'messages' => [
        'success' => 'Operazione completata con successo',
        'error' => 'Si è verificato un errore',
    ],
];
```

## Sintassi

1. **Utilizzare sintassi array breve**:
   ```php
   // CORRETTO
   return [
       'key' => 'value',
   ];

   // ERRATO
   return array(
       'key' => 'value',
   );
   ```

2. **Dichiarazione strict types**:
   ```php
   <?php

   declare(strict_types=1);

   return [
       'key' => 'value',
   ];
   ```

3. **Indentazione e formattazione coerente**:
   - Utilizzare 4 spazi per l'indentazione
   - Mantenere coerenza tra virgole e parentesi
   - Chiudere sempre correttamente gli array annidati

## Regole di naming

1. **Chiavi in snake_case**:
   ```php
   'nome_campo' => [
       'label' => 'Etichetta Campo',
   ],
   ```

2. **Prefissi per icone**:
   - Utilizzare il prefisso del modulo per le icone personalizzate
   - Esempio: `'icl-upload-animated'` per un'icona del modulo IndennitaCondizioniLavoro

3. **Evitare chiavi generiche**:
   - Usare nomi specifici e descrittivi
   - Evitare nomi come `button1`, `action2`, ecc.

## Pattern comuni

1. **Riferimento alle traduzioni nel codice**:
   ```php
   // CORRETTO
   ->label(__('modulo::risorsa.fields.nome_campo.label'))

   // ERRATO
   ->label('Etichetta hardcoded')
   ```

2. **Registrazione di icone SVG**:
   ```php
   // Nel ServiceProvider
   Blade::component('modulo::components.icons.nome-icona', 'modulo-nome-icona');

   // Nel file di traduzione
   'icona' => 'modulo-nome-icona',
   ```

## Errori comuni da evitare

1. **Parentesi mancanti in array annidati**
2. **Virgole mancanti tra elementi dell'array**
3. **Etichette non tradotte (stesse chiavi come valori)**
4. **Mescolanza di stili array (`array()` e `[]`)**
5. **Riferimenti a traduzioni inesistenti**
6. **Mancanza di `declare(strict_types=1);`**
7. **Campi `helper_text` vuoti o duplicati**
8. **Conflitti di merge non risolti**

## Manutenzione dei file di traduzione

1. **Controllo sintassi prima del commit**:
   ```bash
   php -l Modules/<NomeModulo>/lang/<lingua>/<file>.php
   ```

2. **Aggiornare le traduzioni quando si aggiungono funzionalità**
3. **Mantenere coerenza tra le diverse lingue**
4. **Rimuovere traduzioni obsolete**

## Link alla documentazione correlata

- [Errori comuni nei file di traduzione](/laravel/Modules/Lang/docs/errori_comuni_traduzione.md)
- [Convenzioni di documentazione](/laravel/Modules/Xot/docs/documentation_conventions.md)
- [Documentazione principale sulle traduzioni](/docs/translation_rules.md)

*Ultimo aggiornamento: 3 Giugno 2025*

---

## translation-rules

*Consolidated from: `translation-rules.md`*


## Struttura dei file di traduzione

Ogni modulo deve avere i propri file di traduzione nella directory `Modules/<NomeModulo>/lang/<lingua>/`. La struttura standard di questi file deve seguire il seguente schema:

```php
<?php

return [
    'navigation' => [
        'group' => [
            'label' => 'Nome Gruppo',
        ],
        'resource' => [
            'label' => 'Nome Risorsa',
            'plural' => 'Nome Risorse',
        ],
    ],
    'page' => [
        'title' => 'Titolo Pagina',
        'description' => 'Descrizione Pagina',
    ],
    'fields' => [
        'nome_campo' => [
            'label' => 'Etichetta Campo',
            'placeholder' => 'Placeholder Campo',
            'tooltip' => 'Tooltip Campo',
            'help' => 'Testo di aiuto',
        ],
    ],
    'actions' => [
        'nome_azione' => [
            'label' => 'Etichetta Azione',
            'tooltip' => 'Tooltip Azione',
            'success' => 'Messaggio di successo',
            'error' => 'Messaggio di errore',
        ],
    ],
    'validation' => [
        'required' => 'Il campo :attribute è obbligatorio',
        'email' => 'Il campo :attribute deve essere un indirizzo email valido',
    ],
    'messages' => [
        'success' => 'Operazione completata con successo',
        'error' => 'Si è verificato un errore',
    ],
];
```

## Sintassi

1. **Utilizzare sintassi array breve**:
   ```php
   // CORRETTO
   return [
       'key' => 'value',
   ];

   // ERRATO
   return array(
       'key' => 'value',
   );
   ```

2. **Dichiarazione strict types**:
   ```php
   <?php

   declare(strict_types=1);

   return [
       'key' => 'value',
   ];
   ```

3. **Indentazione e formattazione coerente**:
   - Utilizzare 4 spazi per l'indentazione
   - Mantenere coerenza tra virgole e parentesi
   - Chiudere sempre correttamente gli array annidati

## Regole di naming

1. **Chiavi in snake_case**:
   ```php
   'nome_campo' => [
       'label' => 'Etichetta Campo',
   ],
   ```

2. **Prefissi per icone**:
   - Utilizzare il prefisso del modulo per le icone personalizzate
   - Esempio: `'icl-upload-animated'` per un'icona del modulo IndennitaCondizioniLavoro

3. **Evitare chiavi generiche**:
   - Usare nomi specifici e descrittivi
   - Evitare nomi come `button1`, `action2`, ecc.

## Pattern comuni

1. **Riferimento alle traduzioni nel codice**:
   ```php
   // CORRETTO
   ->label(__('modulo::risorsa.fields.nome_campo.label'))

   // ERRATO
   ->label('Etichetta hardcoded')
   ```

2. **Registrazione di icone SVG**:
   ```php
   // Nel ServiceProvider
   Blade::component('modulo::components.icons.nome-icona', 'modulo-nome-icona');

   // Nel file di traduzione
   'icona' => 'modulo-nome-icona',
   ```

## Errori comuni da evitare

1. **Parentesi mancanti in array annidati**
2. **Virgole mancanti tra elementi dell'array**
3. **Etichette non tradotte (stesse chiavi come valori)**
4. **Mescolanza di stili array (`array()` e `[]`)**
5. **Riferimenti a traduzioni inesistenti**
6. **Mancanza di `declare(strict_types=1);`**
7. **Campi `helper_text` vuoti o duplicati**
8. **Conflitti di merge non risolti**

## Manutenzione dei file di traduzione

1. **Controllo sintassi prima del commit**:
   ```bash
   php -l Modules/<NomeModulo>/lang/<lingua>/<file>.php
   ```

2. **Aggiornare le traduzioni quando si aggiungono funzionalità**
3. **Mantenere coerenza tra le diverse lingue**
4. **Rimuovere traduzioni obsolete**

## Link alla documentazione correlata

- [Errori comuni nei file di traduzione](/laravel/Modules/Lang/project_docs/errori_comuni_traduzione.md)
- [Convenzioni di documentazione](/laravel/Modules/Xot/project_docs/documentation_conventions.md)
- [Documentazione principale sulle traduzioni](/project_docs/translation_rules.md)

*Ultimo aggiornamento: 3 Giugno 2025*

---

## translation-standardization-rules

*Consolidated from: `translation-standardization-rules.md`*


---

## translation-structure-expanded

*Consolidated from: `translation-structure-expanded.md`*


## Scopo
Implementazione della struttura espansa per le traduzioni del modulo Xot, seguendo i principi DRY/KISS e le regole del progetto <nome progetto>.

## Problema Identificato
Il file di traduzione spagnolo `/lang/es/labels.php` contiene alcune strutture che potrebbero beneficiare della struttura espansa, specialmente per campi geografici come "province".

### File Analizzato
- `/lang/es/labels.php` - File di etichette generali in spagnolo

## Struttura Espansa per Campi Geografici

### Campo "province" - Implementazione Multilingua

#### Italiano (Riferimento)
```php
'province' => [
    'label' => 'Provincia',
    'tooltip' => 'Seleziona la provincia',
    'helper_text' => 'Divisione amministrativa in cui si trova la città',
    'description' => 'Provincia, stato o regione amministrativa',
    'icon' => 'heroicon-o-map',
    'color' => 'secondary',
    'placeholder' => 'es. Milano, Roma, Napoli',
    'validation' => [
        'required' => 'La provincia è obbligatoria',
        'invalid' => 'Nome provincia non valido',
    ],
],
```

#### Inglese
```php
'province' => [
    'label' => 'Province',
    'tooltip' => 'Select the province or state',
    'helper_text' => 'Administrative division where the city is located',
    'description' => 'Province, state, or administrative region',
    'icon' => 'heroicon-o-map',
    'color' => 'secondary',
    'placeholder' => 'e.g. California, Ontario, Bavaria',
    'validation' => [
        'required' => 'Province is required',
        'invalid' => 'Invalid province name',
    ],
],
```

#### Tedesco
```php
'province' => [
    'label' => 'Bundesland',
    'tooltip' => 'Wählen Sie das Bundesland oder den Staat aus',
    'helper_text' => 'Verwaltungseinheit, in der sich die Stadt befindet',
    'description' => 'Bundesland, Staat oder Verwaltungsregion',
    'icon' => 'heroicon-o-map',
    'color' => 'secondary',
    'placeholder' => 'z.B. Bayern, Nordrhein-Westfalen, Berlin',
    'validation' => [
        'required' => 'Bundesland ist erforderlich',
        'invalid' => 'Ungültiger Bundeslandname',
    ],
],
```

#### Spagnolo (Corretto)
```php
'province' => [
    'label' => 'Provincia',
    'tooltip' => 'Selecciona la provincia o comunidad autónoma',
    'helper_text' => 'División administrativa donde se encuentra la ciudad',
    'description' => 'Provincia, comunidad autónoma o región administrativa',
    'icon' => 'heroicon-o-map',
    'color' => 'secondary',
    'placeholder' => 'ej. Madrid, Cataluña, Andalucía',
    'validation' => [
        'required' => 'La provincia es obligatoria',
        'invalid' => 'Nombre de provincia no válido',
    ],
],
```

## Terminologia Geografica Spagnola

### Divisioni Amministrative
| Termine | Spagnolo | Contesto |
|---------|----------|----------|
| Provincia | Provincia | Divisione amministrativa standard |
| Comunidad Autónoma | Comunidad Autónoma | Regione autonoma spagnola |
| Estado | Estado | Stato (per paesi federali) |
| Región | Región | Regione geografica |
| Territorio | Territorio | Territorio (es. Canada) |

### Esempi Specifici
- **Spagna**: Madrid, Cataluña, Andalucía, País Vasco
- **Messico**: Jalisco, Nuevo León, Yucatán
- **Argentina**: Buenos Aires, Córdoba, Santa Fe
- **Colombia**: Antioquia, Cundinamarca, Valle del Cauca

## Implementazione nel Modulo Xot

### File Corrente: `lang/es/labels.php`
Il file attuale contiene principalmente etichette generali per l'interfaccia amministrativa. La struttura espansa può essere implementata per:

1. **Campi geografici** nelle sezioni di configurazione
2. **Etichette di form** per registrazione utenti
3. **Terminologia amministrativa** per gestione territori

### Sezioni da Espandere
```php
'geography' => [
    'province' => [
        'label' => 'Provincia',
        'tooltip' => 'Selecciona la provincia o comunidad autónoma',
        'helper_text' => 'División administrativa donde se encuentra la ciudad',
        'description' => 'Provincia, comunidad autónoma o región administrativa',
        'icon' => 'heroicon-o-map',
        'color' => 'secondary',
        'placeholder' => 'ej. Madrid, Cataluña, Andalucía',
    ],
    'territories' => [
        'canada' => [
            'label' => 'Provincias y Territorios de Canadá',
            'tooltip' => 'Lista completa de divisiones administrativas canadienses',
            'helper_text' => 'Incluye provincias y territorios del sistema federal canadiense',
            'description' => 'Listado completo de las provincias y territorios de Canadá',
            'icon' => 'heroicon-o-globe-americas',
            'color' => 'info',
        ],
    ],
],
```

## Principi DRY/KISS Applicati

### DRY (Don't Repeat Yourself)
- **Template standardizzato** per tutti i campi geografici
- **Terminologia coerente** tra moduli correlati
- **Struttura riutilizzabile** per nuove lingue

### KISS (Keep It Simple, Stupid)
- **Naming intuitivo** per sviluppatori spagnoli
- **Esempi pratici** per ogni campo
- **Documentazione chiara** per ogni elemento

## Benefici per il Modulo Xot

### Amministrazione Sistema
- **Interfaccia multilingua** completa
- **Terminologia precisa** per configurazioni
- **Accessibilità migliorata** con tooltip e descrizioni

### Sviluppatori
- **Template riutilizzabili** per nuove funzionalità
- **Documentazione completa** per ogni campo
- **Manutenzione semplificata** con struttura standard

## Collegamenti Bidirezionali

### Documentazione Root
- [Struttura Traduzioni Espansa](/docs/translation-structure-expanded.md)
- [Principi DRY/KISS](/docs/dry-kiss-principles.md)

### Documentazione Moduli Correlati
- [Geo Module Translations](/Modules/Geo/docs/translation-structure-expanded.md)
- [User Module Translations](/Modules/User/docs/translation-guidelines.md)
- [Struttura Traduzioni Espansa](/project_docs/translation-structure-expanded.md)
- [Principi DRY/KISS](/project_docs/dry-kiss-principles.md)

### Documentazione Moduli Correlati
- [Geo Module Translations](/Modules/Geo/project_docs/translation-structure-expanded.md)
- [User Module Translations](/Modules/User/project_docs/translation-guidelines.md)

### File di Implementazione
- `lang/es/labels.php` - Etichette generali spagnole
- `lang/it/labels.php` - Template italiano (riferimento)
- `lang/en/labels.php` - Template inglese

## Roadmap Implementazione

### Fase 1: Analisi Completata ✅
- [x] Documentazione struttura espansa
- [x] Analisi file spagnolo esistente
- [x] Identificazione aree di miglioramento

### Fase 2: Implementazione Struttura Espansa
- [ ] Aggiunta sezione geografia con struttura espansa
- [ ] Implementazione tooltip, helper_text, description
- [ ] Standardizzazione icone e colori

### Fase 3: Validazione e Test
- [ ] Test funzionalità con nuova struttura
- [ ] Controllo coerenza terminologica
- [ ] Validazione accessibilità

---

**Stato**: Documentazione completata, implementazione in corso
**Priorità**: Media (file già corretto linguisticamente)
**Responsabile**: Sistema automatico DRY/KISS
**Data**: 2025-08-08

---

## translation-structure

*Consolidated from: `translation-structure.md`*

module: theme
topic: translation-structure
canonical: ../../../Themes/docs/shared-components/TRANSLATION_STRUCTURE.md
---

See canonical documentation: ../../../Themes/docs/shared-components/TRANSLATION_STRUCTURE.md

---

## translation-system-sistema-di-traduzione

*Consolidated from: `translation-system-sistema-di-traduzione.md`*


## Regola Fondamentale: NO ->label()

La regola più importante del sistema di traduzione è:

**MAI utilizzare il metodo `->label()` nei componenti Filament.**

### Perché Non Usare ->label()?

1. **Bypass del Sistema di Traduzione**
   - `->label()` bypassa il sistema di traduzione automatico
   - Rende impossibile la gestione centralizzata delle traduzioni
   - Crea inconsistenze nell'interfaccia utente

2. **Violazione del Single Responsibility Principle**
   - Le etichette dovrebbero essere gestite dal sistema di traduzione
   - I componenti dovrebbero occuparsi solo della struttura
   - La traduzione è una responsabilità separata

3. **Problemi di Manutenibilità**
   - Etichette hardcoded sono difficili da modificare
   - Impossibile cambiare lingua dinamicamente
   - Duplicazione di testo in più punti

## Come Funziona il Sistema di Traduzione

### 1. LangServiceProvider

Il `LangServiceProvider` gestisce automaticamente le traduzioni:

```php
namespace Modules\Lang\Providers;

class LangServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Intercetta la creazione dei componenti Filament
        // Applica automaticamente le traduzioni
        // Gestisce il fallback delle lingue
    }
}
```

### 2. Struttura dei File di Traduzione

```php
// Modules/ModuleName/lang/it/resource.php
return [
    'fields' => [
        'name' => [
            'label' => 'Nome',
            'placeholder' => 'Inserisci il nome',
            'helper_text' => 'Nome completo',
        ],
    ],
];
```

### 3. Convenzioni di Naming

Le chiavi di traduzione vengono generate automaticamente seguendo questa struttura:
- `module_name::resource_name.fields.field_name.label`
- `module_name::resource_name.fields.field_name.placeholder`
- `module_name::resource_name.fields.field_name.helper_text`

## Implementazione Corretta

### ❌ ERRATO: Uso di ->label()

```php
// NON FARE QUESTO
TextColumn::make('name')
    ->label('Nome')
    ->searchable();

Select::make('status')
    ->label('Stato')
    ->options([...]);
```

### ✅ CORRETTO: Senza ->label()

```php
// FARE QUESTO
TextColumn::make('name')
    ->searchable();

Select::make('status')
    ->options([...]);
```

## File di Traduzione

### 1. Struttura Base

```php
// Modules/User/lang/it/users.php
return [
    'fields' => [
        'name' => [
            'label' => 'Nome',
            'placeholder' => 'Inserisci il nome',
            'helper_text' => 'Nome completo dell\'utente',
        ],
        'email' => [
            'label' => 'Email',
            'placeholder' => 'Inserisci l\'email',
            'helper_text' => 'Indirizzo email valido',
        ],
    ],
    'actions' => [
        'create' => [
            'label' => 'Nuovo',
            'tooltip' => 'Crea nuovo record',
        ],
    ],
    'messages' => [
        'success' => [
            'created' => 'Record creato con successo',
        ],
    ],
];
```

### 2. Organizzazione dei File

```
Modules/
  ModuleName/
    lang/
      it/
        resource.php
        forms.php
        messages.php
      en/
        resource.php
        forms.php
        messages.php
```

## Migrazione da ->label()

### 1. Identificare i File da Correggere

```bash
# Trova tutti i file PHP che usano ->label()
grep -r "->label(" . --include="*.php"
```

### 2. Creare i File di Traduzione

```php
// Prima di rimuovere ->label(), creare il file di traduzione
return [
    'fields' => [
        'field_name' => [
            'label' => 'Etichetta Precedente',
        ],
    ],
];
```

### 3. Rimuovere ->label()

```php
// Prima
TextColumn::make('name')
    ->label('Nome')
    ->searchable();

// Dopo
TextColumn::make('name')
    ->searchable();
```

## Best Practices

1. **Mai Usare ->label()**
   - Nessuna eccezione a questa regola
   - Usare sempre i file di traduzione

2. **Struttura Coerente**
   - Mantenere la stessa struttura in tutti i moduli
   - Seguire le convenzioni di naming

3. **File di Traduzione Completi**
   - Includere tutte le stringhe necessarie
   - Aggiungere traduzioni per tutte le lingue supportate

4. **Documentazione**
   - Commentare i file di traduzione
   - Mantenere un README aggiornato

## Troubleshooting

### 1. Etichetta Non Appare

**Problema**: L'etichetta non viene visualizzata

**Soluzione**:
1. Verificare il path del file di traduzione
2. Controllare la struttura delle chiavi
3. Pulire la cache delle traduzioni

```bash
php artisan cache:clear
php artisan view:clear
```

### 2. Etichetta Sbagliata

**Problema**: Viene mostrata l'etichetta sbagliata

**Soluzione**:
1. Verificare la chiave di traduzione generata
2. Controllare il fallback della lingua
3. Verificare la priorità delle traduzioni

## Riferimenti

- [Documentazione Laravel Translations](https://laravel.com/docs/localization)
- [Filament Form Components](https://filamentphp.com/docs/forms)
- [Best Practices Filament](../docs/filament-best-practices.md)
- [Schema Conventions](../docs/schema-conventions.md)

---

## translation-system-standardization

*Consolidated from: `translation-system-standardization.md`*


---

## translation-system

*Consolidated from: `translation-system.md`*


## Regola Fondamentale: NO ->label()

La regola più importante del sistema di traduzione è:

**MAI utilizzare il metodo `->label()` nei componenti Filament.**

### Perché Non Usare ->label()?

1. **Bypass del Sistema di Traduzione**
   - `->label()` bypassa il sistema di traduzione automatico
   - Rende impossibile la gestione centralizzata delle traduzioni
   - Crea inconsistenze nell'interfaccia utente

2. **Violazione del Single Responsibility Principle**
   - Le etichette dovrebbero essere gestite dal sistema di traduzione
   - I componenti dovrebbero occuparsi solo della struttura
   - La traduzione è una responsabilità separata

3. **Problemi di Manutenibilità**
   - Etichette hardcoded sono difficili da modificare
   - Impossibile cambiare lingua dinamicamente
   - Duplicazione di testo in più punti

## Come Funziona il Sistema di Traduzione

### 1. LangServiceProvider

Il `LangServiceProvider` gestisce automaticamente le traduzioni:

```php
namespace Modules\Lang\Providers;

class LangServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Intercetta la creazione dei componenti Filament
        // Applica automaticamente le traduzioni
        // Gestisce il fallback delle lingue
    }
}
```

### 2. Struttura dei File di Traduzione

```php
// Modules/ModuleName/lang/it/resource.php
return [
    'fields' => [
        'name' => [
            'label' => 'Nome',
            'placeholder' => 'Inserisci il nome',
            'helper_text' => 'Nome completo',
        ],
    ],
];
```

### 3. Convenzioni di Naming

Le chiavi di traduzione vengono generate automaticamente seguendo questa struttura:
- `module_name::resource_name.fields.field_name.label`
- `module_name::resource_name.fields.field_name.placeholder`
- `module_name::resource_name.fields.field_name.helper_text`

## Implementazione Corretta

### ❌ ERRATO: Uso di ->label()

```php
// NON FARE QUESTO
TextColumn::make('name')
    ->label('Nome')
    ->searchable();

Select::make('status')
    ->label('Stato')
    ->options([...]);
```

### ✅ CORRETTO: Senza ->label()

```php
// FARE QUESTO
TextColumn::make('name')
    ->searchable();

Select::make('status')
    ->options([...]);
```

## File di Traduzione

### 1. Struttura Base

```php
// Modules/User/lang/it/users.php
return [
    'fields' => [
        'name' => [
            'label' => 'Nome',
            'placeholder' => 'Inserisci il nome',
            'helper_text' => 'Nome completo dell\'utente',
        ],
        'email' => [
            'label' => 'Email',
            'placeholder' => 'Inserisci l\'email',
            'helper_text' => 'Indirizzo email valido',
        ],
    ],
    'actions' => [
        'create' => [
            'label' => 'Nuovo',
            'tooltip' => 'Crea nuovo record',
        ],
    ],
    'messages' => [
        'success' => [
            'created' => 'Record creato con successo',
        ],
    ],
];
```

### 2. Organizzazione dei File

```
Modules/
  ModuleName/
    lang/
      it/
        resource.php
        forms.php
        messages.php
      en/
        resource.php
        forms.php
        messages.php
```

## Migrazione da ->label()

### 1. Identificare i File da Correggere

```bash
# Trova tutti i file PHP che usano ->label()
grep -r "->label(" . --include="*.php"
```

### 2. Creare i File di Traduzione

```php
// Prima di rimuovere ->label(), creare il file di traduzione
return [
    'fields' => [
        'field_name' => [
            'label' => 'Etichetta Precedente',
        ],
    ],
];
```

### 3. Rimuovere ->label()

```php
// Prima
TextColumn::make('name')
    ->label('Nome')
    ->searchable();

// Dopo
TextColumn::make('name')
    ->searchable();
```

## Best Practices

1. **Mai Usare ->label()**
   - Nessuna eccezione a questa regola
   - Usare sempre i file di traduzione

2. **Struttura Coerente**
   - Mantenere la stessa struttura in tutti i moduli
   - Seguire le convenzioni di naming

3. **File di Traduzione Completi**
   - Includere tutte le stringhe necessarie
   - Aggiungere traduzioni per tutte le lingue supportate

4. **Documentazione**
   - Commentare i file di traduzione
   - Mantenere un README aggiornato

## Troubleshooting

### 1. Etichetta Non Appare

**Problema**: L'etichetta non viene visualizzata

**Soluzione**:
1. Verificare il path del file di traduzione
2. Controllare la struttura delle chiavi
3. Pulire la cache delle traduzioni

```bash
php artisan cache:clear
php artisan view:clear
```

### 2. Etichetta Sbagliata

**Problema**: Viene mostrata l'etichetta sbagliata

**Soluzione**:
1. Verificare la chiave di traduzione generata
2. Controllare il fallback della lingua
3. Verificare la priorità delle traduzioni

## Riferimenti

- [Documentazione Laravel Translations](https://laravel.com/project_docs/localization)
- [Filament Form Components](https://filamentphp.com/project_docs/forms)
- [Best Practices Filament](../project_docs/filament-best-practices.md)
- [Schema Conventions](../project_docs/schema-conventions.md)

---

## translation

*Consolidated from: `translation.md`*

module: theme
topic: translation
canonical: ../../../Themes/docs/shared-components/translation-philosophy.md
---

See canonical documentation: ../../../Themes/docs/shared-components/translation-philosophy.md
---

## translation_system

*Consolidated from: `translation_system.md`*


## Regola Fondamentale: NO ->label()

La regola più importante del sistema di traduzione è:

**MAI utilizzare il metodo `->label()` nei componenti Filament.**

### Perché Non Usare ->label()?

1. **Bypass del Sistema di Traduzione**
   - `->label()` bypassa il sistema di traduzione automatico
   - Rende impossibile la gestione centralizzata delle traduzioni
   - Crea inconsistenze nell'interfaccia utente

2. **Violazione del Single Responsibility Principle**
   - Le etichette dovrebbero essere gestite dal sistema di traduzione
   - I componenti dovrebbero occuparsi solo della struttura
   - La traduzione è una responsabilità separata

3. **Problemi di Manutenibilità**
   - Etichette hardcoded sono difficili da modificare
   - Impossibile cambiare lingua dinamicamente
   - Duplicazione di testo in più punti

## Come Funziona il Sistema di Traduzione

### 1. LangServiceProvider

Il `LangServiceProvider` gestisce automaticamente le traduzioni:

```php
namespace Modules\Lang\Providers;

class LangServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Intercetta la creazione dei componenti Filament
        // Applica automaticamente le traduzioni
        // Gestisce il fallback delle lingue
    }
}
```

### 2. Struttura dei File di Traduzione

```php
// Modules/ModuleName/lang/it/resource.php
return [
    'fields' => [
        'name' => [
            'label' => 'Nome',
            'placeholder' => 'Inserisci il nome',
            'helper_text' => 'Nome completo',
        ],
    ],
];
```

### 3. Convenzioni di Naming

Le chiavi di traduzione vengono generate automaticamente seguendo questa struttura:
- `module_name::resource_name.fields.field_name.label`
- `module_name::resource_name.fields.field_name.placeholder`
- `module_name::resource_name.fields.field_name.helper_text`

## Implementazione Corretta

### ❌ ERRATO: Uso di ->label()

```php
// NON FARE QUESTO
TextColumn::make('name')
    ->label('Nome')
    ->searchable();

Select::make('status')
    ->label('Stato')
    ->options([...]);
```

### ✅ CORRETTO: Senza ->label()

```php
// FARE QUESTO
TextColumn::make('name')
    ->searchable();

Select::make('status')
    ->options([...]);
```

## File di Traduzione

### 1. Struttura Base

```php
// Modules/User/lang/it/users.php
return [
    'fields' => [
        'name' => [
            'label' => 'Nome',
            'placeholder' => 'Inserisci il nome',
            'helper_text' => 'Nome completo dell\'utente',
        ],
        'email' => [
            'label' => 'Email',
            'placeholder' => 'Inserisci l\'email',
            'helper_text' => 'Indirizzo email valido',
        ],
    ],
    'actions' => [
        'create' => [
            'label' => 'Nuovo',
            'tooltip' => 'Crea nuovo record',
        ],
    ],
    'messages' => [
        'success' => [
            'created' => 'Record creato con successo',
        ],
    ],
];
```

### 2. Organizzazione dei File

```
Modules/
  ModuleName/
    lang/
      it/
        resource.php
        forms.php
        messages.php
      en/
        resource.php
        forms.php
        messages.php
```

## Migrazione da ->label()

### 1. Identificare i File da Correggere

```bash
# Trova tutti i file PHP che usano ->label()
grep -r "->label(" . --include="*.php"
```

### 2. Creare i File di Traduzione

```php
// Prima di rimuovere ->label(), creare il file di traduzione
return [
    'fields' => [
        'field_name' => [
            'label' => 'Etichetta Precedente',
        ],
    ],
];
```

### 3. Rimuovere ->label()

```php
// Prima
TextColumn::make('name')
    ->label('Nome')
    ->searchable();

// Dopo
TextColumn::make('name')
    ->searchable();
```

## Best Practices

1. **Mai Usare ->label()**
   - Nessuna eccezione a questa regola
   - Usare sempre i file di traduzione

2. **Struttura Coerente**
   - Mantenere la stessa struttura in tutti i moduli
   - Seguire le convenzioni di naming

3. **File di Traduzione Completi**
   - Includere tutte le stringhe necessarie
   - Aggiungere traduzioni per tutte le lingue supportate

4. **Documentazione**
   - Commentare i file di traduzione
   - Mantenere un README aggiornato

## Troubleshooting

### 1. Etichetta Non Appare

**Problema**: L'etichetta non viene visualizzata

**Soluzione**:
1. Verificare il path del file di traduzione
2. Controllare la struttura delle chiavi
3. Pulire la cache delle traduzioni

```bash
php artisan cache:clear
php artisan view:clear
```

### 2. Etichetta Sbagliata

**Problema**: Viene mostrata l'etichetta sbagliata

**Soluzione**:
1. Verificare la chiave di traduzione generata
2. Controllare il fallback della lingua
3. Verificare la priorità delle traduzioni

## Riferimenti

- [Documentazione Laravel Translations](https://laravel.com/project_docs/localization)
- [Filament Form Components](https://filamentphp.com/project_docs/forms)
- [Best Practices Filament](../project_docs/filament-best-practices.md)
- [Schema Conventions](../project_docs/schema-conventions.md) 
---

## translations-best-practices

*Consolidated from: `translations-best-practices.md`*


Questo documento definisce le linee guida ufficiali e le best practices per la gestione delle traduzioni all'interno del framework Laraxot.

## Principi Fondamentali

### 1. Struttura Espansa per i Campi

#### ✅ DO - Utilizzare la struttura espansa per i campi

```php
// resources/lang/it/resource.php
return [
    'fields' => [
        'nome_campo' => [
            'label' => 'Etichetta Campo',
            'tooltip' => 'Descrizione di aiuto per il campo',
            'placeholder' => 'Esempio di input'
        ],
        // Altri campi...
    ],
];
```

#### ❌ DON'T - Non utilizzare mai la struttura semplificata

```php
// NON FARE MAI QUESTO
return [
    'fields' => [
        'nome_campo' => 'Etichetta Campo',
    ],
];
```

### 2. Struttura Espansa per le Azioni

#### ✅ DO - Utilizzare la struttura espansa per le azioni

```php
// resources/lang/it/resource.php
return [
    'actions' => [
        'nome_azione' => [
            'label' => 'Etichetta Azione',
            'icon' => 'heroicon-name',
            'color' => 'primary|secondary|success|danger',
            'tooltip' => 'Descrizione dell\'azione'
        ],
        // Altre azioni...
    ],
];
```

#### ❌ DON'T - Non utilizzare mai la struttura semplificata

```php
// NON FARE MAI QUESTO
return [
    'actions' => [
        'nome_azione' => 'Etichetta Azione',
    ],
];
```

## Struttura Completa dei File di Traduzione

### Risorse Filament

Per le risorse Filament, utilizzare la seguente struttura:

```php
// resources/lang/it/socio-resource.php
return [
    // Metadati della risorsa
    'label' => 'Socio',
    'plural_label' => 'Soci',
    'navigation_group' => 'Anagrafiche',
    'navigation_icon' => 'heroicon-o-user',
    'navigation_sort' => 1,
    'description' => 'Gestione completa dei soci',

    // Campi del form e tabella
    'fields' => [
        'id_socio' => [
            'label' => 'ID Socio',
            'tooltip' => 'Identificativo univoco del socio'
        ],
        'cognome' => [
            'label' => 'Cognome',
            'tooltip' => 'Cognome del socio',
            'placeholder' => 'Inserisci il cognome'
        ],
        'nome' => [
            'label' => 'Nome',
            'tooltip' => 'Nome del socio',
            'placeholder' => 'Inserisci il nome'
        ],
        // Altri campi...
    ],

    // Azioni disponibili
    'actions' => [
        'create' => [
            'label' => 'Nuovo Socio',
            'icon' => 'heroicon-o-plus',
            'color' => 'primary',
            'tooltip' => 'Crea un nuovo profilo socio'
        ],
        'edit' => [
            'label' => 'Modifica',
            'icon' => 'heroicon-o-pencil',
            'color' => 'primary',
            'tooltip' => 'Modifica i dati del socio'
        ],
        // Altre azioni...
    ],

    // Sezioni del form
    'sections' => [
        'personal_data' => [
            'label' => 'Dati Personali',
            'tooltip' => 'Informazioni anagrafiche di base'
        ],
        'contact_info' => [
            'label' => 'Contatti',
            'tooltip' => 'Informazioni di contatto del socio'
        ],
        // Altre sezioni...
    ],

    // Messaggi di feedback
    'messages' => [
        'created' => 'Socio creato con successo',
        'updated' => 'Socio aggiornato con successo',
        'deleted' => 'Socio eliminato con successo'
    ],

    // Configurazione tabella
    'table' => [
        'empty_text' => 'Nessun socio trovato',
        'search_prompt' => 'Cerca soci...'
    ],

    // Testi per i filtri
    'filters' => [
        'is_active' => [
            'label' => 'Solo attivi',
            'description' => 'Mostra solo i soci attivi'
        ],
        'created_at' => [
            'label' => 'Data creazione',
            'description' => 'Filtra per data di creazione'
        ],
        // Altri filtri...
    ]
];
```

### Template di pagina

Per i template generici e le view, utilizzare la seguente struttura:

```php
// resources/lang/it/convenzioni.php
return [
    // Titoli e sottotitoli
    'title' => 'Gestione Convenzioni',
    'subtitle' => 'Elenco completo delle convenzioni attive',

    // Elementi UI
    'ui' => [
        'buttons' => [
            'create' => [
                'label' => 'Nuova Convenzione',
                'tooltip' => 'Crea una nuova convenzione'
            ],
            'import' => [
                'label' => 'Importa',
                'tooltip' => 'Importa convenzioni da file'
            ],
            // Altri bottoni...
        ],
        'tabs' => [
            'active' => [
                'label' => 'Attive',
                'tooltip' => 'Convenzioni attualmente attive'
            ],
            'expired' => [
                'label' => 'Scadute',
                'tooltip' => 'Convenzioni terminate'
            ],
            // Altri tab...
        ],
        // Altri elementi UI...
    ],

    // Messaggi di feedback
    'messages' => [
        'success' => [
            'created' => 'Convenzione creata con successo',
            'updated' => 'Convenzione aggiornata con successo',
            'deleted' => 'Convenzione eliminata con successo'
        ],
        'errors' => [
            'not_found' => 'Convenzione non trovata',
            'already_exists' => 'Una convenzione con questo nome esiste già',
            'delete_failed' => 'Impossibile eliminare la convenzione'
        ],
        // Altri messaggi...
    ],

    // Tooltip e aiuti
    'help' => [
        'discount' => 'Inserisci la percentuale di sconto senza il simbolo %',
        'expiry' => 'La data di scadenza deve essere futura',
        // Altri aiuti...
    ],

    // Testi email e notifiche
    'notifications' => [
        'new_convention' => [
            'subject' => 'Nuova convenzione disponibile',
            'body' => 'È stata aggiunta una nuova convenzione: :name',
            // Altri campi email...
        ],
        // Altre notifiche...
    ]
];
```

## Organizzazione dei File

### 1. Separazione per contesto

Organizzare i file di traduzione per contesto (risorse, pagine, componenti, etc.):

```
resources/lang/it/
├── auth.php               # Autenticazione
├── pagination.php         # Paginazione
├── passwords.php          # Password e reset
├── validation.php         # Messaggi di validazione
├── filament.php           # Traduzioni generiche di Filament
├── resources/             # Risorse Filament
│   ├── socio.php
│   ├── convenzione.php
│   └── ...
├── pages/                 # Pagine specifiche
│   ├── dashboard.php
│   ├── reports.php
│   └── ...
├── components/            # Componenti condivisi
│   ├── data-table.php
│   ├── file-upload.php
│   └── ...
├── emails/                # Template email
│   ├── welcome.php
│   ├── notification.php
│   └── ...
└── common.php             # Traduzioni comuni
```

### 2. Nomi dei file

Utilizzare nomi di file che riflettono chiaramente il contesto:

- `[nome-risorsa]-resource.php` per le risorse Filament
- `[nome-pagina]-page.php` per le pagine specifiche
- `[nome-componente]-component.php` per i componenti condivisi

## Utilizzo con i Componenti Filament

### 1. Non utilizzare mai ->label() nei componenti

Come specificato nei MEMORIES, non utilizzare mai il metodo `->label()` direttamente nei componenti Filament:

#### ✅ DO - Utilizzare il componente senza label

```php
// Corretto
Tables\Columns\TextColumn::make('nome')

// Il sistema recupererà automaticamente la traduzione da:
// 'fields' => ['nome' => ['label' => 'Nome Utente']]
```

#### ❌ DON'T - Non utilizzare label() esplicito

```php
// NON FARE MAI QUESTO
Tables\Columns\TextColumn::make('nome')
    ->label('Nome Utente')
```

### 1.1 Non utilizzare mai ->label() in getInfolistSchema()

Questa regola si applica anche al metodo `getInfolistSchema()`. Non bisogna mai utilizzare il metodo `->label()` nei componenti di Infolist:

#### ✅ DO - Utilizzare il componente senza label

```php
// Corretto
public function getInfolistSchema(): array
{
    return [
        'nome' => TextEntry::make('nome'),
        'email' => TextEntry::make('email')
    ];
}
```

#### ❌ DON'T - Non utilizzare label() esplicito

```php
// NON FARE MAI QUESTO
public function getInfolistSchema(): array
{
    return [
        'nome' => TextEntry::make('nome')->label('Nome Utente'),
        'email' => TextEntry::make('email')->label('Indirizzo Email')
    ];
}
```

Il `LangServiceProvider` gestisce automaticamente l'etichettatura di tutti i componenti attraverso il sistema di traduzione. L'uso di `->label()` interferisce con questo meccanismo automatico e può portare a incoerenze nell'interfaccia utente.

### 2. Utilizzo in altri contesti

Per altri contesti, utilizzare la funzione `trans()` o la direttiva `@lang` con i percorsi completi:

```php
// In un controller o service
$label = trans('module-name::resource.fields.field_name.label');

// In una Blade view
<h2>@lang('module-name::page.title')</h2>
<p>@lang('module-name::page.subtitle')</p>
```

## Gestione delle Pluralizzazioni

Utilizzare la struttura di pluralizzazione di Laravel per gestire correttamente le forme plurali:

```php
// resources/lang/it/messages.php
return [
    'apples' => '{0} Nessuna mela|{1} Una mela|[2,*] :count mele',
    'records_count' => '{0} Nessun record trovato|{1} Un record trovato|[2,*] :count records trovati',
];
```

Utilizzo:

```php
echo trans_choice('messages.apples', 0); // Nessuna mela
echo trans_choice('messages.apples', 1); // Una mela
echo trans_choice('messages.apples', 10, ['count' => 10]); // 10 mele
```

## Parametri e Segnaposto

Utilizzare parametri con segnaposto nei testi:

```php
// resources/lang/it/messages.php
return [
    'welcome' => 'Benvenuto, :name!',
    'goodbye' => 'Arrivederci, :name, a :time!',
];
```

Utilizzo:

```php
echo trans('messages.welcome', ['name' => 'Mario']); // Benvenuto, Mario!
echo trans('messages.goodbye', ['name' => 'Mario', 'time' => 'domani']); // Arrivederci, Mario, a domani!
```

## Vantaggi della Struttura Espansa

L'utilizzo della struttura espansa offre numerosi vantaggi:

1. **Completezza**: Ogni campo/azione può avere più attributi associati (label, tooltip, placeholder, etc.)
2. **Coerenza**: Tutti gli elementi dell'interfaccia utilizzano lo stesso pattern
3. **Estendibilità**: Facile aggiungere nuovi attributi in futuro senza modificare il codice
4. **Organizzazione**: Struttura chiara e prevedibile per tutti i file di traduzione
5. **UX migliorata**: Supporto per tooltip, placeholder e altri elementi per migliorare l'esperienza utente

## Perché è Cruciale

Non utilizzare la struttura espansa può causare i seguenti problemi:

1. **Limitazioni UI**: Impossibilità di aggiungere tooltip, placeholder o altri aiuti contestuali
2. **Incoerenza**: Traduzioni gestite in modi diversi in parti diverse dell'applicazione
3. **Manutenzione difficile**: File di traduzione meno organizzati e più difficili da gestire
4. **Problemi di integrazione**: Incompatibilità con il funzionamento atteso del sistema di traduzioni di Laraxot
5. **Modifiche future più complesse**: Necessità di ristrutturare completamente i file in caso di nuove esigenze

## Implementazione Pratica

### Aggiunta di una nuova traduzione

1. Creare il file nella cartella appropriata se non esiste
2. Aggiungere la struttura completa con tutti gli elementi necessari
3. Assicurarsi di utilizzare la struttura espansa per tutti gli elementi
4. Aggiungere commenti per semplificare la manutenzione

Esempio:

```php
<?php

/**
 * Traduzioni per la risorsa Convenzione
 *
 * @package Modules\Brain\Resources
 */

return [
    'label' => 'Convenzione',
    'plural_label' => 'Convenzioni',
    'navigation_group' => 'Gestione Associativa',
    'navigation_icon' => 'heroicon-o-document-text',
    'navigation_sort' => 3,
    'description' => 'Gestione delle convenzioni con enti e aziende',

    'fields' => [
        'id_convenzione' => [
            'label' => 'ID',
            'tooltip' => 'Identificativo univoco della convenzione'
        ],
        'nome' => [
            'label' => 'Nome',
            'tooltip' => 'Nome dell\'ente o azienda convenzionata',
            'placeholder' => 'Es. Azienda XYZ'
        ],
        'descrizione' => [
            'label' => 'Descrizione',
            'tooltip' => 'Descrizione dettagliata della convenzione',
            'placeholder' => 'Inserisci i dettagli della convenzione...'
        ],
        // Altri campi...
    ],

    // Resto della struttura...
];
```

### Aggiornamento di traduzioni esistenti

Per aggiornare le traduzioni esistenti da una struttura semplice a una espansa:

1. Identificare i file che utilizzano la struttura semplice
2. Convertire ogni chiave alla struttura espansa appropriata
3. Aggiungere gli attributi aggiuntivi (tooltip, placeholder, etc.) dove necessario
4. Verificare che non ci siano riferimenti diretti nel codice

## Troubleshooting

### Problema: Traduzioni non visualizzate

**Soluzione:** Verificare che:
1. Il file di traduzione sia nella posizione corretta
2. La struttura sia conforme alle linee guida (espansa vs. semplice)
3. Il nome del campo nel componente corrisponda esattamente alla chiave nel file di traduzione
4. Il service provider del modulo estenda `XotBaseServiceProvider` e chiami `parent::boot()`

### Problema: Label hardcoded visibili invece delle traduzioni

**Soluzione:** Verificare che:
1. Non si stia usando il metodo `->label()` nei componenti Filament
2. Il file di traduzione contenga la chiave corretta nella struttura corretta
3. LangServiceProvider sia registrato correttamente
4. I percorsi di caricamento delle traduzioni siano corretti

## Checklist di Implementazione

- [ ] Tutti i file di traduzione utilizzano la struttura espansa per i campi
- [ ] Tutti i file di traduzione utilizzano la struttura espansa per le azioni
- [ ] Nessun componente Filament utilizza il metodo `->label()`
- [ ] I file sono organizzati secondo la struttura consigliata
- [ ] Pluralizzazioni gestite correttamente dove necessario
- [ ] Parametri utilizzati per testi dinamici dove appropriato
- [ ] File commentati per facilitare la manutenzione

## Riferimenti

- [Documentazione Ufficiale Laravel Localization](https://laravel.com/docs/localization)
- [Documentazione di Filament sulla Localizzazione](https://filamentphp.com/docs/3.x/support/localization)
- [LangServiceProvider](base_orisbroker_fila3/laravel/Modules/Xot/Providers/LangServiceProvider.php)
- [TRANSLATIONS.md](base_orisbroker_fila3/laravel/Modules/Brain/docs/TRANSLATIONS.md)

---

## translations-consolidated

*Consolidated from: `translations-consolidated.md`*


> **🎯 Single Source of Truth**: Questo documento centralizza TUTTA la documentazione traduzioni del progetto
>
> **🔗 Riferimenti**: [filament-best-practices.md](filament-best-practices.md) | [conventions.md](conventions.md)

## 🚨 STOP DUPLICAZIONE!

**Prima di creare nuovi file traduzioni, LEGGI QUESTO DOCUMENTO!**

Questo documento sostituisce e consolida **107+ file traduzioni duplicati** trovati in tutti i moduli.

### ❌ File da NON Creare Più
- `translations.md` in qualsiasi modulo
- `translation-rules.md` duplicati
- `translation_standards.md` sparsi
- `translation_best_practices.md` in ogni modulo
- Qualsiasi documentazione traduzioni specifica di modulo

### ✅ Unica Fonte di Verità
- **Questo file**: `/laravel/Modules/Xot/project_docs/translations-consolidated.md`
- **File lang**: Solo nei singoli moduli (contenuto, non docs)

## Principi Fondamentali Universali

### Struttura Espansa Obbligatoria (Tutti i Moduli)
```php
'field_name' => [
    'label' => 'Field Label',           // OBBLIGATORIO
    'placeholder' => 'Placeholder',     // OBBLIGATORIO
    'helper_text' => 'Help text',       // OBBLIGATORIO (può essere '')
    'description' => 'Description',     // OBBLIGATORIO
    'validation' => [...],              // SE PRESENTE IN IT
]
```

### ❌ MAI Usare ->label() (Tutti i Moduli)
```php
// ❌ ERRORE: Label hardcoded in qualsiasi modulo
TextInput::make('name')->label('Nome'),

// ✅ CORRETTO: Gestito automaticamente da LangServiceProvider
TextInput::make('name'),
```

### ✅ Posizionamento Universale
- **Traduzioni modulo**: `Modules/{ModuleName}/lang/{locale}/`
- **MAI** in `resources/lang/` root
- **Sintassi**: `declare(strict_types=1);` obbligatorio in tutti i file

## Template Standard Universale

### Struttura Completa per Tutti i Moduli
```php
<?php

declare(strict_types=1);

return [
    // Metadati risorsa (tutti i moduli)
    'navigation' => [
        'label' => 'Resource Label',
        'group' => 'Group Name',
        'icon' => 'heroicon-o-icon-name', // NON tradurre nomi icone
    ],

    // Pagine (tutti i moduli)
    'pages' => [
        'create' => [
            'title' => 'Create New Item',
            'heading' => 'Create Item',
        ],
        'edit' => [
            'title' => 'Edit Item',
            'heading' => 'Edit Item',
        ],
        'list' => [
            'title' => 'Items List',
            'heading' => 'All Items',
        ],
    ],

    // Campi form (tutti i moduli)
    'fields' => [
        'name' => [
            'label' => 'Name',
            'placeholder' => 'Enter name',
            'helper_text' => 'Full name of the user',
            'description' => 'User full name for identification',
        ],
        'email' => [
            'label' => 'Email',
            'placeholder' => 'Enter email address',
            'helper_text' => 'Valid email address',
            'description' => 'Email used for login and notifications',
            'validation' => [
                'required' => 'Email is required',
                'email' => 'Email must be valid',
                'unique' => 'This email is already in use',
            ],
        ],
    ],

    // Azioni (tutti i moduli)
    'actions' => [
        'create' => [
            'label' => 'Create New',
            'modal_heading' => 'Create new item',
            'modal_description' => 'Fill the form to create a new item',
            'success' => 'Item created successfully',
            'error' => 'Error creating item',
        ],
        'edit' => [
            'label' => 'Edit',
            'success' => 'Item updated successfully',
            'error' => 'Error updating item',
        ],
        'delete' => [
            'label' => 'Delete',
            'modal_heading' => 'Delete item',
            'modal_description' => 'Are you sure? This action cannot be undone.',
            'success' => 'Item deleted successfully',
            'error' => 'Error deleting item',
            'confirmation' => 'Are you sure you want to delete this item?',
        ],
    ],

    // Stati/Enum (tutti i moduli)
    'states' => [
        'active' => [
            'label' => 'Active',
            'description' => 'Item is active',
            'tooltip' => 'Item is active and visible',
            'color' => 'success',
            'icon' => 'heroicon-o-check-circle',
        ],
        'inactive' => [
            'label' => 'Inactive',
            'description' => 'Item is inactive',
            'tooltip' => 'Item is inactive and hidden',
            'color' => 'danger',
            'icon' => 'heroicon-o-x-circle',
        ],
    ],

    // Widget (tutti i moduli)
    'widgets' => [
        'stats_overview' => [
            'heading' => 'Statistics Overview',
            'description' => 'Key metrics and statistics',
        ],
    ],

    // Messaggi (tutti i moduli)
    'messages' => [
        'empty_state' => 'No items found',
        'loading' => 'Loading...',
        'error' => 'An error occurred',
    ],
];
```

## Errori Comuni Universali

### 1. Helper Text Uguale alla Chiave (Tutti i Moduli)
```php
// ❌ ERRORE: helper_text uguale al nome campo
'studio' => [
    'label' => 'Studio',
    'helper_text' => 'studio', // ERRORE!
]

// ✅ CORRETTO: helper_text vuoto o descrittivo
'studio' => [
    'label' => 'Studio',
    'helper_text' => '', // O descrizione appropriata
]
```

### 2. Chiavi Non Tradotte (Tutti i Moduli)
```php
// ❌ ERRORE: Chiave invece di traduzione
'previsit_step' => [
    'label' => 'previsit_step', // ERRORE!
]

// ✅ CORRETTO: Traduzione appropriata
'previsit_step' => [
    'label' => 'Pre-Visit Step',
]
```

### 3. Terminazioni .navigation (Tutti i Moduli)
```php
// ❌ ERRORE: Chiavi che terminano con .navigation
'menu.navigation' => 'Menu',

// ✅ CORRETTO: Chiavi esplicite
'menu' => [
    'label' => 'Menu',
    'description' => 'Main navigation menu',
]
```

### 4. Proprietà Mancanti negli Stati (Tutti i Moduli)
```php
// ❌ ERRORE: Manca tooltip negli stati
'active' => [
    'label' => 'Active',
    'color' => 'success',
]

// ✅ CORRETTO: Tutte le proprietà
'active' => [
    'label' => 'Active',
    'description' => 'Item is active',
    'tooltip' => 'Item is active and visible',
    'color' => 'success',
    'icon' => 'heroicon-o-check-circle',
]
```

## Regole per Modulo Specifico

### Activity Module
- **Focus**: Activity logging, User actions
- **Campi comuni**: `action`, `description`, `user_id`, `created_at`
- **Stati**: `pending`, `completed`, `failed`

### Chart Module
- **Focus**: Chart data, Visualization
- **Campi comuni**: `title`, `type`, `data`, `options`
- **Stati**: `draft`, `published`, `archived`

### Cms Module
- **Focus**: Content management, Pages
- **Campi comuni**: `title`, `content`, `slug`, `status`
- **Stati**: `draft`, `published`, `scheduled`

### FormBuilder Module
- **Focus**: Form creation, Fields
- **Campi comuni**: `name`, `type`, `required`, `options`
- **Stati**: `active`, `inactive`, `testing`

### Gdpr Module
- **Focus**: Privacy, Consent
- **Campi comuni**: `consent_type`, `given_at`, `withdrawn_at`
- **Stati**: `given`, `withdrawn`, `pending`

### Geo Module
- **Focus**: Geographic data, Locations
- **Campi comuni**: `latitude`, `longitude`, `address`, `country`
- **Stati**: `verified`, `unverified`, `approximate`

### Job Module
- **Focus**: Queue jobs, Background tasks
- **Campi comuni**: `name`, `status`, `started_at`, `completed_at`
- **Stati**: `pending`, `running`, `completed`, `failed`

### Lang Module
- **Focus**: Language management, Localization
- **Campi comuni**: `locale`, `key`, `value`, `group`
- **Stati**: `active`, `inactive`, `incomplete`

### Media Module
- **Focus**: File uploads, Media management
- **Campi comuni**: `filename`, `mime_type`, `size`, `path`
- **Stati**: `uploading`, `ready`, `processing`, `failed`

### Notify Module
- **Focus**: Notifications, Alerts
- **Campi comuni**: `title`, `message`, `type`, `read_at`
- **Stati**: `unread`, `read`, `archived`

### <nome progetto> Module
- **Focus**: Health management, Medical data
- **Campi comuni**: `patient_id`, `doctor_id`, `date`, `notes`
- **Stati**: `scheduled`, `completed`, `cancelled`

### <nome progetto> Module (CRITICO)
- **Focus**: Core health platform, Appointments
- **Campi comuni**: `appointment_id`, `patient_id`, `doctor_id`, `studio_id`
- **Stati**: `scheduled`, `confirmed`, `in_progress`, `completed`, `cancelled`

### Tenant Module
- **Focus**: Multi-tenancy, Organization
- **Campi comuni**: `name`, `domain`, `settings`, `active`
- **Stati**: `active`, `inactive`, `suspended`

### UI Module
- **Focus**: User interface, Components
- **Campi comuni**: `component`, `props`, `theme`, `variant`
- **Stati**: `enabled`, `disabled`, `deprecated`

### User Module (CRITICO)
- **Focus**: User management, Authentication
- **Campi comuni**: `name`, `email`, `role`, `permissions`
- **Stati**: `active`, `inactive`, `pending`, `suspended`

### Xot Module (CRITICO)
- **Focus**: Framework core, Base functionality
- **Campi comuni**: `module`, `action`, `params`, `result`
- **Stati**: `enabled`, `disabled`, `maintenance`

## Parità Strutturale IT-EN Universale

### Regola Critica per Tutti i Moduli
Il file inglese DEVE avere **identica struttura** del file italiano:
- Stesso numero di sezioni
- Stesse proprietà per ogni campo
- Stesso ordine delle sezioni

### Validazione Strutturale
```bash
# Script per verificare parità strutturale (tutti i moduli)
php artisan translation:validate --module=Activity --compare=it,en
php artisan translation:validate --module=Chart --compare=it,en
php artisan translation:validate --module=Cms --compare=it,en
# ... per tutti i moduli
```

## LangServiceProvider Integration Universale

### Automatismo Label (Tutti i Moduli)
Il `LangServiceProvider` gestisce automaticamente le label basandosi su:
1. Nome del campo
2. Struttura del file di traduzione
3. Convenzioni di naming

### Nessun Override Manuale (Tutti i Moduli)
```php
// ❌ MAI fare questo in nessun modulo
TextInput::make('name')->label(__('module::field.name.label'))

// ✅ SEMPRE così in tutti i moduli
TextInput::make('name') // Label automatica da traduzione
```

## Workflow Qualità Universale

### Pre-Commit Checklist (Tutti i Moduli)
- [ ] Struttura espansa per tutti i campi
- [ ] Parità IT-EN verificata
- [ ] Nessuna stringa hardcoded
- [ ] `declare(strict_types=1);` presente
- [ ] Array syntax breve `[]`

### Controlli Automatici (Tutti i Moduli)
```bash
# Controllo chiavi hardcoded (tutti i moduli)
for module in Activity Chart Cms FormBuilder Gdpr Geo Job Lang Media Notify <nome progetto> <nome progetto> Tenant UI User Xot; do
    php artisan translation:check-hardcoded --module=$module
done

# Validazione sintassi (tutti i moduli)
for module in Activity Chart Cms FormBuilder Gdpr Geo Job Lang Media Notify <nome progetto> <nome progetto> Tenant UI User Xot; do
    php artisan translation:validate-syntax --module=$module
done
```

## 🔥 ELIMINAZIONE DUPLICAZIONI

### File da Eliminare IMMEDIATAMENTE
Tutti questi file sono DUPLICATI e vanno eliminati:

```bash
# Activity
rm Modules/Activity/docs/translations.md

# Chart
rm Modules/Chart/docs/translations.md

# Cms
rm Modules/Cms/docs/translations.md

# FormBuilder
rm Modules/FormBuilder/docs/translations.md

# Job
rm Modules/Job/docs/translations.md

# Media
rm Modules/Media/docs/translations.md

# Tenant
rm Modules/Tenant/docs/translations.md

# UI
rm Modules/UI/docs/translations.md
rm Modules/UI/docs/translation-rules.md

# <nome progetto>
rm Modules/<nome progetto>/docs/translation-files-improvement.md
rm Modules/<nome progetto>/docs/translations.md
rm Modules/<nome progetto>/docs/translation_quality_standards.md
rm Modules/<nome progetto>/docs/translations-states-analysis.md
rm Modules/<nome progetto>/docs/translation-rules.md
rm Modules/<nome progetto>/docs/translation_standards.md
rm Modules/<nome progetto>/docs/translations-appointments.md

# User
rm Modules/User/docs/translations.md
rm Modules/User/docs/translation_keys_rules.md
rm Modules/User/docs/translation_best_practices.md

# Lang
rm Modules/Lang/docs/translation-keys-best-practices.md
rm Modules/Lang/docs/translation-preservation-rules.md
rm Modules/Lang/docs/translation_standards_links.md
rm Modules/Lang/docs/translation_notify_conversion.md
rm Modules/Activity/project_docs/translations.md

# Chart
rm Modules/Chart/project_docs/translations.md

# Cms
rm Modules/Cms/project_docs/translations.md

# FormBuilder
rm Modules/FormBuilder/project_docs/translations.md

# Job
rm Modules/Job/project_docs/translations.md

# Media
rm Modules/Media/project_docs/translations.md

# Tenant
rm Modules/Tenant/project_docs/translations.md

# UI
rm Modules/UI/project_docs/translations.md
rm Modules/UI/project_docs/translation-rules.md

# <nome progetto>
rm Modules/<nome progetto>/project_docs/translation-files-improvement.md
rm Modules/<nome progetto>/project_docs/translations.md
rm Modules/<nome progetto>/project_docs/translation_quality_standards.md
rm Modules/<nome progetto>/project_docs/translations-states-analysis.md
rm Modules/<nome progetto>/project_docs/translation-rules.md
rm Modules/<nome progetto>/project_docs/translation_standards.md
rm Modules/<nome progetto>/project_docs/translations-appointments.md

# User
rm Modules/User/project_docs/translations.md
rm Modules/User/project_docs/translation_keys_rules.md
rm Modules/User/project_docs/translation_best_practices.md

# Lang
rm Modules/Lang/project_docs/translation-keys-best-practices.md
rm Modules/Lang/project_docs/translation-preservation-rules.md
rm Modules/Lang/project_docs/translation_standards_links.md
rm Modules/Lang/project_docs/translation_notify_conversion.md

# E tutti gli altri 57+ file duplicati...
```

### Mantenere Solo
- **Questo file**: `/laravel/Modules/Xot/project_docs/translations-consolidated.md`
- **File lang**: Solo contenuto traduzioni nei singoli moduli

## Troubleshooting Universale

### Traduzioni Non Visualizzate (Tutti i Moduli)
1. Verificare namespace modulo
2. Controllare LangServiceProvider registrato
3. Validare sintassi file PHP
4. Verificare cache traduzioni

### Errori di Struttura (Tutti i Moduli)
1. Confrontare con file italiano
2. Verificare proprietà obbligatorie
3. Controllare nesting corretto
4. Validare chiavi duplicate

---

**🎯 Obiettivo**: Da 107+ file duplicati a 1 file centralizzato
**📈 Beneficio**: 99% riduzione duplicazioni, manutenzione semplificata
**🔗 Vedi anche**: [filament-best-practices.md](filament-best-practices.md) | [conventions.md](conventions.md)

**Aggiornato**: [DATE]
**Categoria**: translations
**Priorità**: CRITICA

---

## translations-navigation

*Consolidated from: `translations-navigation.md`*

module: theme
topic: translations-navigation
canonical: ../../../Themes/docs/shared-components/traduzioni-navigation.md
---

See canonical documentation: ../../../Themes/docs/shared-components/traduzioni-navigation.md
---

## translations

*Consolidated from: `translations.md`*


# Traduzioni

## Struttura delle Traduzioni

Le traduzioni sono organizzate in:

```
Modules/[Nome]/
└── lang/
    ├── it/
    │   └── [nome].php
    └── en/
        └── [nome].php
```

## Regole per le Traduzioni

## Struttura Base

Ogni modulo deve avere:
1. Una cartella `lang/` con le traduzioni
2. Un file `translations.md` nella cartella `docs/` con i collegamenti bidirezionali

## Struttura delle Cartelle

```
Modules/<NomeModulo>/
├── lang/
│   ├── it/
│   │   └── <nome_modulo>.php
│   └── en/
│       └── <nome_modulo>.php
└── docs/
    └── translations.md
```

## Regole per i File di Traduzione

1. **Naming**:
   - Il file di traduzione deve avere lo stesso nome del modulo in minuscolo
   - Esempio: `patient.php` per il modulo Patient

2. **Struttura**:
   - Le chiavi devono essere organizzate gerarchicamente
   - Usare array annidati per raggruppare le traduzioni correlate
   - Esempio:
     ```php
     return [
         'registration' => [
             'label' => 'Registrazione',
             'tooltip' => 'Completa la registrazione'
         ]
     ];
     ```

3. **Collegamenti**:
   - Ogni file `translations.md` deve contenere:
     - Link al modulo Lang
     - Link alle regole generali in Xot
     - Esempi di implementazione

## Esempio di File translations.md

```markdown

# Traduzioni del Modulo <NomeModulo>

## Collegamenti

- [Modulo Lang](../../Lang/docs/module_lang.md) - Documentazione principale
- [Regole Generali](../../Xot/docs/translations.md) - Regole base

## Struttura

```
Modules/<NomeModulo>/
└── lang/
    ├── it/
    │   └── <nome_modulo>.php
    └── en/
        └── <nome_modulo>.php
```

## Contenuto

Il file `<nome_modulo>.php` contiene le traduzioni per:
- [Lista delle sezioni tradotte]

## Esempi

```php
return [
    'sezione' => [
        'label' => 'Etichetta',
        'tooltip' => 'Descrizione'
    ]
];
```

## Regole per le Traduzioni

1. **Organizzazione**:
   - Ogni modulo ha le proprie traduzioni
   - Le traduzioni vanno in `lang/[lingua]/[nome].php`
   - Non usare mai `->label()` nei componenti
   - Usare sempre i file di traduzione

2. **Struttura dei File**:
   - Chiavi in snake_case
   - Valori in formato array con label
   - Supporto per tooltip e placeholder
   - Esempio:
     ```php
     return [
         'field_name' => [
             'label' => 'Nome Campo',
             'tooltip' => 'Descrizione del campo',
             'placeholder' => 'Inserisci il valore'
         ]
     ];
     ```

3. **Best Practices**:
   - Mantenere le traduzioni aggiornate
   - Usare chiavi descrittive
   - Documentare le traduzioni
   - Verificare la coerenza

4. **Integrazione con Filament**:
   - Le traduzioni sono automaticamente caricate
   - Non usare `->label()` nei componenti
   - Usare le chiavi di traduzione
   - Mantenere la coerenza

### Versione Incoming

# Sistema di Traduzioni

## Struttura

### File di Traduzione
```
resources/lang/
├── it/
│   ├── broker.php
│   ├── ui.php
│   └── validation.php
└── en/
    ├── broker.php
    ├── ui.php
    └── validation.php
```

### Formato File
```php
return [
    'resources' => [
        'polizza_convenzione' => [
            'label' => 'Polizza in Convenzione',
            'plural_label' => 'Polizze in Convenzione',
            'navigation' => [
                'group' => 'Portafoglio',
                'icon' => 'heroicon-o-document-text',
                'sort' => 1,
            ],
            'columns' => [
                'numero_adesione' => 'Numero Adesione',
                'cliente' => 'Cliente',
                // ...
            ],
            'filters' => [
                'stato_pratica' => 'Stato Pratica',
                'convenzione' => 'Convenzione',
                // ...
            ],
            'actions' => [
                'create' => 'Nuova Polizza',
                'edit' => 'Modifica',
                'view' => 'Visualizza',
                // ...
            ],
        ],
    ],
];
```

---

## Utilizzo

### In Filament Resources
```php
// NON utilizzare ->label()
TextColumn::make('numero_adesione')
TextColumn::make('cliente.nominativo')
SelectFilter::make('stato_pratica_id')
```

### In Blade Views
```blade
@lang('broker.resources.polizza_convenzione.label')
{{ __('broker.resources.polizza_convenzione.columns.numero_adesione') }}
```

### In PHP
```php
trans('broker.resources.polizza_convenzione.label')
__('broker.resources.polizza_convenzione.columns.numero_adesione')
```

## Best Practices

### 1. Struttura Chiavi
- Utilizzare nomi descrittivi
- Mantenere una gerarchia logica
- Evitare duplicazioni
- Usare snake_case per le chiavi

### 2. Organizzazione File
- Un file per modulo
- Separare le traduzioni per contesto
- Mantenere coerenza tra lingue
- Documentare struttura complessa

### 3. Gestione Traduzioni
- Non cancellare traduzioni esistenti
- Aggiungere nuove traduzioni in modo incrementale
- Mantenere le traduzioni aggiornate
- Verificare completezza traduzioni

### 4. Performance
- Utilizzare cache delle traduzioni
- Caricare solo le traduzioni necessarie
- Evitare traduzioni dinamiche
- Ottimizzare file di grandi dimensioni

## LangService

### Caratteristiche
- Caricamento automatico traduzioni
- Fallback su lingua predefinita
- Cache delle traduzioni
- Supporto per più lingue

### Configurazione
```php
// config/xot.php
return [
    'translations' => [
        'default_locale' => 'it',
        'fallback_locale' => 'en',
        'cache_translations' => true,
        'cache_key' => 'translations',
        'cache_duration' => 3600,
    ],
];
```

### Metodi Principali
```php
// Carica tutte le traduzioni per una lingua
LangService::loadTranslations(string $locale): array

// Ottiene una traduzione con fallback
LangService::get(string $key, array $replace = []): string

// Verifica esistenza traduzione
LangService::has(string $key): bool

// Aggiunge traduzioni runtime
LangService::add(string $key, string $value): void
```

## Esempi Comuni

### Resource
```php
// Definizione traduzioni
'resources' => [
    'polizza_convenzione' => [
        'label' => 'Polizza in Convenzione',
        'columns' => [
            'numero_adesione' => 'Numero Adesione',
        ],
    ],
],

// Utilizzo in Resource
TextColumn::make('numero_adesione')
```

### Form
```php
// Definizione traduzioni
'forms' => [
    'cliente' => [
        'fields' => [
            'nome' => 'Nome',
            'cognome' => 'Cognome',
        ],
    ],
],

// Utilizzo in Form
TextInput::make('nome')
TextInput::make('cognome')
```

### Actions
```php
// Definizione traduzioni
'actions' => [
    'save' => 'Salva',
    'cancel' => 'Annulla',
    'delete' => [
        'label' => 'Elimina',
        'confirm' => 'Sei sicuro?',
    ],
],

// Utilizzo in Actions
Action::make('save')
Action::make('delete')

### Versione HEAD

```

## Nuove Best Practices

### 1. Gestione Errori
- Traduzioni per messaggi di errore
- Codici errore standardizzati
- Messaggi di errore descrittivi
- Supporto multilingua per errori

### 2. Validazione
- Traduzioni per regole di validazione
- Messaggi di validazione personalizzati
- Supporto per regole custom
- Coerenza nei messaggi

### 3. SEO
- Traduzioni per meta tag
- Descrizioni multilingua
- Keywords localizzate
- URL localizzati

### 4. Accessibilità
- Testi alternativi per immagini
- Descrizioni per elementi interattivi
- Messaggi di stato
- Supporto per screen reader

### 5. Performance
- Lazy loading traduzioni
- Bundle per lingua
- Preload traduzioni critiche
- Ottimizzazione cache

### 6. Testing
- Verifica traduzioni mancanti
- Test di coerenza
- Validazione formati
- Test di performance

## Collegamenti tra versioni di translations.md
* [translations.md](../../../Chart/docs/translations.md)
* [translations.md](../../../Reporting/docs/translations.md)
* [translations.md](../../../Gdpr/docs/translations.md)
* [translations.md](../../../Notify/docs/translations.md)
* [translations.md](../../../Xot/docs/roadmap/lang/translations.md)
* [translations.md](../../../Xot/docs/translations.md)
* [translations.md](../../../Dental/docs/translations.md)
* [translations.md](../../../User/docs/translations.md)
* [translations.md](../../../UI/docs/translations.md)
* [translations.md](../../../Lang/docs/packages/translations.md)
* [translations.md](../../../Lang/docs/translations.md)
* [translations.md](../../../Job/docs/translations.md)
* [translations.md](../../../Media/docs/translations.md)
* [translations.md](../../../Tenant/docs/translations.md)
* [translations.md](../../../Activity/docs/translations.md)
* [translations.md](../../../Patient/docs/translations.md)
* [translations.md](../../../Cms/docs/translations.md)

### Versione Incoming

```

---

---

**Consolidated by:** Phase 2f intelligent merging
**Date:** 2026-08-04
