---
title: "xot — Consolidated Documentation"
module: xot
type: integration
tags: [integrations, modules, xot]
created: 2026-08-24
updated: 2026-08-24
---

# xot — Consolidated Documentation

Consolidated from **52** individual files.

## Table of Contents

- [Xot Base Classes in Laravel Modules](#xot-base-classes-xot-base-classes-in-laravel-modules)
- [Xot Base Classes in Laravel Modules](#xot-base-classes)
- [](#xot-base-component)
- [XotBasePage](#xot-base-page)
- [](#xot-base-resource-page)
- [](#xot-base-resource)
- [Aggiornamento File di Traduzione xot_base.php](#xot-base-translation-update-aggiornamento-file-di-traduzione-xotbase)
- [Aggiornamento File di Traduzione xot_base.php](#xot-base-translation-update)
- [](#xot-base-widget)
- [XotBaseWizardWidget](#xot-base-wizard-widget)
- [](#xot-composer)
- [🚀 XOT - IL MOTORE FONDAMENTALE DI LARAXOT](#xot-engine-complete-guide)
- [---](#xot-engine)
- [HasXotTable Trait per Filament in Laraxot PTVX](#xot-table)
- [Modulo Xot](#xot)
- [XotBasePage getModel() Fix - Risoluzione Errore Static/Non-Static](#xotbaage-getmodel-fix)
- [XotBasePage getModel() Fix - Risoluzione Errore Static/Non-Static](#xotbaage-getmodel)
- [xotbasepage: implementazione e best practices](#xotbaage-implementation)
- [XotBasePage - Classe Base per le Pagine Filament](#xotbaage)
- [](#xotbaanelprovider-refactoring)
- [](#xotbaanelprovider)
- [XotBase Extension Rules - Comprehensive Guide](#xotbase-extension-rules-conflict)
- [](#xotbase-extension-rules-variant)
- [XotBase Extension Rules - Comprehensive Guide](#xotbase-extension-rules-xotbase-extension-rules-comprehensive)
- [Regole di Estensione XotBase - Guida di Riferimento](#xotbase-extension-rules)
- [---](#xotbase-extension)
- [](#xotbase-page-business-logic)
- [🚀 XotBase Quick Reference](#xotbase-quick-reference-conflict)
- [](#xotbase-quick-reference-variant)
- [🚀 XotBase Quick Reference](#xotbase-quick-reference)
- [](#xotbase-stats-overview-widget-examples)
- [](#xotbase-stats-overview-widget-improvements)
- [XotBaseStatsOverviewWidget](#xotbase-stats-overview-widget)
- [](#xotbase_extension_rules)
- [](#xotbasecluster)
- [](#xotbaselistrecords)
- [XotBasePage getModel() Fix - Risoluzione Errore Static/Non-Static](#xotbasepage-getmodel-fix)
- [xotbasepage: implementazione e best practices](#xotbasepage-implementation)
- [XotBasePage InteractsWithForms Conflict Fix](#xotbasepage-interactswithforms-fix)
- [](#xotbasepanelprovider-refactoring-variant)
- [](#xotbasepanelprovider-refactoring)
- [](#xotbasepanelprovider_refactoring)
- [VIOLAZIONI CRITICHE XotBaseResource - Regole Globali Laraxot PTVX](#xotbaseresource-violations)
- [XotBaseResource](#xotbaseresource)
- [Risoluzione conflitto XotBaseRouteServiceProvider.php](#xotbaserouteserviceprovider-conflict-resolution-risoluzione-conflitto-xotbaserouteservic)
- [Risoluzione conflitto XotBaseRouteServiceProvider.php](#xotbaserouteserviceprovider-conflict-resolution)
- [---](#xotbaserouteserviceprovider-resolution)
- [---](#xotbaserouteserviceprovider_conflict_resolution)
- [XotBaseServiceProvider](#xotbaseserviceprovider)
- [](#xotbasethemeserviceprovider)
- [XotBaseWidget](#xotbasewidget)
- [](#xotdata)

---

## xot-base-classes-xot-base-classes-in-laravel-modules

*Consolidated from: `xot-base-classes-xot-base-classes-in-laravel-modules.md`*


## Overview
The Xot base classes provide a centralized way to customize and extend functionality across different modules. This document outlines the usage and benefits of Xot base classes for consistent customization.

## Key Principles
1. **Centralized Customization**: Xot base classes centralize customizations to ensure consistency across modules.
2. **Avoid Direct Extensions**: Never extend Filament or other framework classes directly; always use Xot base classes.

## Implementation Guidelines
### Using XotBaseResource
- Instead of extending `Filament\Resources\Resource`, use `XotBaseResource` from the Xot module.
  ```php
  namespace Modules\Patient\Filament\Resources;

  use Modules\Xot\Filament\Resources\XotBaseResource;

  class DoctorResource extends XotBaseResource
  {
      // Resource definition
      public static function getFormSchema(): array
      {
          return [
              'full_name' => Forms\Components\TextInput::make('full_name'),
              'email' => Forms\Components\TextInput::make('email'),
          ];
      }
  }
  ```

### Using XotBasePage
- For pages, extend `XotBasePage` instead of `Filament\Pages\Page`.
  ```php
  namespace Modules\Notify\Filament\Pages;

  use Modules\Xot\Filament\Pages\XotBasePage;

  class SettingPage extends XotBasePage
  {
      // Page definition
  }
  ```

### Benefits
- **Consistency**: Ensures all customizations follow the same pattern.
- **Ease of Updates**: Simplifies updates when the underlying framework changes.
- **Additional Functionality**: Provides additional methods and properties specific to the module ecosystem.

## Common Issues and Fixes
- **Direct Extension**: Developers sometimes extend Filament classes directly. Always use Xot base classes for customization.
- **Importing Original Classes**: Avoid importing original Filament classes if they are not used directly. Remove unnecessary imports.

## Documentation and Updates
- Document any custom Xot base classes or significant customizations in the module's `docs` folder.
- Update this document if new Xot base classes are introduced.

## Links to Related Documentation
- [Code Quality](../Xot/docs/CODE_QUALITY.md)
- [Filament Extension Pattern](filament_extension_pattern.md)
- [Filament Extension Pattern Analysis](filament_extension_pattern_analysis.md)
- [Patient Module - Filament Customization](../../Patient/docs/FILAMENT_CUSTOMIZATION.md)
- [Patient Module - Namespace Conventions](../../Patient/docs/NAMESPACE_CONVENTIONS.md)

---

## xot-base-classes

*Consolidated from: `xot-base-classes.md`*


## Overview
The Xot base classes provide a centralized way to customize and extend functionality across different modules. This document outlines the usage and benefits of Xot base classes for consistent customization.

## Key Principles
1. **Centralized Customization**: Xot base classes centralize customizations to ensure consistency across modules.
2. **Avoid Direct Extensions**: Never extend Filament or other framework classes directly; always use Xot base classes.

## Implementation Guidelines
### Using XotBaseResource
- Instead of extending `Filament\Resources\Resource`, use `XotBaseResource` from the Xot module.
  ```php
  namespace Modules\Patient\Filament\Resources;

  use Modules\Xot\Filament\Resources\XotBaseResource;

  class DoctorResource extends XotBaseResource
  {
      // Resource definition
      public static function getFormSchema(): array
      {
          return [
              'full_name' => Forms\Components\TextInput::make('full_name'),
              'email' => Forms\Components\TextInput::make('email'),
          ];
      }
  }
  ```

### Using XotBasePage
- For pages, extend `XotBasePage` instead of `Filament\Pages\Page`.
  ```php
  namespace Modules\Notify\Filament\Pages;

  use Modules\Xot\Filament\Pages\XotBasePage;

  class SettingPage extends XotBasePage
  {
      // Page definition
  }
  ```

### Benefits
- **Consistency**: Ensures all customizations follow the same pattern.
- **Ease of Updates**: Simplifies updates when the underlying framework changes.
- **Additional Functionality**: Provides additional methods and properties specific to the module ecosystem.

## Common Issues and Fixes
- **Direct Extension**: Developers sometimes extend Filament classes directly. Always use Xot base classes for customization.
- **Importing Original Classes**: Avoid importing original Filament classes if they are not used directly. Remove unnecessary imports.

## Documentation and Updates
- Document any custom Xot base classes or significant customizations in the module's `docs` folder.
- Update this document if new Xot base classes are introduced.

## Links to Related Documentation
- [Code Quality](../Xot/docs/CODE_QUALITY.md)
- [Filament Extension Pattern](filament_extension_pattern.md)
- [Filament Extension Pattern Analysis](filament_extension_pattern_analysis.md)
- [Patient Module - Filament Customization](../../Patient/docs/FILAMENT_CUSTOMIZATION.md)
- [Patient Module - Namespace Conventions](../../Patient/docs/NAMESPACE_CONVENTIONS.md)
---

## xot-base-component

*Consolidated from: `xot-base-component.md`*


---

## xot-base-page

*Consolidated from: `xot-base-page.md`*


## Panoramica
`XotBasePage` è una classe base astratta che estende `Filament\Pages\Page` e fornisce funzionalità comuni per tutte le pagine Filament nel sistema. Questa classe implementa pattern e best practices standardizzati per la gestione delle pagine.

## Caratteristiche Principali

### 1. Gestione delle View
- Risoluzione automatica delle view basata sul namespace della classe
- Supporto per view personalizzate
- Gestione delle view mancanti con messaggi di errore appropriati

### 2. Sistema di Traduzione
- Integrazione con il sistema di traduzioni di Laravel
- Generazione automatica delle chiavi di traduzione
- Supporto per etichette di navigazione e gruppi

### 3. Gestione dei Form
- Integrazione con il sistema di form di Filament
- Schema di form configurabile
- Gestione dello stato del form

### 4. Autorizzazioni
- Sistema di autorizzazioni integrato
- Verifica automatica dei permessi
- Supporto per politiche di accesso

## Utilizzo

```php
namespace Modules\YourModule\Filament\Pages;

use Modules\Xot\Filament\Pages\XotBasePage;

class YourPage extends XotBasePage
{
    protected static string $view = 'your-module::pages.your-page';

    protected function getFormSchema(): array
    {
        return [
            // Schema del form
        ];
    }
}
```

## Best Practices

1. **View**
   - Definire sempre la proprietà `$view` nelle classi figlie
   - Utilizzare il namespace del modulo per le view
   - Seguire la convenzione di naming delle view

2. **Traduzioni**
   - Utilizzare il sistema di traduzioni per tutte le stringhe
   - Non hardcodare le stringhe nel codice
   - Mantenere i file di traduzione organizzati

3. **Form**
   - Implementare `getFormSchema()` per definire la struttura del form
   - Utilizzare i componenti Filament standard
   - Gestire correttamente lo stato del form

4. **Autorizzazioni**
   - Implementare le politiche di accesso appropriate
   - Utilizzare il sistema di autorizzazioni di Laravel
   - Documentare i requisiti di accesso

## Metodi Principali

### `getModuleName()`
Restituisce il nome del modulo dalla classe.

### `trans(string $key)`
Genera una chiave di traduzione basata sul namespace della classe.

### `getModel()`
Restituisce il modello associato alla pagina.

### `getFormSchema()`
Definisce lo schema del form della pagina.

### `authorizeAccess()`
Verifica se l'utente ha l'accesso alla pagina.

## Note Tecniche

1. **Namespace**
   - Le classi devono essere nel namespace `Modules\{ModuleName}\Filament\Pages`
   - Le view devono essere nel namespace `{module-name}::pages`

2. **Dipendenze**
   - Filament Pages
   - Filament Forms
   - Laravel Authorization

3. **Compatibilità**
   - Compatibile con Filament 3.x
   - Richiede PHP 8.1+

## Link Correlati

- [Documentazione Filament](../../../../docs/project/filament/index.md)
- [Best Practices](../../../../docs/project/best-practices.md)
- [Guida Traduzioni](../../../../docs/project/translations.md)
---

## xot-base-resource-page

*Consolidated from: `xot-base-resource-page.md`*


---

## xot-base-resource

*Consolidated from: `xot-base-resource.md`*


---

## xot-base-translation-update-aggiornamento-file-di-traduzione-xotbase

*Consolidated from: `xot-base-translation-update-aggiornamento-file-di-traduzione-xotbase.md`*


## Data Aggiornamento
2025-01-27

## File Modificato
`Modules/Xot/lang/it/xot_base.php`

## Modifiche Apportate

### 1. Sintassi Array Moderna
- **Prima**: Utilizzo di `array()` syntax
- **Dopo**: Utilizzo di sintassi array breve `[]`
- **Motivazione**: Conformità alle best practice Laraxot e PSR-12

### 2. Dichiarazione Strict Types
- **Aggiunto**: `declare(strict_types=1);` all'inizio del file
- **Motivazione**: Tipizzazione rigorosa per PHPStan livello 9+

### 3. Rimozione Duplicazioni
- **Rimosso**: Campi `helper_text` vuoti
- **Rimosso**: Duplicazioni nei campi `certification` e `doctor_certificate`
- **Migliorato**: Testi dei campi duplicati con etichette più specifiche

### 4. Risoluzione Conflitti Merge
- **Risolto**: Conflitti di merge non risolti alla fine del file
- **Rimosso**: Codice duplicato e marcatori di conflitto

### 5. Struttura Migliorata
- **Aggiunto**: Campi `tooltip` e `help` mancanti per alcune azioni
- **Migliorato**: Coerenza nella struttura dei campi
- **Standardizzato**: Formato delle traduzioni per tutti i campi

## Struttura Finale

Il file ora segue la struttura espansa obbligatoria per Laraxot:

```php
<?php

declare(strict_types=1);

return [
    'fields' => [
        'field_name' => [
            'label' => 'Etichetta Campo',
            'description' => 'Descrizione del campo',
            'placeholder' => 'Placeholder del campo',
            'help' => 'Testo di aiuto',
        ],
    ],
    'actions' => [
        'action_name' => [
            'label' => 'Etichetta Azione',
            'description' => 'Descrizione dell\'azione',
            'tooltip' => 'Tooltip dell\'azione',
            'help' => 'Testo di aiuto',
            'success' => 'Messaggio di successo',
            'error' => 'Messaggio di errore',
            'confirmation' => 'Messaggio di conferma',
        ],
    ],
    // Altri sezioni...
];
```

## Validazione

- ✅ Sintassi PHP valida verificata con `php -l`
- ✅ Conformità alle best practice Laraxot
- ✅ Struttura espansa per tutti i campi
- ✅ Nessuna duplicazione o conflitto di merge
- ✅ Tipizzazione rigorosa con `declare(strict_types=1);`

## Impatto

### Moduli che Utilizzano xot_base.php
- Tutti i moduli che estendono classi base Xot
- Componenti Filament che utilizzano traduzioni base
- Wizard e form che utilizzano step predefiniti

### Compatibilità
- ✅ Compatibile con versioni precedenti
- ✅ Nessun breaking change
- ✅ Miglioramento della qualità del codice

## Collegamenti

- [Regole Traduzioni Xot](translation_rules.md)
- [Best Practices Traduzioni](translations-best-practices.md)
- [Documentazione Principale Traduzioni](../../../docs/translation_rules.md)

*Ultimo aggiornamento: 27 Gennaio 2025*

---

## xot-base-translation-update

*Consolidated from: `xot-base-translation-update.md`*


## Data Aggiornamento
2025-01-27

## File Modificato
`Modules/Xot/lang/it/xot_base.php`

## Modifiche Apportate

### 1. Sintassi Array Moderna
- **Prima**: Utilizzo di `array()` syntax
- **Dopo**: Utilizzo di sintassi array breve `[]`
- **Motivazione**: Conformità alle best practice Laraxot e PSR-12

### 2. Dichiarazione Strict Types
- **Aggiunto**: `declare(strict_types=1);` all'inizio del file
- **Motivazione**: Tipizzazione rigorosa per PHPStan livello 9+

### 3. Rimozione Duplicazioni
- **Rimosso**: Campi `helper_text` vuoti
- **Rimosso**: Duplicazioni nei campi `certification` e `doctor_certificate`
- **Migliorato**: Testi dei campi duplicati con etichette più specifiche

### 4. Risoluzione Conflitti Merge
- **Risolto**: Conflitti di merge non risolti alla fine del file
- **Rimosso**: Codice duplicato e marcatori di conflitto

### 5. Struttura Migliorata
- **Aggiunto**: Campi `tooltip` e `help` mancanti per alcune azioni
- **Migliorato**: Coerenza nella struttura dei campi
- **Standardizzato**: Formato delle traduzioni per tutti i campi

## Struttura Finale

Il file ora segue la struttura espansa obbligatoria per Laraxot:

```php
<?php

declare(strict_types=1);

return [
    'fields' => [
        'field_name' => [
            'label' => 'Etichetta Campo',
            'description' => 'Descrizione del campo',
            'placeholder' => 'Placeholder del campo',
            'help' => 'Testo di aiuto',
        ],
    ],
    'actions' => [
        'action_name' => [
            'label' => 'Etichetta Azione',
            'description' => 'Descrizione dell\'azione',
            'tooltip' => 'Tooltip dell\'azione',
            'help' => 'Testo di aiuto',
            'success' => 'Messaggio di successo',
            'error' => 'Messaggio di errore',
            'confirmation' => 'Messaggio di conferma',
        ],
    ],
    // Altri sezioni...
];
```

## Validazione

- ✅ Sintassi PHP valida verificata con `php -l`
- ✅ Conformità alle best practice Laraxot
- ✅ Struttura espansa per tutti i campi
- ✅ Nessuna duplicazione o conflitto di merge
- ✅ Tipizzazione rigorosa con `declare(strict_types=1);`

## Impatto

### Moduli che Utilizzano xot_base.php
- Tutti i moduli che estendono classi base Xot
- Componenti Filament che utilizzano traduzioni base
- Wizard e form che utilizzano step predefiniti

### Compatibilità
- ✅ Compatibile con versioni precedenti
- ✅ Nessun breaking change
- ✅ Miglioramento della qualità del codice

## Collegamenti

- [Regole Traduzioni Xot](translation_rules.md)
- [Best Practices Traduzioni](translations-best-practices.md)
- [Documentazione Principale Traduzioni](../../../project_docs/translation_rules.md)

*Ultimo aggiornamento: 27 Gennaio 2025*

---

## xot-base-widget

*Consolidated from: `xot-base-widget.md`*


---

## xot-base-wizard-widget

*Consolidated from: `xot-base-wizard-widget.md`*


The `XotBaseWizardWidget` provides a standardized base for creating multi-step form widgets in Filament.

## Features

- **Filament v5 Wizard + `HasWizard`**: `Modules\Xot\Filament\Widgets\XotBaseWizardWidget` include `Filament\Resources\Pages\Concerns\HasWizard` con `parent::form()` su `XotBaseWidget`; **nessun** `normalizeWizardFormState()` sulla base — il dominio usa `$this->form->getState()`; Blade helper opzionale **`DelegatesFilamentWizardSchemaMethods`** dove ancora incluso nel progetto.
- **Schema Integration**: Extends `XotBaseWidget` and `InteractsWithSchemas` conventions.
- **Customizable Actions**: Methods to configure Next, Previous, and Submit actions.
- **Theme Support**: Automatically switches to the theme-level wizard component for front-office views.
- **State Persistence**: Optional support for step persistence in the query string.

## View Calculation Rule

Classes extending `XotBaseWizardWidget` (and more generally `XotBaseWidget`) **must not** define a `protected string $view` property. The view is automatically calculated based on the class name using `GetViewByClassAction`.

### Requirements:
1. **Remove** the `protected string $view` property from child classes.
2. **Add** a class-level docblock comment `@view <calculated-view-name>` to document which view is being used.
3. Ensure the blade file name matches the kebab-case version of the class name (minus the "Widget" suffix).

Example:
```php
/**
 * @view my-module::filament.widgets.my-wizard
 */
class MyWizardWidget extends XotBaseWizardWidget
{
    // No $view property here
}
```

---

## xot-composer

*Consolidated from: `xot-composer.md`*


---

## xot-engine-complete-guide

*Consolidated from: `xot-engine-complete-guide.md`*


## 📋 INDICE
1. [Filosofia Xot](#-filosofia-xot)
2. [Architettura Core](#-architettura-core)
3. [Classi Fondamentali](#-classi-fondamentali)
4. [Pattern Implementativi](#-pattern-implementativi)
5. [Estensioni Future](#-estensioni-future)

---

## 🧠 FILOSOFIA XOT (The Engine Philosophy)

### **Principio Fondamentale: Xot è il Motore, non il Veicolo**
Xot non contiene logica di business, fornisce i **mattoni** per costruirla:
- **50+ Classi Base**: Le fondamenta per ogni pattern
- **20+ Service Provider**: L'iniezione di dipendenze core
- **15+ Trait**: Funzionalità trasversali riutilizzabili
- **Type System**: Garanzia di qualità assoluta

### **DNA Xot: Qualità by Design**
```php
// Dogma Xot: Qualità non è opzionale, è DNA
abstract class XotBaseModel extends Model {
    use HasXotFactory;    // Factory pattern
    use Updater;          // Audit automatico
    use RelationX;        // Relazioni advanced
    // ... 20+ funzionalità standard
}
```

---

## 🏗️ ARCHITETTURA CORE (Core Architecture)

### **1. Layer Model Base**
```
XotBaseModel (Motore)
    ↓
BaseModel (Modulo)
    ↓
Model Specifico (Business)
```

### **2. Service Provider Architecture**
```php
// XotServiceProvider: Il cuore dell'iniezione
class XotServiceProvider extends ServiceProvider {
    public function register(): void {
        $this->app->singleton(CacheManager::class);
        $this->app->singleton(QueryOptimizer::class);
        $this->app->singleton(ApiResponseService::class);
        // ... 20+ servizi core
    }
}
```

### **3. Trait System**
```php
// Traits come "mattoncini" componibili
trait HasExtraTrait {       // Campi extra dinamici
trait HasCaching {          // Caching intelligente
trait DispatchesDomainEvents { // Eventi di dominio
trait HasQueryOptimization {   // Query ottimizzate
```

---

## 🏛️ CLASSI FONDAMENTALI (Foundation Classes)

### **XotBaseModel: Il Modello Perfetto**
```php
<?php

declare(strict_types=1);

namespace Modules\Xot\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Xot\Models\Traits\HasXotFactory;
use Modules\Xot\Traits\Updater;

/**
 * XotBaseModel: Il DNA di ogni modello Laraxot
 *
 * Fornisce AUTOMATICAMENTE:
 * - Factory pattern con HasXotFactory
 * - Audit trail con Updater (created_by, updated_by)
 * - Relazioni advanced con RelationX
 * - Soft deletes (commentato, attivabile)
 * - 20+ proprietà standard configurate
 * - Type hints completi per PHPStan Level 10
 */
abstract class XotBaseModel extends Model
{
    use HasXotFactory;
    use Traits\RelationX;
    use Updater;
    // use SoftDeletes;  // Decommenta quando necessario

    /**
     * Snake attributes per compatibilità database
     * @see https://laravel-news.com/6-eloquent-secrets
     */
    public static $snakeAttributes = true;

    /** @var bool Auto-increment ID */
    public $incrementing = true;

    /** @var bool Timestamps automatici */
    public $timestamps = true;

    /** @var int Pagination default */
    protected $perPage = 30;

    /** @var string Connection di default */
    protected $connection = 'user';

    /** @var list<string> Append automatici */
    protected $appends = [];

    /** @var string Primary key standard */
    protected $primaryKey = 'id';

    /** @var string Key type */
    protected $keyType = 'string';

    /** @var list<string> Campi hidden standard */
    protected $hidden = [];

    /** @var list<string> Campi fillable di base */
    protected $fillable = ['id'];

    /**
     * Boot method per configurazioni automatiche
     * Ogni modello eredita queste configurazioni SENZA scrivere codice
     */
    protected static function boot(): void
    {
        parent::boot();

        // Event listeners automatici
        static::creating(function ($model) {
            // Logica pre-creazione automatica
        });

        static::updating(function ($model) {
            // Logica pre-aggiornamento automatica
        });
    }
}
```

### **XotBaseController: Il Controller Perfetto**
```php
<?php

declare(strict_types=1);

namespace Modules\Xot\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

/**
 * XotBaseController: Il DNA di ogni controller Laraxot
 *
 * Fornisce AUTOMATICAMENTE:
 * - Authorization con AuthorizesRequests
 * - Job dispatch con DispatchesJobs
 * - Validation con ValidatesRequests
 * - Base methods comuni
 * - Error handling standardizzato
 */
abstract class XotBaseController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Response JSON standardizzato
     */
    protected function jsonResponse($data, int $status = 200): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'data' => $data,
            'status' => $status,
            'timestamp' => now()->toISOString(),
        ], $status);
    }

    /**
     * Error response standardizzato
     */
    protected function errorResponse(string $message, int $status = 400): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'error' => $message,
            'status' => $status,
            'timestamp' => now()->toISOString(),
        ], $status);
    }
}
```

### **XotBaseResource: La Risorsa Filament Perfetta**
```php
<?php

declare(strict_types=1);

namespace Modules\Xot\Filament\Resources;

use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Modules\Xot\Datas\XotData;

/**
 * XotBaseResource: Il DNA di ogni risorsa Filament
 *
 * Fornisce AUTOMATICAMENTE:
 * - Form schema standard
 * - Table schema standard
 * - Base configuration
 * - Multi-tenant support
 * - Navigation setup
 */
abstract class XotBaseResource extends Resource
{
    /**
     * Get form schema con validation automatica
     */
    public static function getFormSchema(): array
    {
        return [
            // Schema base automatico
            // I moduli sovrascrivono solo le specificità
        ];
    }

    /**
     * Get table schema con columns automatiche
     */
    public static function getTableSchema(): array
    {
        return [
            // Colonne base automatiche
            // I moduli aggiungono solo le specifiche
        ];
    }

    /**
     * Multi-tenant configuration
     */
    public static function getTenant(): ?string
    {
        return XotData::make()->getTenantClass();
    }
}
```

---

## 🎯 PATTERN IMPLEMENTATIVI (Implementation Patterns)

### **1. BaseModel Pattern: Eredità Controllata**
```php
// Ogni modulo DEVE avere il proprio BaseModel
abstract class BaseModel extends XotBaseModel {
    protected $connection = 'survey_module';  // Connection specifica

    // Solo funzionalità SPECIFICHE del modulo
    // MAI duplicare ciò che XotBaseModel già fornisce
}

// I modelli del modulo estendono SEMPRE BaseModel del modulo
class SurveyPdf extends BaseModel {
    // Solo logica business specifica
}
```

### **2. Action Pattern: Logica Pura**
```php
// Actions incapsulano logica di business pura
class MakePdfAction {
    public function __construct(
        private PdfGenerator $generator,
        private StorageService $storage
    ) {}

    public function execute(SurveyPdf $survey): PdfResponse {
        // Logica pura, senza dipendenze dal framework
        $pdf = $this->generator->generate($survey);
        $path = $this->storage->store($pdf);
        return new PdfResponse($path);
    }
}
```

### **3. Trait Pattern: Composizione vs Eredità**
```php
// Traits come "mixins" riutilizzabili
trait HasExtraTrait {
    public function getExtra(string $key, mixed $default = null): mixed {
        return data_get($this->extra, $key, $default);
    }

    public function setExtra(string $key, mixed $value): void {
        $this->extra = array_merge($this->extra ?? [], [$key => $value]);
        $this->save();
    }
}
```

### **4. Data Pattern: DTO Type-Safe**
```php
// Data objects per trasferimento dati sicuro
class SurveyData extends Data {
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly ?string $description,
        public readonly array $questions,
    ) {}

    public static function fromModel(Survey $survey): self {
        return new self(
            id: $survey->id,
            name: $survey->name,
            description: $survey->description,
            questions: $survey->questions->map(fn($q) => QuestionData::fromModel($q))->toArray(),
        );
    }
}
```

---

## 🚀 ESTENSIONI FUTURE (Future Extensions)

### **1. Xot v2.0: Advanced Features**
```php
// Caching system integrato
trait HasIntelligentCaching {
    protected function cacheKey(string $operation): string {
        return sprintf('%s:%s:%s',
            static::class,
            $this->getKey(),
            $operation
        );
    }

    protected function remember(string $key, callable $callback): mixed {
        return Cache::remember($key, 3600, $callback);
    }
}

// Event system automatico
trait DispatchesDomainEvents {
    private array $domainEvents = [];

    protected function recordEvent(DomainEventInterface $event): void {
        $this->domainEvents[] = $event;
    }

    public function dispatchEvents(): void {
        foreach ($this->domainEvents as $event) {
            event($event);
        }
        $this->domainEvents = [];
    }
}
```

### **2. Query Optimization System**
```php
class QueryOptimizer {
    public function optimize(Builder $query): Builder {
        // Auto-eager loading basato su usage patterns
        // N+1 prevention automatica
        // Query analysis e suggerimenti
    }
}
```

### **3. API Generation Automatica**
```php
// Generazione automatica API endpoints da Models
trait GeneratesApiEndpoints {
    public static function generateApiRoutes(): array {
        return [
            'GET /api/{resource}' => 'index',
            'POST /api/{resource}' => 'store',
            'GET /api/{resource}/{id}' => 'show',
            'PUT /api/{resource}/{id}' => 'update',
            'DELETE /api/{resource}/{id}' => 'destroy',
        ];
    }
}
```

### **4. Testing Automation**
```php
trait GeneratesTests {
    public static function generateFeatureTest(): string {
        // Genera automatico test feature per il modello
        // CRUD operations + business logic tests
    }
}
```

---

## 📊 STATO ATTUALE XOT

### **✅ COMPLETATO**
- **50+ Classi Base** funzionanti
- **PHPStan Level 10** compliance
- **BaseModel pattern** implementato
- **Service Provider architecture**
- **Trait system** avanzato
- **Type safety** completo

### **🔄 IN CORSO**
- **Caching system** unified
- **Domain events** framework
- **Query optimization** automatica
- **API generation** system

### **📋 PIANIFICATO**
- **Real-time features**
- **Advanced security**
- **Performance monitoring**
- **Microservices readiness**

---

## 🎯 BEST PRACTICES XOT

### **1. Sempre Estendere, Mai Duplicare**
```php
// ❌ SBAGLIATO: Duplicazione
class MyModel extends Model {
    protected $connection = 'user';
    public $timestamps = true;
    // ... 20+ proprietà duplicate
}

// ✅ CORRETTO: Eredità
class MyModel extends BaseModel {
    // Solo proprietà specifiche
}
```

### **2. Type Hints Sempre**
```php
// ❌ SBAGLIATO: No type hints
function processData($data) {
    return $data['value'];
}

// ✅ CORRETTO: Type hints completi
function processData(array $data): string {
    Assert::keyExists($data, 'value');
    Assert::string($data['value']);
    return $data['value'];
}
```

### **3. Actions per Logica Business**
```php
// ❌ SBAGLIATO: Logica nel controller
class SurveyController extends Controller {
    public function generatePdf($id) {
        // 100 linee di logica PDF
    }
}

// ✅ CORRETTO: Logica in Action
class SurveyController extends Controller {
    public function generatePdf($id) {
        return $this->pdfAction->execute(Survey::findOrFail($id));
    }
}
```

---

## 🏆 CONCLUSIONE: XOT è IL FUTURO

Xot rappresenta l'evoluzione naturale di Laravel:
- **Maintainability**: Codice che dura 10+ anni
- **Quality**: PHPStan Level 10 by design
- **Performance**: Ottimizzazioni automatiche
- **Security**: Best practices integrate
- **Testing**: Framework completo incluso

**Xot non è un modulo, è il DNA di ogni applicazione Laraxot.**

---

*Documentazione Xot v1.0*
*Creato: 2025-11-17*
*Autore: AI Assistant con analisi approfondita*

---

## xot-engine

*Consolidated from: `xot-engine.md`*

module: theme
topic: xot-engine
canonical: ../../../Themes/docs/shared-components/xot-engine-complete-guide.md
---

See canonical documentation: ../../../Themes/docs/shared-components/xot-engine-complete-guide.md
---

## xot-table

*Consolidated from: `xot-table.md`*


## Panoramica

Il trait `HasXotTable` è un componente fondamentale nell'architettura Filament di Laraxot PTVX, utilizzato sia in `XotBaseResource` che in `XotBaseRelationManager`. Fornisce funzionalità avanzate per la gestione delle tabelle, con particolare attenzione alla tipizzazione, traduzione e configurazione consistente.

## Funzionalità Principali

- **Gestione Layout**: Supporto per differenti layout di tabella (lista, griglia, calendario)
- **Azioni Standardizzate**: Implementazione unificata delle azioni di tabella
- **Traduzione Integrata**: Utilizzo del trait TransTrait per la gestione delle traduzioni
- **Gestione Automatica delle Tabelle**: Verifica dell'esistenza della tabella e notifiche
- **Controllo dei Permessi**: Configurazione semplificata dei permessi (view, edit, delete)

## Proprietà e Metodi Principali

### Proprietà

| Proprietà | Tipo | Default | Descrizione |
|-----------|------|---------|-------------|
| `$layoutView` | `TableLayoutEnum` | `TableLayoutEnum::LIST` | Layout della tabella |
| `$canReplicate` | `bool` | `false` | Se è possibile replicare i record |
| `$canView` | `bool` | `true` | Se è possibile visualizzare i record |
| `$canEdit` | `bool` | `true` | Se è possibile modificare i record |

### Metodi

| Metodo | Tipo di Ritorno | Descrizione |
|--------|-----------------|-------------|
| `getTableHeaderActions()` | `array` | Azioni nell'header della tabella |
| `getTableActions()` | `array` | Azioni per ogni record |
| `getTableBulkActions()` | `array` | Azioni bulk sulla tabella |
| `table(Table $table)` | `Table` | Configurazione base della tabella |
| `configureViewAction()` | `Actions\ViewAction` | Configura l'azione di visualizzazione |
| `configureEditAction()` | `Actions\EditAction` | Configura l'azione di modifica |
| `configureDeleteAction()` | `Actions\DeleteAction` | Configura l'azione di eliminazione |
| `configureDuplicateAction()` | `Actions\ReplicateAction` | Configura l'azione di duplicazione |
| `getSearchableColumns()` | `array` | Colonne ricercabili |
| `hasSearch()` | `bool` | Se la ricerca è abilitata |

## Utilizzo Corretto con XotBaseRelationManager

Quando si estende `XotBaseRelationManager`, il trait `HasXotTable` è già incluso, quindi non è necessario includerlo nuovamente. Ecco come utilizzarne correttamente le funzionalità:

### 1. Definizione delle Colonne

```php
/**
 * Definisce le colonne della tabella.
 *
 * @return array<int, \Filament\Tables\Columns\Column>
 */
public function getTableColumns(): array
{
    return [
        TextColumn::make('id'),
        TextColumn::make('name')
            ->label(trans('module::relation.fields.name.label'))
            ->searchable(),
        // Altre colonne...
    ];
}
```

### 2. Personalizzazione delle Azioni

```php
/**
 * Personalizza le azioni disponibili per ogni record.
 *
 * @return array<\Filament\Tables\Actions\Action>
 */
public function getTableActions(): array
{
    return [
        Tables\Actions\ViewAction::make()
            ->label(trans('module::relation.actions.view.label')),
        // Sovrascrive l'implementazione di HasXotTable
    ];
}
```

### 3. Abilitazione/Disabilitazione di Funzionalità

```php
/**
 * Constructor della classe.
 */
public function __construct()
{
    parent::__construct();

    // Disabilita la replica dei record
    static::$canReplicate = false;

    // Disabilita la visualizzazione dei record
    static::$canView = false;
}
```

### 4. Configurazione del Layout

```php
/**
 * Configura il layout della tabella.
 *
 * @return void
 */
protected function setUp(): void
{
    parent::setUp();

    // Imposta il layout a griglia
    $this->layoutView = TableLayoutEnum::GRID;
}
```

## Integrazione con Traduzioni

Il trait `HasXotTable` utilizza il trait `TransTrait` per la gestione delle traduzioni. Seguire queste linee guida:

```php
// Utilizzo corretto delle traduzioni
Tables\Actions\EditAction::make()
    ->label(trans('module::relation.actions.edit.label'))

// Esempio di file di traduzione (module/lang/it/relation.php)
return [
    'actions' => [
        'edit' => [
            'label' => 'Modifica',
        ],
    ],
]
```

## Best Practices per l'Utilizzo

1. **Non Sovrascrivere il Metodo table()**:
   Il metodo `table()` nel trait `HasXotTable` contiene logica importante. Utilizzare invece `getTableColumns()`, `getTableActions()`, ecc.

2. **Utilizzare Traduzioni per Tutte le Label**:
   ```php
   TextColumn::make('nome')
       ->label(trans('module::relation.fields.nome.label'))
   ```

3. **Tipizzazione Esplicita**:
   ```php
   /**
    * @return array<int, \Filament\Tables\Columns\Column>
    */
   public function getTableColumns(): array
   ```

4. **Rispettare la Struttura delle Traduzioni**:
   Organizzare le traduzioni in sezioni come `fields`, `actions`, `filters`.

5. **Mantenere la Coerenza Visiva**:
   Utilizzare le stesse icone, colori e layout in tutte le tabelle per una UX coerente.

## Compatibilità con PHPStan

Per garantire la compatibilità con PHPStan livello 9, seguire queste regole:

1. Utilizzare annotazioni generiche per gli array:
   ```php
   /**
    * @return array<int, \Filament\Tables\Columns\Column>
    */
   ```

2. Dichiarare tipi di proprietà:
   ```php
   public TableLayoutEnum $layoutView;
   ```

3. Documentare metodi con PHPDoc completi, inclusi i tipi di ritorno.

## Esempio Completo di RelationManager

```php
<?php

declare(strict_types=1);

namespace Modules\NomeModulo\Filament\Resources\EsempioResource\RelationManagers;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

class DatiRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'dati';

    protected static ?string $recordTitleAttribute = 'nome';

    /**
     * Definisce le colonne della tabella.
     *
     * @return array<int, \Filament\Tables\Columns\Column>
     */
    public function getTableColumns(): array
    {
        return [
            TextColumn::make('id'),
            TextColumn::make('nome')
                ->label(trans('nomemodulo::relation.fields.nome.label'))
                ->searchable(),
            TextColumn::make('created_at')
                ->label(trans('nomemodulo::relation.fields.created_at.label'))
                ->dateTime(),
        ];
    }

    /**
     * Definisce lo schema del form.
     *
     * @return array<int, \Filament\Forms\Components\Component>
     */
    public function getFormSchema(): array
    {
        return [
            TextInput::make('nome')
                ->label(trans('nomemodulo::relation.fields.nome.label'))
                ->required(),
        ];
    }
}
```

## Collegamenti alla Documentazione Correlata

- [XotBaseRelationManager](/laravel/modules/xot/project_docs/filament/relation_managers.md)
- [Regole di Traduzione](/laravel/modules/xot/project_docs/translation_rules.md)
- [Filament Resources](/laravel/modules/xot/project_docs/filament/resources.md)

# HasXotTable Trait per Filament in Laraxot PTVX

## Panoramica

Il trait `HasXotTable` è un componente fondamentale nell'architettura Filament di Laraxot PTVX, utilizzato sia in `XotBaseResource` che in `XotBaseRelationManager`. Fornisce funzionalità avanzate per la gestione delle tabelle, con particolare attenzione alla tipizzazione, traduzione e configurazione consistente.

## Funzionalità Principali

- **Gestione Layout**: Supporto per differenti layout di tabella (lista, griglia, calendario)
- **Azioni Standardizzate**: Implementazione unificata delle azioni di tabella
- **Traduzione Integrata**: Utilizzo del trait TransTrait per la gestione delle traduzioni
- **Gestione Automatica delle Tabelle**: Verifica dell'esistenza della tabella e notifiche
- **Controllo dei Permessi**: Configurazione semplificata dei permessi (view, edit, delete)

## Proprietà e Metodi Principali

### Proprietà

| Proprietà | Tipo | Default | Descrizione |
|-----------|------|---------|-------------|
| `$layoutView` | `TableLayoutEnum` | `TableLayoutEnum::LIST` | Layout della tabella |
| `$canReplicate` | `bool` | `false` | Se è possibile replicare i record |
| `$canView` | `bool` | `true` | Se è possibile visualizzare i record |
| `$canEdit` | `bool` | `true` | Se è possibile modificare i record |

### Metodi

| Metodo | Tipo di Ritorno | Descrizione |
|--------|-----------------|-------------|
| `getTableHeaderActions()` | `array` | Azioni nell'header della tabella |
| `getTableActions()` | `array` | Azioni per ogni record |
| `getTableBulkActions()` | `array` | Azioni bulk sulla tabella |
| `table(Table $table)` | `Table` | Configurazione base della tabella |
| `configureViewAction()` | `Actions\ViewAction` | Configura l'azione di visualizzazione |
| `configureEditAction()` | `Actions\EditAction` | Configura l'azione di modifica |
| `configureDeleteAction()` | `Actions\DeleteAction` | Configura l'azione di eliminazione |
| `configureDuplicateAction()` | `Actions\ReplicateAction` | Configura l'azione di duplicazione |
| `getSearchableColumns()` | `array` | Colonne ricercabili |
| `hasSearch()` | `bool` | Se la ricerca è abilitata |

## Utilizzo Corretto con XotBaseRelationManager

Quando si estende `XotBaseRelationManager`, il trait `HasXotTable` è già incluso, quindi non è necessario includerlo nuovamente. Ecco come utilizzarne correttamente le funzionalità:

### 1. Definizione delle Colonne

```php
/**
 * Definisce le colonne della tabella.
 *
 * @return array<int, \Filament\Tables\Columns\Column>
 */
public function getTableColumns(): array
{
    return [
        TextColumn::make('id'),
        TextColumn::make('name')
            ->label(trans('module::relation.fields.name.label'))
            ->searchable(),
        // Altre colonne...
    ];
}
```

### 2. Personalizzazione delle Azioni

```php
/**
 * Personalizza le azioni disponibili per ogni record.
 *
 * @return array<\Filament\Tables\Actions\Action>
 */
public function getTableActions(): array
{
    return [
        Tables\Actions\ViewAction::make()
            ->label(trans('module::relation.actions.view.label')),
        // Sovrascrive l'implementazione di HasXotTable
    ];
}
```

### 3. Abilitazione/Disabilitazione di Funzionalità

```php
/**
 * Constructor della classe.
 */
public function __construct()
{
    parent::__construct();

    // Disabilita la replica dei record
    static::$canReplicate = false;

    // Disabilita la visualizzazione dei record
    static::$canView = false;
}
```

### 4. Configurazione del Layout

```php
/**
 * Configura il layout della tabella.
 *
 * @return void
 */
protected function setUp(): void
{
    parent::setUp();

    // Imposta il layout a griglia
    $this->layoutView = TableLayoutEnum::GRID;
}
```

## Integrazione con Traduzioni

Il trait `HasXotTable` utilizza il trait `TransTrait` per la gestione delle traduzioni. Seguire queste linee guida:

```php
// Utilizzo corretto delle traduzioni
Tables\Actions\EditAction::make()
    ->label(trans('module::relation.actions.edit.label'))

// Esempio di file di traduzione (module/lang/it/relation.php)
return [
    'actions' => [
        'edit' => [
            'label' => 'Modifica',
        ],
    ],
]
```

## Best Practices per l'Utilizzo

1. **Non Sovrascrivere il Metodo table()**:
   Il metodo `table()` nel trait `HasXotTable` contiene logica importante. Utilizzare invece `getTableColumns()`, `getTableActions()`, ecc.

2. **Utilizzare Traduzioni per Tutte le Label**:
   ```php
   TextColumn::make('nome')
       ->label(trans('module::relation.fields.nome.label'))
   ```

3. **Tipizzazione Esplicita**:
   ```php
   /**
    * @return array<int, \Filament\Tables\Columns\Column>
    */
   public function getTableColumns(): array
   ```

4. **Rispettare la Struttura delle Traduzioni**:
   Organizzare le traduzioni in sezioni come `fields`, `actions`, `filters`.

5. **Mantenere la Coerenza Visiva**:
   Utilizzare le stesse icone, colori e layout in tutte le tabelle per una UX coerente.

## Compatibilità con PHPStan

Per garantire la compatibilità con PHPStan livello 9, seguire queste regole:

1. Utilizzare annotazioni generiche per gli array:
   ```php
   /**
    * @return array<int, \Filament\Tables\Columns\Column>
    */
   ```

2. Dichiarare tipi di proprietà:
   ```php
   public TableLayoutEnum $layoutView;
   ```

3. Documentare metodi con PHPDoc completi, inclusi i tipi di ritorno.

## Esempio Completo di RelationManager

```php
<?php

declare(strict_types=1);

namespace Modules\NomeModulo\Filament\Resources\EsempioResource\RelationManagers;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager;

class DatiRelationManager extends XotBaseRelationManager
{
    protected static string $relationship = 'dati';

    protected static ?string $recordTitleAttribute = 'nome';

    /**
     * Definisce le colonne della tabella.
     *
     * @return array<int, \Filament\Tables\Columns\Column>
     */
    public function getTableColumns(): array
    {
        return [
            TextColumn::make('id'),
            TextColumn::make('nome')
                ->label(trans('nomemodulo::relation.fields.nome.label'))
                ->searchable(),
            TextColumn::make('created_at')
                ->label(trans('nomemodulo::relation.fields.created_at.label'))
                ->dateTime(),
        ];
    }

    /**
     * Definisce lo schema del form.
     *
     * @return array<int, \Filament\Forms\Components\Component>
     */
    public function getFormSchema(): array
    {
        return [
            TextInput::make('nome')
                ->label(trans('nomemodulo::relation.fields.nome.label'))
                ->required(),
        ];
    }
}
```

## Collegamenti alla Documentazione Correlata

- [XotBaseRelationManager](/laravel/modules/xot/docs/filament/relation_managers.md)
- [Regole di Traduzione](/laravel/modules/xot/docs/translation_rules.md)
- [Filament Resources](/laravel/modules/xot/docs/filament/resources.md)


---

## xot

*Consolidated from: `xot.md`*


## Descrizione
Modulo core che fornisce funzionalità di base e utility per l'intero sistema.

## Struttura
- [Documentazione Completa](../../Modules/Xot/docs/module_xot.md)

## Componenti Principali

### Datas
- [MetatagData](../../Modules/Xot/docs/datas/MetatagData.md) - Gestione meta tag e configurazione Filament

### Actions
- Panel
  - [ApplyMetatagToPanelAction](../../Modules/Xot/docs/actions/panel/ApplyMetatagToPanelAction.md) - Applica configurazioni metatag al pannello Filament

## Errori PHPStan Comuni
- [Registro Errori](../../Modules/Xot/docs/phpstan/errors.md)
- [Guide alla Correzione](../../Modules/Xot/docs/phpstan/fixes.md)

## Best Practices
- [Convenzioni Namespace](../../Modules/Xot/docs/NAMESPACE-CONVENTIONS.md)
- [Linee Guida PHPStan](../../Modules/Xot/docs/PHPSTAN_LIVELLO10_LINEE_GUIDA.md)

## Collegamenti
- [Roadmap](../../Modules/Xot/docs/roadmap.md)
- [Implementazione](../../Modules/Xot/docs/implementation.md)
- [Integrazione](../../Modules/Xot/docs/integration.md)

---

## xotbaage-getmodel-fix

*Consolidated from: `xotbaage-getmodel-fix.md`*


## Problema
L'errore `Cannot make non static method Filament\Resources\Pages\Page::getModel() static in class Modules\Xot\Filament\Resources\Pages\XotBasePage` si verificava quando si tentava di sovrascrivere il metodo `getModel()` nella classe `XotBasePage`.

## Causa
La classe `XotBasePage` dichiarava il metodo `getModel()` come **statico**, ma la classe padre `Filament\Resources\Pages\Page` lo dichiara come **non statico**. In PHP non è possibile sovrascrivere un metodo non statico con uno statico.

## Analisi Tecnica
- **Classe padre**: `Filament\Resources\Pages\Page::getModel()` - metodo **non statico** che restituisce `string`
- **Classe figlia**: `XotBasePage::getModel()` - erroneamente dichiarato come **statico** con tipo `null|string`
- **Errore**: Violazione del principio di sovrascrittura dei metodi in PHP

## Soluzione Implementata

### 1. Correzione della Dichiarazione del Metodo
```php
// PRIMA (errato)
public static function getModel(): null|string
{
    return static::$model;
}

// DOPO (corretto)
public function getModel(): string
{
    if (static::$model === null) {
        throw new \LogicException('Model class not set for page: ' . static::class);
    }

    return static::$model;
}
```

### 2. Miglioramenti Implementati
- **Rimozione `static`**: Il metodo ora è correttamente non statico
- **Tipo di ritorno corretto**: Restituisce `string` invece di `null|string` per compatibilità con la classe padre
- **Gestione errori**: Lancia eccezione se `$model` non è impostato, invece di restituire `null`
- **Documentazione migliorata**: Spiegazione del motivo per cui il metodo deve essere non statico

## Impatto e Compatibilità

### Classi che Estendono XotBasePage
Verificate 24 classi che estendono `XotBasePage`:
- ✅ **Nessuna chiamata statica**: Nessuna classe utilizza `static::getModel()`
- ✅ **Compatibilità garantita**: Tutte le classi continueranno a funzionare
- ✅ **Nessuna modifica richiesta**: Le classi figlie non necessitano di modifiche

### Risorse Filament
Le Resources Filament utilizzano `static::getModel()` ma questo è diverso dal metodo delle Pages:
- ✅ **Separazione corretta**: Resources vs Pages hanno implementazioni separate
- ✅ **Nessun conflitto**: La correzione non impatta le Resources

## Architettura Laraxot

### Principio di Estensione
Questo fix rispetta il principio fondamentale di Laraxot:
> **MAI estendere classi Filament direttamente - sempre estendere classi XotBase**

### Benefici della Correzione
1. **Compatibilità PHP**: Rispetta le regole di sovrascrittura dei metodi
2. **Type Safety**: Tipo di ritorno corretto per PHPStan livello 9+
3. **Gestione Errori**: Eccezioni chiare invece di valori null inaspettati
4. **Manutenibilità**: Codice più robusto e prevedibile

## Test e Verifiche

### Controlli Eseguiti
- ✅ **Linting**: Nessun errore di sintassi
- ✅ **Rotte**: Verificate le rotte del modulo Progressioni
- ✅ **Compatibilità**: Nessuna classe figlia richiede modifiche
- ✅ **Architettura**: Rispetta i principi di Laraxot

### Metodologia di Test
- **Analisi statica**: Verifica della compatibilità con classi esistenti
- **Controllo dipendenze**: Verifica che nessuna classe utilizzi il metodo staticamente
- **Validazione architetturale**: Rispetto dei principi di estensione Laraxot

## Best Practice per il Futuro

### Quando Sovrascrivere Metodi Filament
1. **Verificare sempre la firma**: Controllare se il metodo padre è statico o non statico
2. **Mantenere compatibilità**: Il tipo di ritorno deve essere compatibile
3. **Documentare le eccezioni**: Spiegare perché si sovrascrive un metodo
4. **Testare l'impatto**: Verificare che le classi figlie continuino a funzionare

### Pattern di Estensione Corretto
```php
// ✅ CORRETTO - Verificare sempre la firma del metodo padre
public function getModel(): string
{
    // Implementazione specifica del modulo
}

// ❌ ERRATO - Non verificare la firma del metodo padre
public static function getModel(): null|string
{
    // Implementazione che viola le regole di sovrascrittura
}
```

## Collegamenti
- [XotBasePage](../app/Filament/Resources/Pages/XotBasePage.php)
- [Filament Page Documentation](https://filamentphp.com/docs/3.x/resources/pages)
- [Laraxot Extension Rules](../../../../docs/laraxot-conventions.md)

## Note di Manutenzione
- **Data correzione**: Gennaio 2025
- **Versione Filament**: 3.x
- **PHP Version**: 8.3+
- **Livello PHPStan**: 9+
---

## xotbaage-getmodel

*Consolidated from: `xotbaage-getmodel.md`*


## Problema
L'errore `Cannot make non static method Filament\Resources\Pages\Page::getModel() static in class Modules\Xot\Filament\Resources\Pages\XotBasePage` si verificava quando si tentava di sovrascrivere il metodo `getModel()` nella classe `XotBasePage`.

## Causa
La classe `XotBasePage` dichiarava il metodo `getModel()` come **statico**, ma la classe padre `Filament\Resources\Pages\Page` lo dichiara come **non statico**. In PHP non è possibile sovrascrivere un metodo non statico con uno statico.

## Analisi Tecnica
- **Classe padre**: `Filament\Resources\Pages\Page::getModel()` - metodo **non statico** che restituisce `string`
- **Classe figlia**: `XotBasePage::getModel()` - erroneamente dichiarato come **statico** con tipo `null|string`
- **Errore**: Violazione del principio di sovrascrittura dei metodi in PHP

## Soluzione Implementata

### 1. Correzione della Dichiarazione del Metodo
```php
// PRIMA (errato)
public static function getModel(): null|string
{
    return static::$model;
}

// DOPO (corretto)
public function getModel(): string
{
    if (static::$model === null) {
        throw new \LogicException('Model class not set for page: ' . static::class);
    }

    return static::$model;
}
```

### 2. Miglioramenti Implementati
- **Rimozione `static`**: Il metodo ora è correttamente non statico
- **Tipo di ritorno corretto**: Restituisce `string` invece di `null|string` per compatibilità con la classe padre
- **Gestione errori**: Lancia eccezione se `$model` non è impostato, invece di restituire `null`
- **Documentazione migliorata**: Spiegazione del motivo per cui il metodo deve essere non statico

## Impatto e Compatibilità

### Classi che Estendono XotBasePage
Verificate 24 classi che estendono `XotBasePage`:
- ✅ **Nessuna chiamata statica**: Nessuna classe utilizza `static::getModel()`
- ✅ **Compatibilità garantita**: Tutte le classi continueranno a funzionare
- ✅ **Nessuna modifica richiesta**: Le classi figlie non necessitano di modifiche

### Risorse Filament
Le Resources Filament utilizzano `static::getModel()` ma questo è diverso dal metodo delle Pages:
- ✅ **Separazione corretta**: Resources vs Pages hanno implementazioni separate
- ✅ **Nessun conflitto**: La correzione non impatta le Resources

## Architettura Laraxot

### Principio di Estensione
Questo fix rispetta il principio fondamentale di Laraxot:
> **MAI estendere classi Filament direttamente - sempre estendere classi XotBase**

### Benefici della Correzione
1. **Compatibilità PHP**: Rispetta le regole di sovrascrittura dei metodi
2. **Type Safety**: Tipo di ritorno corretto per PHPStan livello 9+
3. **Gestione Errori**: Eccezioni chiare invece di valori null inaspettati
4. **Manutenibilità**: Codice più robusto e prevedibile

## Test e Verifiche

### Controlli Eseguiti
- ✅ **Linting**: Nessun errore di sintassi
- ✅ **Rotte**: Verificate le rotte del modulo Progressioni
- ✅ **Compatibilità**: Nessuna classe figlia richiede modifiche
- ✅ **Architettura**: Rispetta i principi di Laraxot

### Metodologia di Test
- **Analisi statica**: Verifica della compatibilità con classi esistenti
- **Controllo dipendenze**: Verifica che nessuna classe utilizzi il metodo staticamente
- **Validazione architetturale**: Rispetto dei principi di estensione Laraxot

## Best Practice per il Futuro

### Quando Sovrascrivere Metodi Filament
1. **Verificare sempre la firma**: Controllare se il metodo padre è statico o non statico
2. **Mantenere compatibilità**: Il tipo di ritorno deve essere compatibile
3. **Documentare le eccezioni**: Spiegare perché si sovrascrive un metodo
4. **Testare l'impatto**: Verificare che le classi figlie continuino a funzionare

### Pattern di Estensione Corretto
```php
// ✅ CORRETTO - Verificare sempre la firma del metodo padre
public function getModel(): string
{
    // Implementazione specifica del modulo
}

// ❌ ERRATO - Non verificare la firma del metodo padre
public static function getModel(): null|string
{
    // Implementazione che viola le regole di sovrascrittura
}
```

## Collegamenti
- [XotBasePage](../app/Filament/Resources/Pages/XotBasePage.php)
- [Filament Page Documentation](https://filamentphp.com/docs/3.x/resources/pages)
- [Laraxot Extension Rules](../../../../docs/laraxot-conventions.md)

## Note di Manutenzione
- **Data correzione**: Gennaio 2025
- **Versione Filament**: 3.x
- **PHP Version**: 8.3+
- **Livello PHPStan**: 9+
---

## xotbaage-implementation

*Consolidated from: `xotbaage-implementation.md`*


## descrizione
la classe `XotBasePage` è una classe base astratta per tutte le pagine filament non collegate a risorse specifiche. fornisce funzionalità comuni come gestione delle traduzioni, integrazione con il sistema di autorizzazioni e utilità per l'accesso ai dati.

## struttura
la classe `XotBasePage` estende `Filament\Pages\Page` e si trova in:
```
Modules/Xot/app/Filament/Pages/XotBasePage.php
```

## namespace
```php
namespace Modules\Xot\Filament\Pages;
```

## utilizzo corretto

```php
// nel modulo esempio
namespace Modules\Example\Filament\Pages;

use Modules\Xot\Filament\Pages\XotBasePage;

class SettingsPage extends XotBasePage
{
    // implementazione...
}
```

## funzionalità principali

1. **sistema di traduzioni integrato**
   - localizzazione automatica basata sul modulo
   - generazione di chiavi di traduzione standardizzate

2. **gestione autorizzazioni**
   - integrazione con policy e autorizzazioni

3. **integrazione con form**
   - gestione form standardizzata
   - supporto per validazione

4. **rilevamento intelligente modello**
   - rilevamento automatico del modello associato
   - gestione centralizzata dell'entità associata

## best practices

### 1. traduzioni
- non usare mai stringhe hardcoded per le etichette
- utilizzare il metodo `trans()` o il trait `TransTrait`
- organizzare le traduzioni nei file del modulo (`/Modules/NomeModulo/lang/`)

### 2. override di metodi
- implementare `getFormSchema()` per definire la struttura del form SOLO nelle classi figlie che ne hanno bisogno
- NON dichiarare mai abstract getFormSchema() in XotBasePage
- non sovrascrivere metodi dichiarati come `final`
- estendere i metodi hook dove possibile

### 3. viste
- utilizzare viste nel modulo specifico
- preferire component blade riutilizzabili

### 4. performance
- evitare query n+1 utilizzando eager loading
- minimizzare il caricamento di risorse non necessarie

## esempio completo

```php
namespace Modules\<nome progetto>\Filament\Pages;

use Modules\Xot\Filament\Pages\XotBasePage;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class DashboardSettings extends XotBasePage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $view = '<nome progetto>::filament.pages.dashboard-settings';

    protected function getFormFields(): array
    {
        return [
            'title' => [
                'type' => TextInput::class,
                'label' => true,
                'tooltip' => true,
                'placeholder' => true,
                'required' => true,
            ],
            'refresh_interval' => [
                'type' => Select::class,
                'label' => true,
                'tooltip' => true,
                'options' => [
                    '30' => '30 secondi',
                    '60' => '1 minuto',
                    '300' => '5 minuti',
                ]
            ]
        ];
    }

    public function submit(): void
    {
        $this->form->validate();
        // logica di salvataggio
    }

    public function authorize(): bool
    {
        return auth()->user()->can('view', static::class);
    }
}
```

## traduzioni dei campi

Le traduzioni dei campi del form devono essere definite nei file di traduzione del modulo seguendo questa struttura:

```php
// /Modules/NomeModulo/lang/it/fields.php
return [
    'title' => [
        'label' => 'Titolo',
        'tooltip' => 'Inserisci il titolo della dashboard',
        'placeholder' => 'Es. Dashboard Principale',
    ],
    'refresh_interval' => [
        'label' => 'Intervallo di aggiornamento',
        'tooltip' => 'Seleziona ogni quanto aggiornare i dati',
        'placeholder' => 'Seleziona un intervallo',
    ],
];
```

## autorizzazioni

Per implementare le autorizzazioni, è necessario:

1. Definire una policy per la pagina
2. Implementare il metodo `authorize()` nella classe della pagina
3. Registrare la policy nel service provider del modulo

```php
// /Modules/NomeModulo/Policies/DashboardSettingsPolicy.php
namespace Modules\NomeModulo\Policies;

use App\Models\User;

class DashboardSettingsPolicy
{
    public function view(User $user): bool
    {
        return $user->hasRole('admin');
    }
}
```

## considerazioni di sicurezza
- validare sempre gli input dell'utente
- utilizzare `authorizeAccess()` per controllare gli accessi
- seguire il principio del privilegio minimo

## pattern comuni
- pagine di impostazioni
- dashboard specializzate
- pagine di reportistica
- wizard personalizzati

## collegamento ad altre documentazioni
- [pattern di estensione filament](../xot/docs/filament_extension_pattern.md)
- [best practices filament](../<nome progetto>/docs/filament-best-practices.md)

## ATTENZIONE: errori critici da evitare
- NON dichiarare mai abstract getFormSchema() in XotBasePage: la classe base Filament lo implementa già. Fornire sempre una implementazione di default (array vuoto).
- Se serve uno schema custom, sovrascrivere il metodo nella classe figlia.

---

## xotbaage

*Consolidated from: `xotbaage.md`*


## Descrizione

La classe `XotBasePage` rappresenta un componente fondamentale nell'architettura di <nome progetto>, fungendo da intermediario tra le pagine Filament e le implementazioni specifiche dell'applicazione. Questa classe astratta segue il pattern architetturale di non estendere mai direttamente le classi di Filament, ma utilizzare sempre classi wrapper con prefisso `XotBase`.

## Percorso del File

```
Modules/Xot/app/Filament/Resources/Pages/XotBasePage.php
```

## Gerarchia di Ereditarietà

```
Filament\Pages\Page
    ↑
    └── Modules\Xot\Filament\Resources\Pages\XotBasePage
        ↑
        └── Modulo specifico\Filament\Pages\YourCustomPage
```

## Trait e Interfacce

- Implementa `HasForms` per la gestione dei moduli
- Utilizza `InteractsWithForms` per l'interazione con i form
- Utilizza `NavigationLabelTrait` per la gestione delle etichette di navigazione
- Utilizza `TransTrait` per le funzionalità di traduzione

## Funzionalità Principali

### Gestione Automatica delle Traduzioni

```php
public static function getNavigationLabel(): string
{
    return static::transFunc(__FUNCTION__);
}

public function getTitle(): string
{
    return static::transTitle();
}
```

### Form Standardizzato

```php
public function form(Form $form): Form
{
    return $form
        ->schema($this->getFormSchema())
        ->statePath('data');
}
```

### Proprietà Principali

| Proprietà | Tipo | Descrizione |
|-----------|------|-------------|
| `$model` | `?string` | Classe del modello associato alla pagina |
| `$data` | `?array` | Dati del form |
| `$navigationIcon` | `?string` | Icona di navigazione predefinita |

## Utilizzo Corretto

### Estensione Corretta

```php
namespace Modules\<nome progetto>\Filament\Pages;

use Modules\Xot\Filament\Resources\Pages\XotBasePage;

class MyCustomPage extends XotBasePage
{
    protected static ?string $navigationIcon = 'heroicon-o-document';

    protected function getFormSchema(): array
    {
        return [
            // Schema del form
        ];
    }
}
```

### Estensione Errata da Evitare

```php
// ❌ ERRORE: Non estendere mai direttamente Page
namespace Modules\<nome progetto>\Filament\Pages;

use Filament\Pages\Page;

class MyCustomPage extends Page // ⚠️ ERRATO!
{
    // ...
}
```

## Vantaggi dell'Utilizzo

1. **Uniformità del Codice**: Comportamento coerente in tutti i moduli
2. **Traduzione Automatica**: Gestione centralizzata delle traduzioni
3. **Gestione Form Semplificata**: Pattern standardizzato per i form
4. **Separazione delle Responsabilità**: Layer di astrazione tra l'applicazione e il framework

## Best Practices

1. **Non Ridichiarare Interfacce**: Non ridichiarare `HasForms` o altri trait già presenti in XotBasePage
2. **Implementare getFormSchema()**: Sempre fornire un'implementazione di questo metodo
3. **Rispettare il Namespace**: Utilizzare `Modules\<nome modulo>\Filament\Pages` per le classi che estendono XotBasePage
4. **Utilizzare le Traduzioni**: Sfruttare il sistema di traduzione automatico invece di hardcodare le etichette

## ⚠️ ERRORI GRAVI DA EVITARE

### Duplicazione di Trait e Interfacce

**❌ ERRORE GRAVE**: Non ridichiarare mai trait e interfacce già presenti in `XotBasePage`

```php
// ❌ ERRORE GRAVE: Ridichiarazione di trait/interfacce
class MyPage extends XotBasePage implements HasForms  // ⚠️ ERRATO!
{
    use InteractsWithForms;  // ⚠️ ERRATO!

    // ...
}
```

**✅ CORRETTO**: Estendere semplicemente `XotBasePage` senza ridichiarazioni

```php
// ✅ CORRETTO: Estensione pulita
class MyPage extends XotBasePage
{
    // Nessuna ridichiarazione di trait/interfacce già presenti

    protected function getFormSchema(): array
    {
        return [
            // Schema del form
        ];
    }
}
```

### Perché È un Errore Grave

1. **Violazione del Principio DRY**: Duplicazione di codice già presente
2. **Conflitti di Trait**: Può causare errori runtime difficili da debuggare
3. **Manutenibilità**: Rende il codice più difficile da mantenere
4. **Performance**: Caricamento doppio degli stessi trait
5. **Inconsistenza**: Comportamento non prevedibile tra diverse pagine

### Cosa Fornisce Già XotBasePage

`XotBasePage` implementa già:
- `HasForms` interface
- `InteractsWithForms` trait
- `NavigationLabelTrait` trait
- `TransTrait` trait
- `InteractsWithFormActions` trait

**NON ridichiarare mai questi elementi nelle classi che estendono XotBasePage.**
## Compatibilità con Filament

La classe è progettata per essere compatibile con Filament v3+ e garantisce il corretto funzionamento di tutte le funzionalità native di Filament\Pages\Page.

## Collegamenti

- [Documentazione di Filament](https://filamentphp.com/project_docs/3.x/panels/pages)
- [Pattern di Estensione](modules/xot/project_docs/filament/filament_best_practices.md)
- [Principi di Ereditarietà](modules/xot/project_docs/class_inheritance_principles.md)
- [Architettura Filament-Xot](modules/xot/project_docs/filament_xot_architecture.md)

---

## xotbaanelprovider-refactoring

*Consolidated from: `xotbaanelprovider-refactoring.md`*



---

## xotbaanelprovider

*Consolidated from: `xotbaanelprovider.md`*


---

## xotbase-extension-rules-conflict

*Consolidated from: `xotbase-extension-rules-conflict.md`*


## 🚨 Critical Architectural Rule

**NEVER extend Filament classes directly. ALWAYS extend the corresponding XotBase abstract class.**

## 📋 Extension Pattern Table

| Filament Original Class | XotBase Class to Extend |
|-------------------------|-------------------------|
| `Filament\Resources\Resource` | `Modules\Xot\Filament\Resources\XotBaseResource` |
| `Filament\Resources\Pages\Page` | `Modules\Xot\Filament\Resources\Pages\XotBasePage` |
| `Filament\Resources\Pages\ListRecords` | `Modules\Xot\Filament\Resources\Pages\XotBaseListRecords` |
| `Filament\Resources\Pages\CreateRecord` | `Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord` |
| `Filament\Resources\Pages\EditRecord` | `Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord` |
| `Filament\Resources\Pages\ViewRecord` | `Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord` |
| `Filament\Widgets\Widget` | `Modules\Xot\Filament\Widgets\XotBaseWidget` |
| `Filament\Resources\RelationManagers\RelationManager` | `Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager` |

## ✅ Correct Implementation Examples

### Resource Example
```php
// CORRECT: Extend XotBaseResource
namespace Modules\MyModule\Filament\Resources;

use Modules\Xot\Filament\Resources\XotBaseResource;

class MyResource extends XotBaseResource
{
    // Implementation here
}

// WRONG: Direct Filament extension
class MyResource extends \Filament\Resources\Resource
{
    // This will cause architecture violations
}
```

### Widget Example
```php
// CORRECT: Extend XotBaseWidget
namespace Modules\MyModule\Filament\Widgets;

use Modules\Xot\Filament\Widgets\XotBaseWidget;

class MyWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [
            // Form components here
        ];
    }
}

// WRONG: Direct Filament extension
class MyWidget extends \Filament\Widgets\Widget
{
    // Missing required methods and architecture violations
}
```

## ⚠️ Common Errors and Solutions

### Error: "Class contains 1 abstract method and must therefore be declared abstract"
**Cause**: Extending XotBaseWidget without implementing required abstract methods like `getFormSchema()`
**Solution**: Always implement ALL abstract methods from XotBase classes

### Error: "Access level must be public (as in class XotBaseWidget)"
**Cause**: Using `protected` instead of `public` for methods that are `public` in parent class
**Solution**: Match the exact access level from the parent abstract class

### Error: "Cannot override final method"
**Cause**: Trying to override methods marked as `final` in XotBase classes
**Solution**: Use the provided hook methods instead of overriding final methods

## 🔧 Required Method Implementations

### For XotBaseWidget
```php
public function getFormSchema(): array
{
    return [
        // Must return array of Filament form components
        // NEVER return empty array []
        \Filament\Forms\Components\TextInput::make('field_name')
            ->label(__('module::translation.key'))
            ->required(),
    ];
}
```

### For XotBaseResource
```php
// XotBaseResource provides default implementations
// Override only when necessary using the correct patterns
```

## 📁 Namespace Structure Rules

1. **Maintain Filament's namespace structure** but within your module
2. **Never include 'app' in namespace** for Filament components
3. **Use correct translation patterns** with module prefix

**Correct:**
```php
namespace Modules\MyModule\Filament\Resources;
namespace Modules\MyModule\Filament\Widgets;
```

**Wrong:**
```php
namespace Modules\MyModule\App\Filament\Resources; // Contains 'App'
namespace Modules\MyModule\Filament\App\Widgets;   // Wrong structure
```

## 🛡️ Validation Checklist

Before committing any Filament-related code, verify:

1. [ ] Extends XotBase class, not direct Filament class
2. [ ] All abstract methods are implemented with correct signatures
3. [ ] Method access levels match parent class (public/protected)
4. [ ] Namespace follows correct pattern without 'app' segment
5. [ ] No final methods are being overridden
6. [ ] Form schemas return proper Filament components, not empty arrays
7. [ ] Translation keys use module prefix (module::key.path)

## 🔍 Common Pitfalls

### Empty Form Schemas
**Wrong:**
```php
public function getFormSchema(): array
{
    return []; // NEVER return empty array
}
```

**Correct:**
```php
public function getFormSchema(): array
{
    return [
        \Filament\Forms\Components\TextInput::make('name')
            ->label(__('module::fields.name')),
    ];
}
```

### Wrong Access Levels
**Wrong:**
```php
protected function getFormSchema(): array // Should be public
{
    return [/*...*/];
}
```

**Correct:**
```php
public function getFormSchema(): array // Must be public
{
    return [/*...*/];
}
```

## 📚 Related Documentation

- [Filament Extension Pattern](../filament_extension_pattern.md)
- [XotBaseWidget Documentation](./filament/widgets/xot-base-widget.md)
- [Namespace Rules](../namespace-rules.md)
- [Architecture Best Practices](../architecture-best-practices.md)

## 🚨 Emergency Fix Procedure

If you encounter architecture violations:

1. **Identify the incorrectly extended class**
2. **Change extends to correct XotBase class**
3. **Implement all required abstract methods**
4. **Verify method signatures match parent**
5. **Run PHPStan to validate fixes**

## 🔗 Integration with Development Workflow

This rule is enforced by:
- PHPStan architecture rules
- Code review processes
- Automated quality checks

Always run `php artisan optimize:clear && ./vendor/bin/phpstan analyse` after making changes to verify compliance.

---

*Last Updated: 2025-08-27*
*Architecture Version: XotBase 2.0*

---

## xotbase-extension-rules-variant

*Consolidated from: `xotbase-extension-rules-variant.md`*


---

## xotbase-extension-rules-xotbase-extension-rules-comprehensive

*Consolidated from: `xotbase-extension-rules-xotbase-extension-rules-comprehensive.md`*


## 🚨 Critical Architectural Rule

**NEVER extend Filament classes directly. ALWAYS extend the corresponding XotBase abstract class.**

## 📋 Extension Pattern Table

| Filament Original Class | XotBase Class to Extend |
|-------------------------|-------------------------|
| `Filament\Resources\Resource` | `Modules\Xot\Filament\Resources\XotBaseResource` |
| `Filament\Resources\Pages\Page` | `Modules\Xot\Filament\Resources\Pages\XotBasePage` |
| `Filament\Resources\Pages\ListRecords` | `Modules\Xot\Filament\Resources\Pages\XotBaseListRecords` |
| `Filament\Resources\Pages\CreateRecord` | `Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord` |
| `Filament\Resources\Pages\EditRecord` | `Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord` |
| `Filament\Resources\Pages\ViewRecord` | `Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord` |
| `Filament\Widgets\Widget` | `Modules\Xot\Filament\Widgets\XotBaseWidget` |
| `Filament\Resources\RelationManagers\RelationManager` | `Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager` |

## ✅ Correct Implementation Examples

### Resource Example
```php
// CORRECT: Extend XotBaseResource
namespace Modules\MyModule\Filament\Resources;

use Modules\Xot\Filament\Resources\XotBaseResource;

class MyResource extends XotBaseResource
{
    // Implementation here
}

// WRONG: Direct Filament extension
class MyResource extends \Filament\Resources\Resource
{
    // This will cause architecture violations
}
```

### Widget Example
```php
// CORRECT: Extend XotBaseWidget
namespace Modules\MyModule\Filament\Widgets;

use Modules\Xot\Filament\Widgets\XotBaseWidget;

class MyWidget extends XotBaseWidget
{
    public function getFormSchema(): array
    {
        return [
            // Form components here
        ];
    }
}

// WRONG: Direct Filament extension
class MyWidget extends \Filament\Widgets\Widget
{
    // Missing required methods and architecture violations
}
```

## ⚠️ Common Errors and Solutions

### Error: "Class contains 1 abstract method and must therefore be declared abstract"
**Cause**: Extending XotBaseWidget without implementing required abstract methods like `getFormSchema()`
**Solution**: Always implement ALL abstract methods from XotBase classes

### Error: "Access level must be public (as in class XotBaseWidget)"
**Cause**: Using `protected` instead of `public` for methods that are `public` in parent class
**Solution**: Match the exact access level from the parent abstract class

### Error: "Cannot override final method"
**Cause**: Trying to override methods marked as `final` in XotBase classes
**Solution**: Use the provided hook methods instead of overriding final methods

## 🔧 Required Method Implementations

### For XotBaseWidget
```php
public function getFormSchema(): array
{
    return [
        // Must return array of Filament form components
        // NEVER return empty array []
        \Filament\Forms\Components\TextInput::make('field_name')
            ->label(__('module::translation.key'))
            ->required(),
    ];
}
```

### For XotBaseResource
```php
// XotBaseResource provides default implementations
// Override only when necessary using the correct patterns
```

## 📁 Namespace Structure Rules

1. **Maintain Filament's namespace structure** but within your module
2. **Never include 'app' in namespace** for Filament components
3. **Use correct translation patterns** with module prefix

**Correct:**
```php
namespace Modules\MyModule\Filament\Resources;
namespace Modules\MyModule\Filament\Widgets;
```

**Wrong:**
```php
namespace Modules\MyModule\App\Filament\Resources; // Contains 'App'
namespace Modules\MyModule\Filament\App\Widgets;   // Wrong structure
```

## 🛡️ Validation Checklist

Before committing any Filament-related code, verify:

1. [ ] Extends XotBase class, not direct Filament class
2. [ ] All abstract methods are implemented with correct signatures
3. [ ] Method access levels match parent class (public/protected)
4. [ ] Namespace follows correct pattern without 'app' segment
5. [ ] No final methods are being overridden
6. [ ] Form schemas return proper Filament components, not empty arrays
7. [ ] Translation keys use module prefix (module::key.path)
8. [ ] **Array methods return `array<string, mixed>`** - Never use numeric keys for:
   - `getTableColumns()` → `array<string, Column>`
   - `getFormSchema()` → `array<string, Component>`
   - `getTableActions()` → `array<string, Action>`
   - `getTableBulkActions()` → `array<string, BulkAction>`
   - `getTableFilters()` → `array<string, Filter>`
   - `getHeaderActions()` → `array<string, Action>`
9. [ ] **Never use `property_exists()` with Eloquent models** - Use `isset()` for magic attributes
10. [ ] **Avoid `mixed` types** - Use specific types or `array<string, mixed>` when necessary

## 🔍 Common Pitfalls

### Empty Form Schemas
**Wrong:**
```php
public function getFormSchema(): array
{
    return []; // NEVER return empty array
}
```

**Correct:**
```php
public function getFormSchema(): array
{
    return [
        'name' => \Filament\Forms\Components\TextInput::make('name')
            ->label(__('module::fields.name')),
    ];
}
```

### Array with Numeric Keys
**Wrong:**
```php
public function getTableActions(): array
{
    return [
        EditAction::make(),
        DeleteAction::make(),
    ]; // ❌ Numeric keys (0, 1)
}
```

**Correct:**
```php
/**
 * @return array<string, Action>
 */
public function getTableActions(): array
{
    return [
        'edit' => EditAction::make(),
        'delete' => DeleteAction::make(),
    ]; // ✅ String keys
}
```

### Property Exists with Models
**Wrong:**
```php
if (property_exists($model, 'attribute')) {
    $value = $model->attribute; // ❌ Doesn't work with magic attributes
}
```

**Correct:**
```php
if (isset($model->attribute)) {
    $value = $model->attribute; // ✅ Works with magic attributes
}
```

### Wrong Access Levels
**Wrong:**
```php
protected function getFormSchema(): array // Should be public
{
    return [/*...*/];
}
```

**Correct:**
```php
public function getFormSchema(): array // Must be public
{
    return [/*...*/];
}
```

## 📚 Related Documentation

- [Filament Extension Pattern](../filament_extension_pattern.md)
- [XotBaseWidget Documentation](./filament/widgets/xot-base-widget.md)
- [Namespace Rules](../namespace-rules.md)
- [Architecture Best Practices](../architecture-best-practices.md)

## 🚨 Emergency Fix Procedure

If you encounter architecture violations:

1. **Identify the incorrectly extended class**
2. **Change extends to correct XotBase class**
3. **Implement all required abstract methods**
4. **Verify method signatures match parent**
5. **Run PHPStan to validate fixes**

## 🔗 Integration with Development Workflow

This rule is enforced by:
- PHPStan architecture rules
- Code review processes
- Automated quality checks

Always run `php artisan optimize:clear && ./vendor/bin/phpstan analyse` after making changes to verify compliance.

---

*Last Updated: 2025-01-10*
*Architecture Version: XotBase 2.1*

---

## xotbase-extension-rules

*Consolidated from: `xotbase-extension-rules.md`*


## 🚨 REGOLA CRITICA FONDAMENTALE

**MAI ESTENDERE CLASSI FILAMENT DIRETTAMENTE - SEMPRE USARE XOTBASE**

Questa è la regola più importante dell'architettura Laraxot/PTVX e ha **PRIORITÀ ASSOLUTA** su qualsiasi altra considerazione.

## ❌ Cosa NON Fare

```php
// VIETATO - Estensione diretta di classi Filament
class Dashboard extends Filament\Pages\Dashboard
class EmployeeResource extends Filament\Resources\Resource
class StatsWidget extends Filament\Widgets\Widget
class CustomPage extends Filament\Pages\Page
class PanelProvider extends Filament\Panel
```

## ✅ Cosa Fare SEMPRE

```php
// OBBLIGATORIO - Estensione di classi XotBase
class Dashboard extends Modules\Xot\Filament\Pages\XotBaseDashboard
class EmployeeResource extends Modules\Xot\Filament\Resources\XotBaseResource
class StatsWidget extends Modules\Xot\Filament\Widgets\XotBaseWidget
class CustomPage extends Modules\Xot\Filament\Pages\XotBasePage
class AdminPanelProvider extends Modules\Xot\Providers\Filament\XotBasePanelProvider
```

## 📋 Mapping Completo delle Classi

| Filament Originale | XotBase Corrispondente | Utilizzo |
|-------------------|------------------------|----------|
| `Filament\Pages\Dashboard` | `Modules\Xot\Filament\Pages\XotBaseDashboard` | Dashboard moduli |
| `Filament\Resources\Resource` | `Modules\Xot\Filament\Resources\XotBaseResource` | Risorse CRUD |
| `Filament\Widgets\Widget` | `Modules\Xot\Filament\Widgets\XotBaseWidget` | Widget dashboard |
| `Filament\Pages\Page` | `Modules\Xot\Filament\Pages\XotBasePage` | Pagine custom |
| `Filament\Panel` | `Modules\Xot\Providers\Filament\XotBasePanelProvider` | Panel provider |

## 🎯 Motivazioni della Regola

### 1. **Funzionalità Aggiuntive**
Le classi XotBase forniscono funzionalità specifiche del progetto Laraxot/PTVX che non sono disponibili nelle classi Filament standard.

### 2. **Consistenza Architetturale**
Garantisce che tutti i moduli seguano lo stesso pattern architetturale, facilitando manutenzione e sviluppo.

### 3. **Modifiche Centralizzate**
Permette di applicare modifiche a tutti i moduli modificando solo le classi XotBase, senza toccare ogni singolo modulo.

### 4. **Integrazione Sistema**
Le classi XotBase sono integrate con il sistema di configurazione, traduzioni e funzionalità specifiche del progetto.

## 🔍 Come Verificare la Conformità

### Ricerca Violazioni
```bash
# Cerca estensioni dirette di Filament (dovrebbe restituire 0 risultati)
grep -r "extends Filament\\" Modules/ --include="*.php"

# Cerca estensioni corrette XotBase
grep -r "extends Modules\\Xot\\" Modules/ --include="*.php"
```

### Verifica Specifica per Tipo
```bash
# Dashboard
grep -r "XotBaseDashboard" Modules/ --include="*.php"

# Resources
grep -r "XotBaseResource" Modules/ --include="*.php"

# Widgets
grep -r "XotBaseWidget" Modules/ --include="*.php"
```

## 📝 Esempi Pratici

### Dashboard Modulo
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Pages;

use Modules\Xot\Filament\Pages\XotBaseDashboard;

/**
 * Dashboard per il modulo Employee.
 *
 * Estende XotBaseDashboard seguendo la regola architettturale fondamentale
 * di non estendere mai classi Filament direttamente.
 */
class Dashboard extends XotBaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $title = 'Employee Dashboard';
    protected static ?string $navigationLabel = 'Employee';
    protected static ?int $navigationSort = 1;
}
```

### Resource Modulo
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Resources;

use Modules\Xot\Filament\Resources\XotBaseResource;

class EmployeeResource extends XotBaseResource
{
    protected static ?string $model = Employee::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';

    // Implementazione specifica del resource...
}
```

### Widget Modulo
```php
<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Widgets;

use Modules\Xot\Filament\Widgets\XotBaseWidget;

class EmployeeStatsWidget extends XotBaseWidget
{
    protected static string $view = 'employee::filament.widgets.stats';

    // Implementazione specifica del widget...
}
```

## 🚨 Controlli di Qualità

### Pre-commit Hook
Aggiungere un controllo pre-commit per verificare che non ci siano estensioni dirette di Filament:

```bash
#!/bin/bash
# .git/hooks/pre-commit

if grep -r "extends Filament\\" Modules/ --include="*.php" > /dev/null; then
    echo "❌ ERRORE: Trovate estensioni dirette di classi Filament!"
    echo "Usa sempre le classi XotBase invece."
    exit 1
fi

echo "✅ Controllo XotBase: PASSED"
```

### CI/CD Check
```yaml
# .github/workflows/xotbase-check.yml
name: XotBase Extension Check
on: [push, pull_request]
jobs:
  check-xotbase:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Check XotBase Extensions
        run: |
          if grep -r "extends Filament\\" Modules/ --include="*.php"; then
            echo "❌ Direct Filament extensions found!"
            exit 1
          fi
          echo "✅ All extensions use XotBase pattern"
```

## 📚 Documentazione Correlata

- [Architecture Best Practices](./architecture_best_practices.md)
- [Filament Integration Guide](./filament_integration.md)
- [Module Development Standards](./module_development_standards.md)

## ⚠️ Nota Importante

**Questa regola non ammette eccezioni.** Ogni violazione deve essere corretta immediatamente. In caso di dubbi, consultare sempre la documentazione o chiedere conferma prima di procedere.

---

*Documento aggiornato: 2025-07-30*
*Priorità: CRITICA*
*Stato: OBBLIGATORIO per tutti i moduli*


---

## xotbase-extension

*Consolidated from: `xotbase-extension.md`*

module: theme
topic: xotbase-extension
canonical: ../../../Themes/docs/shared-components/xotbase-extension-rules-conflict.md
---

See canonical documentation: ../../../Themes/docs/shared-components/xotbase-extension-rules-conflict.md
---

## xotbase-page-business-logic

*Consolidated from: `xotbase-page-business-logic.md`*


---

## xotbase-quick-reference-conflict

*Consolidated from: `xotbase-quick-reference-conflict.md`*


## ⚡ Immediate Action Required

**NEVER DO THIS:** ❌
```php
class MyResource extends \Filament\Resources\Resource
class MyWidget extends \Filament\Widgets\Widget
```

**ALWAYS DO THIS:** ✅
```php
class MyResource extends \Modules\Xot\Filament\Resources\XotBaseResource
class MyWidget extends \Modules\Xot\Filament\Widgets\XotBaseWidget
```

## 🔧 Critical Methods to Implement

### For XotBaseWidget
```php
public function getFormSchema(): array
{
    return [
        // REQUIRED: Form components here
        // NEVER return empty array []
    ];
}
```

### Method must be PUBLIC (not protected)

## 📁 Correct Namespace Structure

```php
namespace Modules\YourModule\Filament\Resources;      // ✅ Correct
namespace Modules\YourModule\Filament\Widgets;       // ✅ Correct

namespace Modules\YourModule\App\Filament\Resources; // ❌ Wrong (contains App)
```

## 🚨 Common Error Fixes

### Error: "contains 1 abstract method"
**Fix:** Implement `getFormSchema()` method returning form components

### Error: "Access level must be public"
**Fix:** Change `protected` to `public` for the method

### Error: "Cannot override final method"
**Fix:** Use hook methods instead of overriding

## 📞 Emergency Help

1. Check: `/Modules/Xot/docs/XOTBASE_EXTENSION_RULES.md`
2. Check: `/Modules/Xot/docs/filament_extension_pattern.md`
3. Run: `php artisan optimize:clear && ./vendor/bin/phpstan analyse`

---

*Keep this file visible during development!*

---

## xotbase-quick-reference-variant

*Consolidated from: `xotbase-quick-reference-variant.md`*


---

## xotbase-quick-reference

*Consolidated from: `xotbase-quick-reference.md`*


## ⚡ Immediate Action Required

**NEVER DO THIS:** ❌
```php
class MyResource extends \Filament\Resources\Resource
class MyWidget extends \Filament\Widgets\Widget
```

**ALWAYS DO THIS:** ✅
```php
class MyResource extends \Modules\Xot\Filament\Resources\XotBaseResource
class MyWidget extends \Modules\Xot\Filament\Widgets\XotBaseWidget
```

## 🔧 Critical Methods to Implement

### For XotBaseWidget
```php
public function getFormSchema(): array
{
    return [
        // REQUIRED: Form components here
        // NEVER return empty array []
    ];
}
```

### Method must be PUBLIC (not protected)

## 📁 Correct Namespace Structure

```php
namespace Modules\YourModule\Filament\Resources;      // ✅ Correct
namespace Modules\YourModule\Filament\Widgets;       // ✅ Correct

namespace Modules\YourModule\App\Filament\Resources; // ❌ Wrong (contains App)
```

## 🚨 Common Error Fixes

### Error: "contains 1 abstract method"
**Fix:** Implement `getFormSchema()` method returning form components

### Error: "Access level must be public"
**Fix:** Change `protected` to `public` for the method

### Error: "Cannot override final method"
**Fix:** Use hook methods instead of overriding

## 📞 Emergency Help

1. Check: `/Modules/Xot/project_docs/XOTBASE_EXTENSION_RULES.md`
2. Check: `/Modules/Xot/project_docs/filament_extension_pattern.md`
3. Run: `php artisan optimize:clear && ./vendor/bin/phpstan analyse`

---

*Keep this file visible during development!*


---

## xotbase-stats-overview-widget-examples

*Consolidated from: `xotbase-stats-overview-widget-examples.md`*


---

## xotbase-stats-overview-widget-improvements

*Consolidated from: `xotbase-stats-overview-widget-improvements.md`*


---

## xotbase-stats-overview-widget

*Consolidated from: `xotbase-stats-overview-widget.md`*


## Panoramica

`XotBaseStatsOverviewWidget` è la classe base per tutti i widget di statistiche overview nel sistema Xot. Estende `Filament\Widgets\StatsOverviewWidget` e fornisce funzionalità comuni e metodi helper avanzati per la creazione di statistiche.

## Caratteristiche

### Estensione Base
```php
use Modules\Xot\Filament\Widgets\XotBaseStatsOverviewWidget;

class MyStatsWidget extends XotBaseStatsOverviewWidget
{
    // Implementazione...
}
```

### Proprietà Predefinite
- `$pollingInterval = '5m'` - Aggiornamento automatico ogni 5 minuti
- `$isLazy = true` - Caricamento lazy per ottimizzare le performance
- `$sort = 1` - Ordinamento nella dashboard
- `$defaultCacheTtl = 300` - TTL predefinito per la cache (5 minuti)

### Trait Inclusi
- `TransTrait` - Per la gestione delle traduzioni

## Metodi Principali

### getStats()
```php
protected function getStats(): array
{
    return [
        $this->createStat('Utenti Totali', '1,234'),
        $this->createStatWithCalculatedTrend('Nuovi Utenti', 56, 45),
        $this->createStatFromQuery('Articoli', 'articles'),
        $this->createStatWithUrl('Vedi Tutti', '1,234', '/admin/users'),
    ];
}
```

## Metodi Helper Base

### createStat()
Crea una statistica standard:
```php
protected function createStat(
    string $label,
    string $value,
    string $description = '',
    string $icon = 'heroicon-m-information-circle',
    string $color = 'info'
): Stat
```

### createStatWithTrend()
Crea una statistica con trend:
```php
protected function createStatWithTrend(
    string $label,
    string $value,
    string $trend,
    string $trendIcon = 'heroicon-m-arrow-trending-up',
    string $trendColor = 'success',
    string $icon = 'heroicon-m-information-circle',
    string $color = 'info'
): Stat
```

### createStatWithChart()
Crea una statistica con grafico:
```php
protected function createStatWithChart(
    string $label,
    string $value,
    array $chartData,
    string $description = '',
    string $icon = 'heroicon-m-information-circle',
    string $color = 'info'
): Stat
```

## Metodi Helper Avanzati

### Calcolo Trend Automatico

#### calculateTrend()
Calcola automaticamente il trend percentuale:
```php
protected function calculateTrend(
    float $currentValue,
    float $previousValue,
    int $decimals = 1
): array
```

**Risultato**:
```php
[
    'trend' => '+12.5%',
    'icon' => 'heroicon-m-arrow-trending-up',
    'color' => 'success'
]
```

#### createStatWithCalculatedTrend()
Crea statistica con trend calcolato automaticamente:
```php
protected function createStatWithCalculatedTrend(
    string $label,
    float $currentValue,
    float $previousValue,
    string $icon = 'heroicon-m-information-circle',
    string $color = 'info',
    int $decimals = 1
): Stat
```

### Formattazione Numeri

#### formatNumber()
Formatta numeri con separatori delle migliaia:
```php
protected function formatNumber(float|int $number, int $decimals = 0): string
// Esempio: 1234.56 -> "1.234,56"
```

#### formatPercentage()
Formatta numeri come percentuali:
```php
protected function formatPercentage(float $number, int $decimals = 1): string
// Esempio: 0.15 -> "15.0%"
```

#### formatCurrency()
Formatta numeri come valuta:
```php
protected function formatCurrency(float $amount, string $currency = '€', int $decimals = 2): string
// Esempio: 1234.56 -> "1.234,56 €"
```

### Statistiche Interattive

#### createStatWithUrl()
Crea statistica con URL di navigazione:
```php
protected function createStatWithUrl(
    string $label,
    string $value,
    string $url,
    string $description = '',
    string $icon = 'heroicon-m-information-circle',
    string $color = 'info'
): Stat
```

#### createStatWithAction()
Crea statistica con azione personalizzata:
```php
protected function createStatWithAction(
    string $label,
    string $value,
    Action $action,
    string $description = '',
    string $icon = 'heroicon-m-information-circle',
    string $color = 'info'
): Stat
```

#### createStatWithBadge()
Crea statistica con badge:
```php
protected function createStatWithBadge(
    string $label,
    string $value,
    string $badge,
    string $badgeColor = 'info',
    string $description = '',
    string $icon = 'heroicon-m-information-circle',
    string $color = 'info'
): Stat
```

### Statistiche Comparative

#### createComparativeStat()
Crea statistica comparativa tra due periodi:
```php
protected function createComparativeStat(
    string $label,
    float $currentValue,
    float $previousValue,
    string $currentPeriod,
    string $previousPeriod,
    string $icon = 'heroicon-m-information-circle',
    string $color = 'info'
): Stat
```

### Gestione Errori

#### createStatWithErrorHandling()
Crea statistica con gestione errori:
```php
protected function createStatWithErrorHandling(
    string $label,
    callable $valueCallback,
    string $fallbackValue = '0',
    string $description = '',
    string $icon = 'heroicon-m-information-circle',
    string $color = 'info'
): Stat
```

### Query Database

#### createStatFromQuery()
Crea statistica da query COUNT:
```php
protected function createStatFromQuery(
    string $label,
    string $table,
    string $column = '*',
    array $conditions = [],
    string $description = '',
    string $icon = 'heroicon-m-information-circle',
    string $color = 'info'
): Stat
```

#### createStatFromAggregateQuery()
Crea statistica da query di aggregazione:
```php
protected function createStatFromAggregateQuery(
    string $label,
    string $table,
    string $aggregateFunction, // sum, avg, max, min
    string $column,
    array $conditions = [],
    string $description = '',
    string $icon = 'heroicon-m-information-circle',
    string $color = 'info',
    int $decimals = 0
): Stat
```

#### createStatWithTrendFromQuery()
Crea statistica con trend da query database:
```php
protected function createStatWithTrendFromQuery(
    string $label,
    string $table,
    string $dateColumn,
    Carbon $currentPeriod,
    Carbon $previousPeriod,
    array $conditions = [],
    string $description = '',
    string $icon = 'heroicon-m-information-circle',
    string $color = 'info'
): Stat
```

### Gestione Cache

#### getCachedData()
Ottiene dati cacheati con gestione errori:
```php
protected function getCachedData(string $cacheKey, int $ttl, callable $callback)
```

#### getCachedDataWithDefaultTtl()
Ottiene dati cacheati con TTL predefinito:
```php
protected function getCachedDataWithDefaultTtl(string $cacheKey, callable $callback)
```

## Esempio di Implementazione Avanzata

```php
<?php

declare(strict_types=1);

namespace Modules\MyModule\Filament\Widgets;

use Modules\Xot\Filament\Widgets\XotBaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Actions\Action;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MyAdvancedStatsOverviewWidget extends XotBaseStatsOverviewWidget
{
    protected static ?int $sort = 2;
    protected static ?string $pollingInterval = '2m';

    protected function getStats(): array
    {
        $cacheKey = 'mymodule:dashboard:advanced_stats_overview';

        return $this->getCachedDataWithDefaultTtl($cacheKey, function () {
            $currentMonth = Carbon::now()->startOfMonth();
            $previousMonth = Carbon::now()->subMonth()->startOfMonth();

            return [
                // Statistica base con formattazione
                $this->createStat(
                    'Utenti Totali',
                    $this->formatNumber(DB::table('users')->count()),
                    'Tutti gli utenti registrati',
                    'heroicon-m-users',
                    'success'
                ),

                // Statistica con trend calcolato automaticamente
                $this->createStatWithCalculatedTrend(
                    'Nuovi Utenti',
                    DB::table('users')->where('created_at', '>=', $currentMonth)->count(),
                    DB::table('users')->where('created_at', '>=', $previousMonth)->count(),
                    'heroicon-m-user-plus',
                    'info'
                ),

                // Statistica da query con condizioni
                $this->createStatFromQuery(
                    'Utenti Attivi',
                    'users',
                    '*',
                    ['status' => 'active'],
                    'Utenti con stato attivo',
                    'heroicon-m-check-circle',
                    'success'
                ),

                // Statistica con aggregazione
                $this->createStatFromAggregateQuery(
                    'Valore Medio Ordini',
                    'orders',
                    'avg',
                    'total_amount',
                    ['status' => 'completed'],
                    'Valore medio degli ordini completati',
                    'heroicon-m-currency-euro',
                    'warning',
                    2
                ),

                // Statistica con URL
                $this->createStatWithUrl(
                    'Vedi Tutti gli Utenti',
                    $this->formatNumber(DB::table('users')->count()),
                    '/admin/users',
                    'Clicca per visualizzare tutti gli utenti',
                    'heroicon-m-arrow-right',
                    'primary'
                ),

                // Statistica con badge
                $this->createStatWithBadge(
                    'Ordini in Attesa',
                    $this->formatNumber(DB::table('orders')->where('status', 'pending')->count()),
                    'Nuovo',
                    'warning',
                    'Ordini che richiedono attenzione',
                    'heroicon-m-clock',
                    'warning'
                ),

                // Statistica con trend da query
                $this->createStatWithTrendFromQuery(
                    'Vendite Mensili',
                    'orders',
                    'created_at',
                    $currentMonth,
                    $previousMonth,
                    ['status' => 'completed'],
                    'Confronto vendite mese corrente vs precedente',
                    'heroicon-m-chart-bar',
                    'primary'
                ),
            ];
        });
    }

    public function getHeading(): ?string
    {
        return __('mymodule::widgets.advanced_stats_overview.title');
    }

    public static function canView(): bool
    {
        return auth()->user()->can('view_advanced_stats');
    }
}
```

## Esempio con Gestione Errori

```php
protected function getStats(): array
{
    return [
        // Statistica con gestione errori
        $this->createStatWithErrorHandling(
            'Dati Complessi',
            function () {
                // Query complessa che potrebbe fallire
                return DB::table('complex_table')
                    ->join('another_table', 'complex_table.id', '=', 'another_table.complex_id')
                    ->where('status', 'active')
                    ->sum('amount');
            },
            '0',
            'Dati calcolati in tempo reale',
            'heroicon-m-calculator',
            'info'
        ),

        // Statistica con azione personalizzata
        $this->createStatWithAction(
            'Esporta Dati',
            '1,234',
            Action::make('export')
                ->label('Esporta CSV')
                ->icon('heroicon-m-arrow-down-tray')
                ->action(function () {
                    // Logica di esportazione
                }),
            'Clicca per esportare i dati',
            'heroicon-m-document-arrow-down',
            'secondary'
        ),
    ];
}
```

## Colori Disponibili

- `'success'` - Verde
- `'danger'` - Rosso
- `'warning'` - Giallo
- `'info'` - Blu
- `'primary'` - Blu primario
- `'secondary'` - Grigio

## Icone Heroicon

### Icone Generali
- `'heroicon-m-information-circle'` - Informazioni
- `'heroicon-m-users'` - Utenti
- `'heroicon-m-user-plus'` - Nuovo utente
- `'heroicon-m-chart-bar'` - Grafico
- `'heroicon-m-currency-euro'` - Valuta
- `'heroicon-m-check-circle'` - Verificato
- `'heroicon-m-clock'` - Orologio
- `'heroicon-m-calculator'` - Calcolatrice
- `'heroicon-m-document-arrow-down'` - Download documento

### Icone Trend
- `'heroicon-m-arrow-trending-up'` - Trend positivo
- `'heroicon-m-arrow-trending-down'` - Trend negativo
- `'heroicon-m-arrow-up'` - Freccia su
- `'heroicon-m-arrow-down'` - Freccia giù
- `'heroicon-m-minus'` - Nessun cambiamento

## Best Practices

### 1. Utilizzare il Caching
```php
return $this->getCachedDataWithDefaultTtl('cache_key', function () {
    // Query costose qui
});
```

### 2. Formattare i Numeri
```php
$this->createStat('Utenti', $this->formatNumber($count))
$this->createStat('Percentuale', $this->formatPercentage($ratio))
$this->createStat('Valore', $this->formatCurrency($amount))
```

### 3. Gestire gli Errori
```php
$this->createStatWithErrorHandling('Dati', function () {
    // Query che potrebbe fallire
}, '0', 'Descrizione', 'heroicon-m-exclamation-triangle', 'danger')
```

### 4. Utilizzare Trend Automatici
```php
$this->createStatWithCalculatedTrend('Metrica', $current, $previous)
```

### 5. Query Database Ottimizzate
```php
$this->createStatFromQuery('Conteggio', 'table', '*', ['status' => 'active'])
$this->createStatFromAggregateQuery('Media', 'table', 'avg', 'column')
```

### 6. Statistiche Interattive
```php
$this->createStatWithUrl('Vedi Tutti', $count, '/admin/resource')
$this->createStatWithBadge('Nuovi', $count, 'Nuovo', 'warning')
```

### 7. Implementare Autorizzazioni
```php
public static function canView(): bool
{
    return auth()->user()->can('view_stats');
}
```

### 8. Personalizzare l'Intervallo di Polling
```php
protected static ?string $pollingInterval = '2m'; // Per dati che cambiano spesso
```

## Miglioramenti Implementati

### ✅ Nuove Funzionalità
- **Calcolo trend automatico** con `calculateTrend()` e `createStatWithCalculatedTrend()`
- **Formattazione numeri** con `formatNumber()`, `formatPercentage()`, `formatCurrency()`
- **Statistiche interattive** con URL, azioni e badge
- **Gestione errori** con `createStatWithErrorHandling()`
- **Query database ottimizzate** con metodi dedicati
- **Statistiche comparative** con `createComparativeStat()`
- **Cache migliorata** con gestione errori e TTL predefinito

### ✅ Miglioramenti Performance
- **Gestione errori cache** con fallback automatico
- **Query ottimizzate** con metodi dedicati
- **Formattazione automatica** per ridurre codice duplicato

### ✅ Miglioramenti UX
- **Statistiche interattive** con navigazione e azioni
- **Badge informativi** per evidenziare stati
- **Trend automatici** per analisi immediate
- **Formattazione consistente** dei numeri

## Collegamenti Correlati
- [XotBaseChartWidget](./xotbase-chart-widget.md)
- [XotBaseWidget](./xotbase-widget.md)
- [Filament Widgets Documentation](https://filamentphp.com/project_docs/2.x/admin/widgets)

---

## xotbase_extension_rules

*Consolidated from: `xotbase_extension_rules.md`*


---

## xotbasecluster

*Consolidated from: `xotbasecluster.md`*


---

## xotbaselistrecords

*Consolidated from: `xotbaselistrecords.md`*


---

## xotbasepage-getmodel-fix

*Consolidated from: `xotbasepage-getmodel-fix.md`*


## Problema
L'errore `Cannot make non static method Filament\Resources\Pages\Page::getModel() static in class Modules\Xot\Filament\Resources\Pages\XotBasePage` si verificava quando si tentava di sovrascrivere il metodo `getModel()` nella classe `XotBasePage`.

## Causa
La classe `XotBasePage` dichiarava il metodo `getModel()` come **statico**, ma la classe padre `Filament\Resources\Pages\Page` lo dichiara come **non statico**. In PHP non è possibile sovrascrivere un metodo non statico con uno statico.

## Analisi Tecnica
- **Classe padre**: `Filament\Resources\Pages\Page::getModel()` - metodo **non statico** che restituisce `string`
- **Classe figlia**: `XotBasePage::getModel()` - erroneamente dichiarato come **statico** con tipo `null|string`
- **Errore**: Violazione del principio di sovrascrittura dei metodi in PHP

## Soluzione Implementata

### 1. Correzione della Dichiarazione del Metodo
```php
// PRIMA (errato)
public static function getModel(): null|string
{
    return static::$model;
}

// DOPO (corretto)
public function getModel(): string
{
    if (static::$model === null) {
        throw new \LogicException('Model class not set for page: ' . static::class);
    }

    return static::$model;
}
```

### 2. Miglioramenti Implementati
- **Rimozione `static`**: Il metodo ora è correttamente non statico
- **Tipo di ritorno corretto**: Restituisce `string` invece di `null|string` per compatibilità con la classe padre
- **Gestione errori**: Lancia eccezione se `$model` non è impostato, invece di restituire `null`
- **Documentazione migliorata**: Spiegazione del motivo per cui il metodo deve essere non statico

## Impatto e Compatibilità

### Classi che Estendono XotBasePage
Verificate 24 classi che estendono `XotBasePage`:
- ✅ **Nessuna chiamata statica**: Nessuna classe utilizza `static::getModel()`
- ✅ **Compatibilità garantita**: Tutte le classi continueranno a funzionare
- ✅ **Nessuna modifica richiesta**: Le classi figlie non necessitano di modifiche

### Risorse Filament
Le Resources Filament utilizzano `static::getModel()` ma questo è diverso dal metodo delle Pages:
- ✅ **Separazione corretta**: Resources vs Pages hanno implementazioni separate
- ✅ **Nessun conflitto**: La correzione non impatta le Resources

## Architettura Laraxot

### Principio di Estensione
Questo fix rispetta il principio fondamentale di Laraxot:
> **MAI estendere classi Filament direttamente - sempre estendere classi XotBase**

### Benefici della Correzione
1. **Compatibilità PHP**: Rispetta le regole di sovrascrittura dei metodi
2. **Type Safety**: Tipo di ritorno corretto per PHPStan livello 9+
3. **Gestione Errori**: Eccezioni chiare invece di valori null inaspettati
4. **Manutenibilità**: Codice più robusto e prevedibile

## Test e Verifiche

### Controlli Eseguiti
- ✅ **Linting**: Nessun errore di sintassi
- ✅ **Rotte**: Verificate le rotte del modulo Progressioni
- ✅ **Compatibilità**: Nessuna classe figlia richiede modifiche
- ✅ **Architettura**: Rispetta i principi di Laraxot

### Metodologia di Test
- **Analisi statica**: Verifica della compatibilità con classi esistenti
- **Controllo dipendenze**: Verifica che nessuna classe utilizzi il metodo staticamente
- **Validazione architetturale**: Rispetto dei principi di estensione Laraxot

## Best Practice per il Futuro

### Quando Sovrascrivere Metodi Filament
1. **Verificare sempre la firma**: Controllare se il metodo padre è statico o non statico
2. **Mantenere compatibilità**: Il tipo di ritorno deve essere compatibile
3. **Documentare le eccezioni**: Spiegare perché si sovrascrive un metodo
4. **Testare l'impatto**: Verificare che le classi figlie continuino a funzionare

### Pattern di Estensione Corretto
```php
// ✅ CORRETTO - Verificare sempre la firma del metodo padre
public function getModel(): string
{
    // Implementazione specifica del modulo
}

// ❌ ERRATO - Non verificare la firma del metodo padre
public static function getModel(): null|string
{
    // Implementazione che viola le regole di sovrascrittura
}
```

## Collegamenti
- [XotBasePage](../app/Filament/Resources/Pages/XotBasePage.php)
- [Filament Page Documentation](https://filamentphp.com/docs/3.x/resources/pages)
- [Laraxot Extension Rules](../../../docs/laraxot-conventions.md)

## Note di Manutenzione
- **Data correzione**: Gennaio 2025
- **Versione Filament**: 3.x
- **PHP Version**: 8.3+
- **Livello PHPStan**: 9+

*Ultimo aggiornamento: gennaio 2025*

---

## xotbasepage-implementation

*Consolidated from: `xotbasepage-implementation.md`*


## descrizione
la classe `XotBasePage` è una classe base astratta per tutte le pagine filament non collegate a risorse specifiche. fornisce funzionalità comuni come gestione delle traduzioni, integrazione con il sistema di autorizzazioni e utilità per l'accesso ai dati.

## struttura
la classe `XotBasePage` estende `Filament\Pages\Page` e si trova in:
```
Modules/Xot/app/Filament/Pages/XotBasePage.php
```

## namespace
```php
namespace Modules\Xot\Filament\Pages;
```

## utilizzo corretto

```php
// nel modulo esempio
namespace Modules\Example\Filament\Pages;

use Modules\Xot\Filament\Pages\XotBasePage;

class SettingsPage extends XotBasePage
{
    // implementazione...
}
```

## funzionalità principali

1. **sistema di traduzioni integrato**
   - localizzazione automatica basata sul modulo
   - generazione di chiavi di traduzione standardizzate

2. **gestione autorizzazioni**
   - integrazione con policy e autorizzazioni

3. **integrazione con form**
   - gestione form standardizzata
   - supporto per validazione

4. **rilevamento intelligente modello**
   - rilevamento automatico del modello associato
   - gestione centralizzata dell'entità associata

## best practices

### 1. traduzioni
- non usare mai stringhe hardcoded per le etichette
- utilizzare il metodo `trans()` o il trait `TransTrait`
- organizzare le traduzioni nei file del modulo (`/Modules/NomeModulo/lang/`)

### 2. override di metodi
- implementare `getFormSchema()` per definire la struttura del form SOLO nelle classi figlie che ne hanno bisogno
- NON dichiarare mai abstract getFormSchema() in XotBasePage
- non sovrascrivere metodi dichiarati come `final`
- estendere i metodi hook dove possibile

### 3. viste
- utilizzare viste nel modulo specifico
- preferire component blade riutilizzabili

### 4. performance
- evitare query n+1 utilizzando eager loading
- minimizzare il caricamento di risorse non necessarie

## esempio completo

```php
namespace Modules\<nome progetto>\Filament\Pages;

use Modules\Xot\Filament\Pages\XotBasePage;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class DashboardSettings extends XotBasePage
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $view = '<nome progetto>::filament.pages.dashboard-settings';

    protected function getFormFields(): array
    {
        return [
            'title' => [
                'type' => TextInput::class,
                'label' => true,
                'tooltip' => true,
                'placeholder' => true,
                'required' => true,
            ],
            'refresh_interval' => [
                'type' => Select::class,
                'label' => true,
                'tooltip' => true,
                'options' => [
                    '30' => '30 secondi',
                    '60' => '1 minuto',
                    '300' => '5 minuti',
                ]
            ]
        ];
    }

    public function submit(): void
    {
        $this->form->validate();
        // logica di salvataggio
    }

    public function authorize(): bool
    {
        return auth()->user()->can('view', static::class);
    }
}
```

## traduzioni dei campi

Le traduzioni dei campi del form devono essere definite nei file di traduzione del modulo seguendo questa struttura:

```php
// /Modules/NomeModulo/lang/it/fields.php
return [
    'title' => [
        'label' => 'Titolo',
        'tooltip' => 'Inserisci il titolo della dashboard',
        'placeholder' => 'Es. Dashboard Principale',
    ],
    'refresh_interval' => [
        'label' => 'Intervallo di aggiornamento',
        'tooltip' => 'Seleziona ogni quanto aggiornare i dati',
        'placeholder' => 'Seleziona un intervallo',
    ],
];
```

## autorizzazioni

Per implementare le autorizzazioni, è necessario:

1. Definire una policy per la pagina
2. Implementare il metodo `authorize()` nella classe della pagina
3. Registrare la policy nel service provider del modulo

```php
// /Modules/NomeModulo/Policies/DashboardSettingsPolicy.php
namespace Modules\NomeModulo\Policies;

use App\Models\User;

class DashboardSettingsPolicy
{
    public function view(User $user): bool
    {
        return $user->hasRole('admin');
    }
}
```

## considerazioni di sicurezza
- validare sempre gli input dell'utente
- utilizzare `authorizeAccess()` per controllare gli accessi
- seguire il principio del privilegio minimo

## pattern comuni
- pagine di impostazioni
- dashboard specializzate
- pagine di reportistica
- wizard personalizzati

## collegamento ad altre documentazioni
- [pattern di estensione filament](../Xot/docs/filament_extension_pattern.md)
- [best practices filament](../<nome progetto>/docs/filament-best-practices.md)

## ATTENZIONE: errori critici da evitare
- NON dichiarare mai abstract getFormSchema() in XotBasePage: la classe base Filament lo implementa già. Fornire sempre una implementazione di default (array vuoto).
- Se serve uno schema custom, sovrascrivere il metodo nella classe figlia.

---

## xotbasepage-interactswithforms-fix

*Consolidated from: `xotbasepage-interactswithforms-fix.md`*


## Data: Febbraio 2026

## Problema

FatalError all'avvio dell'applicazione causato da conflitti tra `XotBasePage` e le classi figlie che ri-dichiaravano `InteractsWithForms` e/o `implements HasForms`.

### Errori identificati nei log

1. **`getFormModel()` return type incompatibility**:
   ```
   Declaration of Filament\Forms\Concerns\InteractsWithForms::getFormModel(): 
   Illuminate\Database\Eloquent\Model|string|null must be compatible with 
   Modules\Xot\Filament\Resources\Pages\XotBasePage::getFormModel(): ?string
   ```

2. **`getFormStatePath()` access level conflict**:
   ```
   Access level to Filament\Forms\Concerns\InteractsWithForms::getFormStatePath() 
   must be public (as in class Modules\Xot\Filament\Resources\Pages\XotBasePage)
   ```

## Root Cause

`XotBasePage` dichiara `implements HasForms` e `use InteractsWithForms`. Quando le classi figlie ri-dichiaravano gli stessi trait/interfacce, PHP generava conflitti di:
- **Return type**: il trait `InteractsWithForms` dichiara `getFormModel(): Model|string|null` ma la classe base aveva `?string`
- **Access level**: `getFormStatePath()` era `protected` nel trait ma `public` nella classe base

## Soluzione Applicata

### 1. XotBasePage (classe base)
- Mantiene `implements HasForms` e `use InteractsWithForms`
- `getFormStatePath()` cambiato a `public` con return type `string` (non nullable)
- `getFormModel()` return type allineato a `Model|string|null`

### 2. Classi figlie (fix critico)
**RIMOSSO** `use InteractsWithForms` e `implements HasForms` da TUTTE le classi figlie che estendono `XotBasePage`:

- `Modules/IndennitaResponsabilita/.../SendMailIndennitaResponsabilita.php`
- `Modules/IndennitaResponsabilita/.../UpdateDiriByCsv.php`
- `Modules/Notify/.../SendTelegram.php`
- `Modules/Notify/.../SendPushNotification.php`
- `Modules/Notify/.../SendTelegramPage.php`
- `Modules/Notify/.../SendEmail.php`
- `Modules/Notify/.../TestSmtpPage.php`
- `Modules/Xot/.../MetatagPage.php`
- `Modules/Activity/.../ListLogActivities.php`
- `Modules/Pdnd/.../ServizioVerificaDichGeneralita.php`
- `Modules/Pdnd/.../ServizioVerificaDichEsistenzaVita.php`
- `Modules/Pdnd/.../ServizioAccertamentoIdUnicoNazionalePage.php`
- `Modules/Pdnd/.../ServizioAccertamentoIdUnicoNazionalePagePROD.php`
- `Modules/Pdnd/.../ServizioVerificaDichEsistenzaVitaPROD.php`
- `Modules/Pdnd/.../ServizioVerificaDichGeneralitaPROD.php`

## Regola Critica

> **Le classi che estendono `XotBasePage` NON DEVONO MAI ri-dichiarare `use InteractsWithForms` o `implements HasForms`.**
> Queste funzionalità sono già fornite dalla classe base `XotBasePage`.

## Pattern Corretto

```php
// ✅ CORRETTO
class MiaPage extends XotBasePage
{
    // NO use InteractsWithForms
    // NO implements HasForms
    
    protected function getFormSchema(): array
    {
        return [
            TextInput::make('name')->required(),
        ];
    }
}
```

## Anti-Pattern (da evitare)

```php
// ❌ ERRATO - causa FatalError
class MiaPage extends XotBasePage implements HasForms
{
    use InteractsWithForms; // CONFLITTO con XotBasePage
}
```

## Verifica

Dopo il fix, il sito risponde correttamente:
- `http://ptvx.local/` → HTTP 302 → `/admin/log` → HTTP 200
- Zero errori nei log Laravel

## Collegamenti
- [XotBasePage source](../../app/Filament/Resources/Pages/XotBasePage.php)
- [Filament best practices](../../../.windsurf/rules/filament-best-practices.md)

---

## xotbasepanelprovider-refactoring-variant

*Consolidated from: `xotbasepanelprovider-refactoring-variant.md`*


---

## xotbasepanelprovider-refactoring

*Consolidated from: `xotbasepanelprovider-refactoring.md`*



---

## xotbasepanelprovider_refactoring

*Consolidated from: `xotbasepanelprovider_refactoring.md`*


---

## xotbaseresource-violations

*Consolidated from: `xotbaseresource-violations.md`*


## 🚨 ERRORI GRAVISSIMI DA EVITARE SEMPRE

### Regole Fondamentali XotBaseResource

#### 1. DIVIETO ASSOLUTO: ->label(), ->placeholder(), ->helperText()
```php
// ❌ GRAVEMENTE ERRATO - MAI USARE
TextInput::make('field')
    ->label('Label')          // VIETATO!
    ->placeholder('Placeholder')  // VIETATO!
    ->helperText('Help text');    // VIETATO!

DatePicker::make('date')
    ->label('Data');          // VIETATO!

Toggle::make('active')
    ->label('Attivo');        // VIETATO!
```

**Motivazione**: Le traduzioni sono gestite automaticamente dal LangServiceProvider tramite i file di traduzione del modulo.

#### 2. DIVIETO ASSOLUTO: Metodi Tabellari nella Resource Principale
```php
// ❌ GRAVEMENTE ERRATO - NON implementare nella Resource principale
public static function getTableColumns(): array { ... }     // VIETATO!
public static function getTableFilters(): array { ... }     // VIETATO!
public static function getTableActions(): array { ... }     // VIETATO!
public static function getTableBulkActions(): array { ... } // VIETATO!
```

**Motivazione**: Questi metodi vanno implementati nelle Pages specifiche (es. ListRecords), NON nella Resource principale.

#### 3. DIVIETO ASSOLUTO: getPages() Standard
```php
// ❌ GRAVEMENTE ERRATO - Se contiene solo route standard
public static function getPages(): array
{
    return [
        'index' => Pages\ListRecords::route('/'),
        'create' => Pages\CreateRecord::route('/create'),
        'edit' => Pages\EditRecord::route('/{record}/edit'),
        'view' => Pages\ViewRecord::route('/{record}'),  // Standard
    ];
}
```

**Motivazione**: XotBaseResource gestisce automaticamente le route standard.

## Architettura Fondamentale Laraxot

### Principi Architetturali

#### 1. Centralizzazione e Standardizzazione
- **XotBaseResource** è il punto centrale di controllo per TUTTE le risorse
- **Ogni override locale** rompe la standardizzazione del sistema
- **La manutenibilità** dipende dalla centralizzazione

#### 2. Sistema di Traduzioni Automatico
- **LangServiceProvider** gestisce automaticamente TUTTE le traduzioni
- **File di traduzione del modulo** forniscono label, placeholder, help
- **Zero stringhe hardcoded** nelle interfacce

#### 3. Separazione delle Responsabilità
- **Resource principale**: SOLO `getFormSchemaOld()` e configurazione modello (migrazione 2026-08)
- **Pages specifiche**: `getTableColumns()`, `getTableFilters()`, `getTableActions()`
- **File di traduzione**: Tutte le stringhe UI

### Pattern Architetturale

```
XotBaseResource (Classe Base)
├── Gestione automatica form() [FINAL]
├── Gestione automatica table() [FINAL]
├── Gestione automatica traduzioni
├── Gestione automatica route standard
└── Configurazione centralizzata

Resource Specifica (Estende XotBaseResource)
├── SOLO getFormSchema()
├── SOLO configurazione modello
└── NESSUN override se standard

Pages Specifiche (es. ListRecords)
├── getTableColumns()
├── getTableFilters()
├── getTableActions()
└── getTableBulkActions()

File Traduzioni Modulo
├── fields.*.label
├── fields.*.placeholder
├── fields.*.help
└── actions.*.label
```

## Impatti delle Violazioni

### Impatti Tecnici Critici
- **Rottura della standardizzazione** Laraxot
- **Perdita della localizzazione automatica**
- **Violazione dell'architettura modulare**
- **Duplicazione del codice** e logica
- **Inconsistenza** tra risorse diverse

### Impatti Operativi Critici
- **Problemi di manutenibilità** del codice
- **Difficoltà negli aggiornamenti** globali
- **Perdita di controllo centralizzato**
- **Regressioni** in funzionalità esistenti

## Pattern Corretto Universale

### ✅ Resource Principale CORRETTA
```php
<?php

declare(strict_types=1);

namespace Modules\{ModuleName}\Filament\Resources;

use Modules\{ModuleName}\Models\{ModelName};
use Modules\Xot\Filament\Resources\XotBaseResource;

class {ModelName}Resource extends XotBaseResource
{
    protected static ?string $model = {ModelName}::class;

    // UNICO metodo necessario nella Resource principale
    public static function getFormSchema(): array
    {
        return [
            Section::make()  // NO ->label() - gestito automaticamente
                ->schema([
                    TextInput::make('field')  // NO ->label() - gestito automaticamente
                        ->required(),
                    // Altri campi senza ->label(), ->placeholder(), ->helperText()
                ]),
        ];
    }

    // NESSUN altro metodo se standard:
    // - NO getTableColumns(), getTableFilters(), getTableActions(), getTableBulkActions()
    // - NO getPages() se contiene solo route standard
    // - NO form(), table() (sono FINAL in XotBaseResource)
}
```

### ✅ Page Specifica CORRETTA
```php
<?php

declare(strict_types=1);

namespace Modules\{ModuleName}\Filament\Resources\{ModelName}Resource\Pages;

use Modules\{ModuleName}\Filament\Resources\{ModelName}Resource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

class List{ModelName}s extends XotBaseListRecords
{
    protected static string $resource = {ModelName}Resource::class;

    // QUI vanno i metodi tabellari
    public function getTableColumns(): array
    {
        return [
            'field' => TextColumn::make('field')  // NO ->label() - gestito automaticamente
                ->searchable()
                ->sortable(),
        ];
    }

    public function getTableFilters(): array
    {
        return [
            // Filtri senza ->label()
        ];
    }

    public function getTableActions(): array
    {
        return [
            // Azioni senza ->label()
        ];
    }

    public function getTableBulkActions(): array
    {
        return [
            // Bulk actions senza ->label()
        ];
    }
}
```

### ✅ File Traduzione CORRETTO
```php
<?php

declare(strict_types=1);

// Modules/{ModuleName}/lang/it/{resource}.php
return [
    'navigation' => [
        'label' => 'Etichetta Navigazione',
        'group' => 'Gruppo Navigazione',
    ],
    'fields' => [
        'field' => [
            'label' => 'Etichetta Campo',
            'placeholder' => 'Placeholder Campo',
            'help' => 'Testo di aiuto Campo',
        ],
    ],
    'actions' => [
        'create' => [
            'label' => 'Crea Nuovo',
            'success' => 'Creato con successo',
            'error' => 'Errore durante la creazione',
        ],
    ],
    'sections' => [
        'main' => [
            'label' => 'Sezione Principale',
            'description' => 'Descrizione sezione',
        ],
    ],
];
```

## Regole Permanenti Globali

### ❌ DIVIETI ASSOLUTI
1. **MAI** usare ->label(), ->placeholder(), ->helperText() in QUALSIASI componente Filament
2. **MAI** implementare metodi tabellari nella Resource principale
3. **MAI** implementare getPages() se contiene solo route standard
4. **MAI** sovrascrivere form() o table() (sono FINAL)
5. **MAI** duplicare logica già presente in XotBaseResource

### ✅ OBBLIGHI ASSOLUTI
1. **SEMPRE** estendere XotBaseResource per le risorse
2. **SEMPRE** implementare SOLO getFormSchemaOld() nella Resource principale
3. **SEMPRE** spostare metodi tabellari nelle Pages specifiche
4. **SEMPRE** affidarsi al sistema di traduzioni automatico
5. **SEMPRE** documentare eventuali personalizzazioni non standard

## Processo di Correzione Standard

### Fase 1: Audit della Resource
1. Identificare tutti i ->label(), ->placeholder(), ->helperText()
2. Identificare metodi tabellari nella Resource principale
3. Verificare se getPages() è necessario

### Fase 2: Correzione Resource
1. Rimuovere TUTTI i ->label(), ->placeholder(), ->helperText()
2. Rimuovere getTableColumns(), getTableFilters(), getTableActions(), getTableBulkActions()
3. Rimuovere getPages() se standard

### Fase 3: Correzione Pages
1. Spostare metodi tabellari in Pages specifiche
2. Rimuovere ->label() da componenti tabellari
3. Verificare funzionamento traduzioni automatiche

### Fase 4: Verifica Sistema
1. Testare funzionalità complete
2. Verificare traduzioni automatiche
3. Eseguire PHPStan per validazione
4. Documentare correzioni applicate

## Checklist di Conformità

### Resource Principale
- [ ] Estende XotBaseResource
- [ ] Contiene SOLO getFormSchema()
- [ ] NESSUN ->label(), ->placeholder(), ->helperText()
- [ ] NESSUN metodo tabellare
- [ ] NESSUN getPages() se standard

### Pages Specifiche
- [ ] Contengono metodi tabellari
- [ ] NESSUN ->label() nei componenti
- [ ] Traduzioni automatiche funzionanti

### File Traduzioni
- [ ] Struttura completa (fields, actions, sections)
- [ ] Tutte le chiavi necessarie presenti
- [ ] Nessuna duplicazione

### Sistema Generale
- [ ] Funzionalità complete operative
- [ ] Traduzioni automatiche attive
- [ ] PHPStan compliance
- [ ] Documentazione aggiornata

## Collegamenti Documentazione

### Documentazione Moduli
- [Progressioni: XotBaseResource Violations](../laravel/Modules/Progressioni/docs/xotbaseresource-violations-critical.md)
- [Xot: XotBaseResource Rules](../laravel/Modules/Xot/docs/filament/resources/xot-base-resource.md)
- [Xot: Filament Resource Guidelines](../laravel/Modules/Xot/docs/rules/filament-resource-guidelines.md)

### Regole Correlate
- [Sistema Traduzioni](translation-system.md)
- [Filament Best Practices](filament-best-practices.md)
- [Architettura Modulare](modular-architecture.md)

*Documento creato: agosto 2025*
*Ultimo aggiornamento: agosto 2025*

---

## xotbaseresource

*Consolidated from: `xotbaseresource.md`*


## Panoramica

XotBaseResource è la classe base astratta per tutte le risorse Filament nel sistema. Estende `Filament\Resources\Resource` e implementa funzionalità comuni per la gestione delle risorse.

## Caratteristiche Principali

### Metodi Final

Alcuni metodi sono marcati come `final` e non possono essere sovrascritti nelle classi figlie:

```php
final public static function form(Form $form): Form
{
    return $form->schema(static::getFormSchema()); // fallback
}
```

Questo significa che:
- Non è possibile sovrascrivere il metodo `form()`
- Si deve invece implementare `getFormSchemaOld()`
- Tentare di sovrascrivere un metodo `final` causerà un errore

### Metodi Astratti (migrazione 2026-08)

```php
/** @return array<int|string, \Filament\Schemas\Components\Component> */
abstract public static function getFormSchemaOld(): array;
```

Questo metodo DEVE essere implementato nelle classi figlie e deve restituire un array di componenti del form. `getFormSchema()` è ora `final` e ritorna `[]` — vedi [[xotbaseresource-formschema-old-pattern]].

## Best Practices

1. **Non Sovrascrivere Metodi Final**
   - Non tentare di sovrascrivere `form()`
   - Implementare invece `getFormSchemaOld()` (migrazione 2026-08)
   - Rispettare la struttura definita nella classe base

2. **Gestione delle Table Actions**
   - Se `getTableActions()` restituisce solo ViewAction, EditAction e DeleteAction, rimuoverlo
   - Se presente, deve includere `...parent::getTableActions()`
   - Se `getTableBulkActions()` restituisce solo DeleteBulkAction, rimuoverlo

3. **Label e Traduzioni**
   - Non utilizzare mai `->label('')` direttamente
   - Gestire le label tramite file di traduzione
   - Utilizzare il trait `NavigationLabelTrait`

## Esempio di Implementazione Corretta

```php
namespace Modules\Notify\Filament\Resources;

use Modules\Xot\Filament\Resources\XotBaseResource;
use Filament\Forms;

class NotificationResource extends XotBaseResource
{
    protected static ?string $model = 'Modules\Notify\Models\Notification';

    public static function getFormSchemaOld(): array
    {
        return [
            Forms\Components\TextInput::make('title')
                // Non usare ->label() direttamente
                // Le label sono gestite via file di traduzione
        ];
    }

    // Non sovrascrivere form() perché è final
    // Non definire getTableActions() se restituisce solo azioni standard
}
```

## Collegamenti Bidirezionali

### Collegamenti nella Root
- [Architettura Filament](../../../docs/architecture/filament.md)
- [Gestione Risorse](../../../docs/architecture/resources.md)
- [Regole XotBaseResource](../../../docs/regole/xotbaseresource-rules.md)

### Collegamenti ai Moduli
- [Notify Resource](../../Notify/docs/filament-resources.md)
- [User Resource](../../User/docs/filament-resources.md)

## Note Importanti

1. Non sovrascrivere mai metodi marcati come `final`
2. Implementare sempre i metodi astratti richiesti
3. Utilizzare i file di traduzione per le label
4. Evitare override non necessari di metodi
5. Seguire le convenzioni di Filament

---

## xotbaserouteserviceprovider-conflict-resolution-risoluzione-conflitto-xotbaserouteservic

*Consolidated from: `xotbaserouteserviceprovider-conflict-resolution-risoluzione-conflitto-xotbaserouteservic.md`*


## Problema
Il file conteneva marker di conflitto git  all'interno del metodo `boot()`, con possibili duplicati o codice commentato.

## Scelta
- Sono stati rimossi tutti i marker di conflitto.
- È stato mantenuto il codice più recente e coerente con la logica del modulo.
- La chiamata a `parent::boot();` viene mantenuta dopo la configurazione di `extra_conn`.
- La sintassi e lo stile PSR-12 sono stati rispettati.

## Collegamento alla doc root
Vedi `/docs/xot_conflict_links.md` per la mappatura dei file documentati localmente e i riferimenti incrociati.

---

## xotbaserouteserviceprovider-conflict-resolution

*Consolidated from: `xotbaserouteserviceprovider-conflict-resolution.md`*


## Problema
Il file conteneva marker di conflitto git  all'interno del metodo `boot()`, con possibili duplicati o codice commentato.

## Scelta
- Sono stati rimossi tutti i marker di conflitto.
- È stato mantenuto il codice più recente e coerente con la logica del modulo.
- La chiamata a `parent::boot();` viene mantenuta dopo la configurazione di `extra_conn`.
- La sintassi e lo stile PSR-12 sono stati rispettati.

## Collegamento alla doc root
Vedi `/project_docs/xot_conflict_links.md` per la mappatura dei file documentati localmente e i riferimenti incrociati.
# Risoluzione conflitto XotBaseRouteServiceProvider.php

## Problema
Il file conteneva marker di conflitto git  all'interno del metodo `boot()`, con possibili duplicati o codice commentato.

## Scelta
- Sono stati rimossi tutti i marker di conflitto.
- È stato mantenuto il codice più recente e coerente con la logica del modulo.
- La chiamata a `parent::boot();` viene mantenuta dopo la configurazione di `extra_conn`.
- La sintassi e lo stile PSR-12 sono stati rispettati.

## Collegamento alla doc root
Vedi `/docs/xot_conflict_links.md` per la mappatura dei file documentati localmente e i riferimenti incrociati.

---

## xotbaserouteserviceprovider-resolution

*Consolidated from: `xotbaserouteserviceprovider-resolution.md`*

module: theme
topic: xotbaserouteserviceprovider-resolution
canonical: ../../../Themes/docs/shared-components/xotbaserouteserviceprovider-conflict-resolution.md
---

See canonical documentation: ../../../Themes/docs/shared-components/xotbaserouteserviceprovider-conflict-resolution.md
---

## xotbaserouteserviceprovider_conflict_resolution

*Consolidated from: `xotbaserouteserviceprovider_conflict_resolution.md`*

module: theme
topic: xotbaserouteserviceprovider_conflict_resolution
canonical: ../../../Themes/docs/shared-components/xotbaserouteserviceprovider-conflict-resolution-1.md
---

See canonical documentation: ../../../Themes/docs/shared-components/xotbaserouteserviceprovider-conflict-resolution-1.md

---

## xotbaseserviceprovider

*Consolidated from: `xotbaseserviceprovider.md`*


## Panoramica

XotBaseServiceProvider è la classe base astratta per tutti i ServiceProvider dei moduli nel sistema. Estende `Illuminate\Support\ServiceProvider` e implementa funzionalità comuni per la registrazione di componenti, configurazioni, traduzioni e altro.

## Proprietà Fondamentali

```php
public string $name = '';           // Nome del modulo (DEVE essere public)
public string $nameLower = '';      // Nome del modulo in minuscolo
protected string $module_dir = __DIR__;    // Directory del modulo
protected string $module_ns = __NAMESPACE__; // Namespace del modulo
```

### Importanza della Visibilità

- `$name` e `$nameLower` DEVONO essere `public` perché vengono utilizzati da classi figlie
- La visibilità non può essere modificata nelle classi che estendono XotBaseServiceProvider
- Modificare la visibilità causerà un errore PHP: "Access level ... must be public (as in class XotBaseServiceProvider)"

## Metodi Principali

### boot()
```php
public function boot(): void
{
    $this->registerTranslations();
    $this->registerViews();
    $this->loadMigrationsFrom($this->module_dir.'/../Database/Migrations');
    $this->registerLivewireComponents();
    $this->registerBladeComponents();
    $this->registerCommands();
}
```

Responsabilità:
- Registrazione traduzioni
- Registrazione viste
- Caricamento migrazioni
- Registrazione componenti Livewire
- Registrazione componenti Blade
- Registrazione comandi

### register()
```php
public function register(): void
{
    $this->nameLower = Str::lower($this->name);
    $this->module_ns = collect(explode('\\', $this->module_ns))->slice(0, -1)->implode('\\');
    $this->app->register($this->module_ns.'\Providers\RouteServiceProvider');
    $this->app->register($this->module_ns.'\Providers\EventServiceProvider');
    $this->registerConfig();
    $this->registerBladeIcons();
}
```

Responsabilità:
- Inizializzazione proprietà del modulo
- Registrazione RouteServiceProvider
- Registrazione EventServiceProvider
- Registrazione configurazioni
- Registrazione icone Blade

## Best Practices

1. **Non Modificare la Visibilità**
   - Mantenere `public` per `$name` e `$nameLower`
   - Non cambiare la visibilità dei metodi ereditati

2. **Evitare Override Non Necessari**
   - Non sovrascrivere metodi se non si aggiunge funzionalità
   - Chiamare sempre `parent::method()` quando si sovrascrive

3. **Configurazione Corretta**
   - Impostare sempre `$name` nel costruttore
   - Verificare che `$module_dir` e `$module_ns` siano corretti

## Esempio di Implementazione Corretta

```php
namespace Modules\Notify\Providers;

use Modules\Xot\Providers\XotBaseServiceProvider;

class NotifyServiceProvider extends XotBaseServiceProvider
{
    public string $name = 'Notify';

    public function boot(): void
    {
        parent::boot();
        // Aggiungi funzionalità specifiche qui
    }
}
```

## Collegamenti Bidirezionali

### Collegamenti nella Root
- [Architettura dei Provider](../../../docs/architecture/providers.md)
- [Struttura dei Moduli](../../../docs/architecture/modules.md)

### Collegamenti ai Moduli
- [Notify ServiceProvider](../../Notify/docs/service-provider.md)
- [User ServiceProvider](../../User/docs/service-provider.md)

## Note Importanti

1. La proprietà `$name` è fondamentale e DEVE essere `public`
2. Non modificare mai la visibilità delle proprietà/metodi ereditati
3. Seguire sempre il pattern di registrazione standard
4. Documentare ogni modifica o estensione
5. Mantenere la coerenza tra i moduli
## Collegamenti tra versioni di XotBaseServiceProvider.md
* [XotBaseServiceProvider.md](../../../../docs/moduli/xot/XotBaseServiceProvider.md)

## Correzione, motivazione e miglioramenti (2025-05-13)

### Motivazione
- Garantire robustezza, coerenza e manutenibilità tra tutti i moduli.
- Prevenire errori di visibilità e override errati.
- Facilitare l'estensione e la personalizzazione dei provider nei moduli.
- Rendere la classe conforme a PHPStan livello 10 e alle best practices Laraxot.

### Azioni e pattern applicati
- **Tipizzazione e PHPDoc**: tutti i metodi pubblici e protected devono avere PHPDoc dettagliato e tipi di ritorno espliciti.
- **Centralizzazione dei fallback**: la logica di fallback per path e namespace va centralizzata in metodi protected riutilizzabili.
- **Gestione errori e logging**: loggare i casi di fallback e le eccezioni non bloccanti.
- **Pattern di override**: ogni override deve chiamare sempre `parent::method()`. Vietato cambiare la visibilità delle proprietà/metodi ereditati.
- **Testabilità**: usare metodi protected per facilitare il mocking nei test.
- **Registrazione icone Blade**: seguire il pattern documentato in [registerBladeIcons.md](registerbladeicons.md), con fallback e validazione dei path.

### Consigli di miglioramento
- Centralizzare la gestione dei path (views, lang, svg, ecc.) in un helper o trait.
- Aggiungere logging per fallback e eccezioni non bloccanti.
- Rafforzare la tipizzazione e la documentazione.
- Fornire esempi di override corretti e sbagliati.
- Implementare test di integrazione per la registrazione delle risorse.
- Introdurre versioning e validazione per le icone SVG.

### Esempi di override

**Corretto:**
```php
public function boot(): void
{
    parent::boot();
    // Estensioni specifiche...
}
```

**Sbagliato:**
```php
public function boot(): void
{
    // parent::boot() mancante!
    // ...
}
```

### Collegamenti
- [Best practices per i provider](./service-provider-best-practices.md)
- [Registrazione icone Blade](registerbladeicons.md)

## Gestione dei Path delle Traduzioni

**Regola:**
Per la registrazione delle traduzioni, utilizzare sempre l'action `GetModulePathByGeneratorAction` per ottenere il path della cartella `lang` del modulo. Non usare mai direttamente `module_path` o path hardcoded.

**Motivazione:**
- Garantisce coerenza e robustezza tra i moduli
- Permette fallback e validazione centralizzata
- Facilita la manutenzione e l'evoluzione della struttura dei moduli

**Esempio corretto:**
```php
try {
    $langPath = app(GetModulePathByGeneratorAction::class)->execute($this->name, 'lang');
    \Webmozart\Assert\Assert::string($langPath, 'Percorso lang non valido');
    $this->loadTranslationsFrom($langPath, $this->nameLower);
} catch (\Throwable $e) {
    $fallbackPath = base_path('Modules/'.$this->name.'/lang');
    $this->loadTranslationsFrom($fallbackPath, $this->nameLower);
}
```

**Esempio sbagliato:**
```php
$langPath = module_path($this->name, 'lang');
$this->loadTranslationsFrom($langPath, $this->nameLower);
```

**Nota:**
Applicare la stessa regola per la registrazione delle traduzioni JSON.

**Collegamento:**
Vedi anche [registerBladeIcons.md](registerbladeicons.md) per la gestione centralizzata dei path.

## Console Commands: Religione, Politica, Filosofia, Zen

### Principio
La registrazione dei comandi console è automatica: ogni comando presente in Console/Commands viene autoregistrato dalla base.

### Cosa NON fare
**NON** aggiungere mai manualmente:
```php
$this->commands([
    ...
]);
```

### Cosa fare
- Definire i comandi nella cartella Console/Commands
- Lasciare che la base li autoregistri

### Motivazione
- DRY, KISS, Zen, Politica, Filosofia: meno codice, più coerenza, meno errori
- La responsabilità è centralizzata

### Collegamenti correlati
- [Regola DRY](../../../.cursor/rules/dry.mdc)
- [Regola Zen](../../../.windsurf/rules/zen.mdc)
- [Regola KISS](../../../.windsurf/rules/kiss.mdc)
- [Politica](../../../.windsurf/rules/politica.mdc)
- [Filosofia](../../../.windsurf/rules/filosofia.mdc)

### Zen finale
> "Il miglior comando è quello che non devi mai registrare a mano."

---

## xotbasethemeserviceprovider

*Consolidated from: `xotbasethemeserviceprovider.md`*


---

## xotbasewidget

*Consolidated from: `xotbasewidget.md`*


## Panoramica

XotBaseWidget è la classe base per tutti i widget Filament nel progetto. Fornisce funzionalità comuni e standardizza l'implementazione dei widget.

## Caratteristiche

- Estende la classe base di Filament Widget
- Fornisce metodi comuni per tutti i widget
- Gestisce la configurazione standard dei widget
- Implementa pattern di sicurezza e autorizzazioni

## Utilizzo

```php
use Modules\Xot\Filament\Widgets\XotBaseWidget;

class MyCustomWidget extends XotBaseWidget
{
    protected static string $view = 'my-module::widgets.my-custom-widget';

    protected function getData(): array
    {
        return [
            'data' => $this->getWidgetData(),
        ];
    }
}
```

## Riferimenti

- [Documentazione Filament Widgets](modules/xot/project_docs/filament/widgets/index.md)
- [XotBaseWidget](Modules/Xot/app/Filament/Widgets/XotBaseWidget.php)

---

## xotdata

*Consolidated from: `xotdata.md`*


---

**Consolidated by:** Phase 2f intelligent merging
**Date:** 2026-08-04
