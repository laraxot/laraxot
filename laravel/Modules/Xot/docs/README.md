<<<<<<< HEAD
<<<<<<< HEAD
---
title: "Xot — La Base Sacra di Laraxot"
description: "Modulo fondamentale che fornisce funzionalità core e strutture per tutti gli altri moduli"
module: "Xot"
alias: "xot"
version: "1.0.0"
priority: 2
active: true
status: "core-foundation"
author: "Team Laraxot"
license: "Proprietary"
php_version: "^8.1"
core_version: "10.0"
dependencies: ["User", "Tenant"]
extends: []
extended_by: 46
documentation_date: "2026-05-27"
---

# Xot — La Base Sacra di Laraxot

## Scopo

Xot è il fondamento su cui poggiano tutti i 46 moduli della famiglia Laraxot. Non è un modulo come gli altri: è il **contratto fondamentale** che garantisce che un singolo cambiamento a `XotBaseResource` propaghi i suoi effetti su ogni modulo del sistema. Xot fornisce le classi base, le convenzioni, le utility e il pattern architetturale che rendono possibile la manutenibilità a scala di un ecosistema modulare di questa dimensione.

## Religione

Xot è trattato come un **sacro vincolo** non negoziabile. Le regole che impone non sono suggerimenti: sono i giuramenti che legano l'ecosistema. La religione di Xot si esprime in sette non-negoziabili:

### 1. "Never Filament Directly"

**Regola**: Filament deve essere sempre esteso attraverso Xot.
**Perché**: i suoi i 
- Resource → `XotBaseResource`
- Page → `XotBasePage`
- Widget → `XotBaseWidget` (e varianti: `XotBaseChartWidget`, `XotBaseStatsOverviewWidget`, `XotBaseTableWidget`)
- Action → `XotBaseAction`
- List/Create/Edit/View → `XotBaseListRecords`, `XotBaseCreateRecord`, `XotBaseEditRecord`, `XotBaseViewRecord`
- PanelProvider → `XotBasePanelProvider`
- Dashboard → `XotBaseDashboard`
- Login/Register → `XotBaseLogin`, `XotBaseRegister`
=======
=======
>>>>>>> 28b0298a (fix: phpstan issues)
# Documentation

This directory contains documentation for the module.

## Structure

- **architecture.md** - Module architecture and design patterns
- **README.md** - This file

## Guidelines

Documentation should be:
- Clear and concise
- Example-driven
- Updated with code changes
- Use Markdown format (.md)

---

<!-- Merged from readme.md, which collided with this file on case-insensitive filesystems. -->

# 🏗️ **Xot Module** - Il Cuore del Framework Laraxot

[![Laravel 12.x](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com/)
[![Filament 4.x](https://img.shields.io/badge/Filament-4.x-blue.svg)](https://filamentphp.com/)
[![PHP 8.3](https://img.shields.io/badge/PHP-8.3-blueviolet.svg)](https://www.php.net/)
[![PHPStan Level 10](https://img.shields.io/badge/PHPStan-Level%2010-brightgreen.svg)](https://phpstan.org/)
[![Modular Architecture](https://img.shields.io/badge/Architecture-Modular%20Monolith-yellow.svg)](https://martinfowler.com/articles/modular-monolith.html)

> **🚀 Modulo Xot**: Framework base e cuore architetturale di Laraxot - fornisce classi base, traits, convenzioni e infrastruttura core per tutti i moduli dell'ecosistema.

## 📋 **Panoramica**

Il modulo **Xot** è il **framework base** di Laraxot PTVX, un ecosistema modulare basato su **Laravel 12** e **Filament 4**, progettato per applicazioni enterprise. Fornisce gli strumenti fondamentali e i pattern architetturali per garantire coerenza, estensibilità e manutenibilità in tutto il progetto.

### Principi Fondamentali
- **Modularità**: Ogni funzionalità è organizzata in moduli indipendenti e autoconsistenti.
- **Coerenza**: Adozione di una struttura uniforme, convenzioni di naming e best practice standardizzate.
- **Estensibilità**: Progettato per facilitare l'aggiunta di nuovi moduli e l'espansione delle funzionalità esistenti.
- **Manutenibilità**: Codice pulito, ben documentato e supportato da strumenti di analisi statica.

## ⚡ **Architettura Core**

### 🏗️ **Base Classes Pattern**
Tutti i componenti principali dei moduli devono estendere le classi base fornite da Xot per ereditare funzionalità comuni e garantire coerenza.
>>>>>>> f7400a95 (Story 3.1: Add explicit @var type hints to array variables in HasXotTable.php)

**Implementazione**:
```php
<<<<<<< HEAD
class MyResource extends XotBaseResource
{
    public static $model = MyModel::class;
    // ...
}
```

### 2. "Actions, Not Services"

**Regola**: la logica di business vive in `Actions` con metodo `execute()` e `use QueueableAction`.
**Perché**: standard location, metodo standard, queueable di default. Prevedibilità across moduli.

**Giusto**:
```php
namespace Modules\{Modulo}\Actions;

class MyAction
{
    use QueueableAction;

    public function execute(...): mixed
    {
        // business logic qui
=======
// Esempio di una Resource Filament
use Modules\Xot\Filament\Resources\XotBaseResource;

class UserResource extends XotBaseResource
{
    protected static ?string $model = User::class;
    
    // Il metodo table() e form() NON devono essere sovrascritti
    // se non per aggiungere logica specifica, ma la base
    // è già fornita da XotBaseResource.
}
```

### 🔧 **Traits Ecosystem**
Xot fornisce un ricco ecosistema di Trait per aggiungere funzionalità comuni ai modelli e ad altre classi.
- **HasXotTable**: Aggiunge funzionalità avanzate alle tabelle Filament.
- **HasUuid**: Gestisce automaticamente UUID come chiavi primarie.
- **HasMedia**: Integra Spatie Media Library con convenzioni standard.
- **HasStates**: Fornisce una gestione degli stati per i modelli.
- **TransTrait**: Semplifica le traduzioni dinamiche.

### 📦 **Service Provider Pattern**
I Service Provider di ogni modulo estendono `XotBaseServiceProvider`, che automatizza la registrazione di:
- Migrations, Views, Translations, e Config
- Routes (web.php, api.php)
- Filament Resources, Pages, e Widgets
- Comandi Artisan e Policies

## 🎯 **Funzionalità Principali**

### ⚡ **Actions Framework**
Un pattern standardizzato per incapsulare la business logic in classi riutilizzabili e testabili.
```php
use Modules\Xot\Actions\XotBaseAction;

class CreateUserAction extends XotBaseAction
{
    public function execute(array $data): User
    {
        $user = User::create($data);
        $this->logActivity('user.created', $user); // Logging automatico
        event(new UserCreated($user)); // Dispatching eventi
        return $user;
<<<<<<< HEAD
>>>>>>> f7400a95 (Story 3.1: Add explicit @var type hints to array variables in HasXotTable.php)
=======
=======
---
title: "Xot — La Base Sacra di Laraxot"
description: "Modulo fondamentale che fornisce funzionalità core e strutture per tutti gli altri moduli"
module: "Xot"
alias: "xot"
version: "1.0.0"
priority: 2
active: true
status: "core-foundation"
author: "Team Laraxot"
license: "Proprietary"
php_version: "^8.1"
core_version: "10.0"
dependencies: ["User", "Tenant"]
extends: []
extended_by: 46
documentation_date: "2026-05-27"
---

# Xot — La Base Sacra di Laraxot

## Scopo

Xot è il fondamento su cui poggiano tutti i 46 moduli della famiglia Laraxot. Non è un modulo come gli altri: è il **contratto fondamentale** che garantisce che un singolo cambiamento a `XotBaseResource` propaghi i suoi effetti su ogni modulo del sistema. Xot fornisce le classi base, le convenzioni, le utility e il pattern architetturale che rendono possibile la manutenibilità a scala di un ecosistema modulare di questa dimensione.

## Religione

Xot è trattato come un **sacro vincolo** non negoziabile. Le regole che impone non sono suggerimenti: sono i giuramenti che legano l'ecosistema. La religione di Xot si esprime in sette non-negoziabili:

### 1. "Never Filament Directly"

**Regola**: Filament deve essere sempre esteso attraverso Xot.
**Perché**: i suoi i 
- Resource → `XotBaseResource`
- Page → `XotBasePage`
- Widget → `XotBaseWidget` (e varianti: `XotBaseChartWidget`, `XotBaseStatsOverviewWidget`, `XotBaseTableWidget`)
- Action → `XotBaseAction`
- List/Create/Edit/View → `XotBaseListRecords`, `XotBaseCreateRecord`, `XotBaseEditRecord`, `XotBaseViewRecord`
- PanelProvider → `XotBasePanelProvider`
- Dashboard → `XotBaseDashboard`
- Login/Register → `XotBaseLogin`, `XotBaseRegister`

**Implementazione**:
```php
class MyResource extends XotBaseResource
{
    public static $model = MyModel::class;
    // ...
}
```

### 2. "Actions, Not Services"

**Regola**: la logica di business vive in `Actions` con metodo `execute()` e `use QueueableAction`.
**Perché**: standard location, metodo standard, queueable di default. Prevedibilità across moduli.

**Giusto**:
```php
namespace Modules\{Modulo}\Actions;

class MyAction
{
    use QueueableAction;

    public function execute(...): mixed
    {
        // business logic qui
>>>>>>> 7f6cf6be (.)
>>>>>>> 28b0298a (fix: phpstan issues)
    }
}
```

<<<<<<< HEAD
<<<<<<< HEAD
**Mai**:
```php
namespace app/Services; // ❌ vietato
class SomethingService {
    // business logic qui
}
```

### 3. `phpstan.neon` is Sacred

**Regola**: il file `phpstan.neon` **è modificato** da nessun modulo. No `ignoreErrors`, no module-specific overrides. Gli errori si correggono nel codice, mai silenziati.

**Perché**: la static analysis è la fede. Ignorare errori difende il punto.

### 4. "Folio e Volt per il pubblico, Filament per l'admin"

**Regola**: separazione netta: pagine pubbliche in `resources/views/pages/` (Folio), pagine admin in `Modules/{Mod}/app/Filament/`.

**Perché**: Folio è file-based e semplice per public sites. Filament è declarative e reusable per admin.

### 5. "Traduzioni dai file di lingua"

**Regola**: mai hardcoded labels:
```php
// SBAGLIATO
$input->label('User Email');

// GIUSTO
->label(trans('user::user.email'))  // o
->label(trans_string('user.email'))
```

**Perché**: translations are centralized, maintainable, and reusable. A label change updates everywhere.

### 6. "Base Classes for Everything"

**Regola**: ogni classe eredita da una XotBase: `XotBaseModel`, `XotBaseResource`, `XotBasePage`, `XotBasePanelProvider`, `XotBaseMigration`, `XotBaseServiceProvider`.

**Perché**: Consistency. Shared features (created_by, updated_by, auto-discovery) are inherited, not reimplemented.

### 7. "La cartella docs è la memoria"

**Regola**: la `docs/` cartella è la fonte di verità per:
- Architecture decisions
- Conventions
- Examples
- Integration points

**Aggiorna `docs/` first.** Code follows documentation, not the reverse.

**Perché**: Future maintainers (and future you) should read before writing code. Undocumented code is technical debt.

## Filosofia

> **"DRY e KISS portati alla loro conclusione logica."**

- Un posto per aggiornare = un solo framework change
- Standard naming = leggibilità immediata
- Declared constraints = safer refactoring
- Consistency = no surprises

La filosofia di Xot è l'**astrazione come servizio**. Le classi base non sono wrapper pigri: sono interfacce stabilizzanti che intercettano i cambiamenti dell'ecosistema Filament/Laravel prima che raggiungano i moduli. Xot crede che la complessità si gestisca non combattendola ma **interponendosi** tra essa e chi la usa.

Il sistema è progettato per l'**ereditarietà composita**: ogni modulo eredita struttura, convenzioni e pattern da Xot, ma è libero di estendere e specializzare. Il campo `type` nella tabella users (`Parental\HasChildren`) è l'incarnazione di questa filosofia: una singola tabella, molteplici forme identitarie.

## Politica

- **Gerarchia dei moduli**: Xot sta sopra tutti. Ogni dipendenza transitiva passa per Xot o è esplicitamente dichiarata in `module.json`.
- **Nessun fork**: i moduli non forkano Xot. Lo estendono. Questo garantisce che un fix in Xot sia istantaneamente disponibile everywhere.
- **PHPStan Level 10 non negoziabile**: la rigidità tipativa è politica di qualità, non preference tecnica.
- **Comunità e contributi**: il `docs/` è la fonte della verità. Un PR senza documentazione è un rifiuto implicito.
- **Versionamento**: `minimumCoreVersion: "10.0"` significa che Xot traccia la versione del core Laravel, non la versione del modulo.

## Zen

> **"Il token scade, il log resta, l'utente è sempre lo stesso id."**

Lo Zen di Xot è la **prevedibilità assoluta**. Uno sviluppatore che apre un modulo sconosciuto trova sempre:
- La stessa struttura di cartelle
- Gli stessi pattern di naming
- Le stesse classi base
- Lo stesso flusso di azioni

Questa familiarità è il dono di Xot. Non è un framework: è un **linguaggio** che tutti i moduli parlano fluentemente.

## Perché esiste

Ogni ecosistema modulare di grandi dimensioni affronta il problema della **frammentazione**: i moduli divergono nei pattern, nelle convenzioni e nelle astrazioni, rendendo il sistema inmaneggiabile. Xot esiste per eliminare questo problema alla radice, imponendo un singolo contratto che tutti devono rispettare. La sua esistenza è la risposta al costo marginale di manutenzione che cresce con il numero di moduli.

## Cosa Mancherebbe (Gap Analysis)

| Gap | Severità | Suggerimento |
|-----|----------|--------------|
| Nessun modulo di osservabilità nativo (metrics, tracing, distributed tracing) | Alta | Creare un modulo `Observability` che estenda Xot con dashboard, alerting e distributed tracing |
| Manca un modulo di event sourcing/decision log | Alta | Aggiungere `EventSourcing` come sottodominio di Xot o modulo dedicato |
| Nessun modulo di feature flags | Media | Creare `FeatureFlags` basato su `Extra` model con toggle runtime |
| Assenza di modulo di audit trail centralizzato | Media | Estendere `Activity` o creare `AuditTrail` come Xot submodule |
| Nessun pattern CQRS esplicito | Media | Documentare se `Actions` coprono il pattern CQRS o se serve un modulo `Cqrs` |
| Nessun modulo per task scheduling avanzato | Bassa | Creare `Scheduler` con cron visuale e dashboard |
| Manca documentazione `docs/archived/project-religion-politics-zen.md` referenziata | Bassa | Verificare esistenza o creare questo file storico |

## Proposte di Split/Merge (Solo Documentazione)

### Split Consigliati

1. **Xot → XotCore + XotFilament** — Separare le astrazioni core (model, migration, service provider) dalle astrazioni Filament (resource, page, widget, panel provider). Motivo: ridurre il coupling tra il layer di persistenza e il layer di presentazione admin. Se Filament cambia versione major, solo `XotFilament` deve essere aggiornato.

2. **Xot → XotCore + XotActions** — Estrazione del sistema di Actions in un modulo separato per renderlo riutilizzabile indipendentemente dal framework base.

### Merge Consigliati

1. **Activity + AuditTrail** — Se `Activity` traccia solo azioni utente e `AuditTrail` traccia cambiamenti dati, creare un modulo `Audit` con una visione unificata.

2. **Job + Notify + Email** — Se `Job` gestisce code, `Notify` notifiche e `Email` invio email, fondere in `Communications` con un layer di astrazione comune (message bus).

---

*Documento generato secondo le convenzioni del progetto — modulo `Xot` — data 2026-05-27*
=======
=======
>>>>>>> 28b0298a (fix: phpstan issues)
### 🏷️ **Enums System**
Le Enum di Xot implementano `XotBaseEnum`, che fornisce traduzioni automatiche e altri helper.
```php
use Modules\Xot\Enums\XotBaseEnum;

enum UserStatus: string implements XotBaseEnum
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';

    public function getLabel(): string
    {
        // Traduzione gestita centralmente
        return __('xot::enums.user_status.'.$this->value);
    }
}
```

## 🛠️ **Sviluppo e Qualità**

### Convenzioni
- **Namespace**: I namespace dei moduli **NON** devono includere il segmento `app`.
- **Tipizzazione Forte**: Utilizzo di `declare(strict_types=1);` e type hints rigorosi in tutto il codice.
- **File di Traduzione**: Seguire la struttura espansa `['label' => '...', 'tooltip' => '...']`.

### Strumenti di Qualità
- **PHPStan**: Livello 10. La configurazione è in `phpstan.neon`.
- **Pest**: Utilizzato per i test della business logic nei moduli core.
- **Laravel Pint**: Formattazione del codice secondo lo standard PSR-12 e le convenzioni Laraxot.

Esegui i controlli di qualità dalla root del progetto Laravel:
```bash
./vendor/bin/phpstan analyse Modules/Xot --level=max
./vendor/bin/pest Modules/Xot/tests
./vendor/bin/pint
```

### 🏆 PHPStan Level 10 Compliance (Dicembre 2025)

**Status**: ✅ **0 Errori** (16 → 0)
**Approccio**: Fix, Don't Ignore
**Baseline**: Nessuno

Il modulo Xot ha raggiunto la piena conformità PHPStan Level 10 senza compromessi:
- Zero baseline entries
- Nessuna modifica a phpstan.neon
- Solo correzioni reali del codice
- Type safety al 100%

**Documentazione dettagliata**:
- [PHPStan Patterns Dec 2025](./phpstan-patterns-dec-2025.md)
- [PHPStan Level 10 Success](../../../docs/phpstan-level-10-success.md)

## 🗺️ **Roadmap**
1.  **Consolidamento Documentazione**: Unificare e semplificare la documentazione di tutti i moduli (obiettivo: 500 → 120 file).
2.  **Automazione Script di Merge**: Creare script per la gestione automatica dei conflitti comuni e la validazione pre-commit.
3.  **Aumento Test Coverage**: Portare la copertura dei test per i moduli core sopra il 90%.
4.  **Dashboard Health Check**: Introdurre una dashboard per monitorare lo stato di salute e la compliance di tutti i moduli.

## 🔗 **Link Utili**
- [CHANGELOG](./CHANGELOG.md)
- [CHANGELOG](./changelog.md)
- [Guida alla Risoluzione dei Conflitti Git](../../../bashscripts/docs/git-conflict-resolution-guide.md)
- [Convenzioni sui Namespace](./namespace_conventions.md)
- [Linee Guida per il Testing](./testing.md)


---

## Contenuto assorbito da `readme.md`

# Modulo Xot - Documentazione Consolidata
---
title: "Xot Module** - Il Cuore del Framework Laraxot"
module: xot
type: integration
tags: [integrations, modules, xot]
created: 2026-08-24
updated: 2026-08-24
---

# 🏗️ **Xot Module** - Il Cuore del Framework Laraxot

## 🎯 Panoramica
Modulo core del sistema Laraxot che fornisce classi base e funzionalità comuni per tutti gli altri moduli.

## 📚 Documentazione Principale

### **Core e Architettura**
- [Architettura e Best Practices](core/architecture.md)
- [Convenzioni di Naming](core/naming-conventions.md)
- [Namespace e Autoload](core/namespace-rules.md)
- [Struttura Moduli](core/module-structure.md)

### **Filament e UI**
- [Best Practices Filament](filament/best-practices.md)
- [Risorse e Relation Manager](filament/resources.md)
- [Azioni e Componenti](filament/actions.md)
- [Dashboard e Pagine](filament/dashboard.md)

### **Sviluppo e Qualità**
- [PHPStan e Analisi Statica](development/phpstan-guide.md)
- [Testing e Best Practices](development/testing.md)
- [Migrazioni e Database](development/migrations.md)
- [Service Provider](development/service-providers.md)

### **Integrazione e Utilità**
- [Traduzioni e Localizzazione](utils/translations.md)
- [Gestione Errori](utils/error-handling.md)
- [Eventi e Code](utils/events.md)
- [Sicurezza](utils/security.md)

### **Template e Esempi**
- [Template Classi Base](templates/base-classes.md)
- [Template Service Provider](templates/service-provider.md)
- [Template Filament](templates/filament.md)

## 🚀 Quick Start

1. **Estendi le classi base appropriate**
2. **Segui le convenzioni di naming**
3. **Utilizza i template standardizzati**
4. **Rispetta le regole PHPStan**

## 🔗 Collegamenti

- [Documentazione Root](../../docs/)
- [Best Practices Sistema](../../docs/core/best-practices.md)
- [Convenzioni Sistema](../../docs/core/conventions.md)

---

**Ultimo aggiornamento:** Gennaio 2025  
**Versione:** 2.0 - Consolidata DRY + KISS
<<<<<<< HEAD
>>>>>>> f7400a95 (Story 3.1: Add explicit @var type hints to array variables in HasXotTable.php)
=======
=======
**Mai**:
```php
namespace app/Services; // ❌ vietato
class SomethingService {
    // business logic qui
}
```

### 3. `phpstan.neon` is Sacred

**Regola**: il file `phpstan.neon` **è modificato** da nessun modulo. No `ignoreErrors`, no module-specific overrides. Gli errori si correggono nel codice, mai silenziati.

**Perché**: la static analysis è la fede. Ignorare errori difende il punto.

### 4. "Folio e Volt per il pubblico, Filament per l'admin"

**Regola**: separazione netta: pagine pubbliche in `resources/views/pages/` (Folio), pagine admin in `Modules/{Mod}/app/Filament/`.

**Perché**: Folio è file-based e semplice per public sites. Filament è declarative e reusable per admin.

### 5. "Traduzioni dai file di lingua"

**Regola**: mai hardcoded labels:
```php
// SBAGLIATO
$input->label('User Email');

// GIUSTO
->label(trans('user::user.email'))  // o
->label(trans_string('user.email'))
```

**Perché**: translations are centralized, maintainable, and reusable. A label change updates everywhere.

### 6. "Base Classes for Everything"

**Regola**: ogni classe eredita da una XotBase: `XotBaseModel`, `XotBaseResource`, `XotBasePage`, `XotBasePanelProvider`, `XotBaseMigration`, `XotBaseServiceProvider`.

**Perché**: Consistency. Shared features (created_by, updated_by, auto-discovery) are inherited, not reimplemented.

### 7. "La cartella docs è la memoria"

**Regola**: la `docs/` cartella è la fonte di verità per:
- Architecture decisions
- Conventions
- Examples
- Integration points

**Aggiorna `docs/` first.** Code follows documentation, not the reverse.

**Perché**: Future maintainers (and future you) should read before writing code. Undocumented code is technical debt.

## Filosofia

> **"DRY e KISS portati alla loro conclusione logica."**

- Un posto per aggiornare = un solo framework change
- Standard naming = leggibilità immediata
- Declared constraints = safer refactoring
- Consistency = no surprises

La filosofia di Xot è l'**astrazione come servizio**. Le classi base non sono wrapper pigri: sono interfacce stabilizzanti che intercettano i cambiamenti dell'ecosistema Filament/Laravel prima che raggiungano i moduli. Xot crede che la complessità si gestisca non combattendola ma **interponendosi** tra essa e chi la usa.

Il sistema è progettato per l'**ereditarietà composita**: ogni modulo eredita struttura, convenzioni e pattern da Xot, ma è libero di estendere e specializzare. Il campo `type` nella tabella users (`Parental\HasChildren`) è l'incarnazione di questa filosofia: una singola tabella, molteplici forme identitarie.

## Politica

- **Gerarchia dei moduli**: Xot sta sopra tutti. Ogni dipendenza transitiva passa per Xot o è esplicitamente dichiarata in `module.json`.
- **Nessun fork**: i moduli non forkano Xot. Lo estendono. Questo garantisce che un fix in Xot sia istantaneamente disponibile everywhere.
- **PHPStan Level 10 non negoziabile**: la rigidità tipativa è politica di qualità, non preference tecnica.
- **Comunità e contributi**: il `docs/` è la fonte della verità. Un PR senza documentazione è un rifiuto implicito.
- **Versionamento**: `minimumCoreVersion: "10.0"` significa che Xot traccia la versione del core Laravel, non la versione del modulo.

## Zen

> **"Il token scade, il log resta, l'utente è sempre lo stesso id."**

Lo Zen di Xot è la **prevedibilità assoluta**. Uno sviluppatore che apre un modulo sconosciuto trova sempre:
- La stessa struttura di cartelle
- Gli stessi pattern di naming
- Le stesse classi base
- Lo stesso flusso di azioni

Questa familiarità è il dono di Xot. Non è un framework: è un **linguaggio** che tutti i moduli parlano fluentemente.

## Perché esiste

Ogni ecosistema modulare di grandi dimensioni affronta il problema della **frammentazione**: i moduli divergono nei pattern, nelle convenzioni e nelle astrazioni, rendendo il sistema inmaneggiabile. Xot esiste per eliminare questo problema alla radice, imponendo un singolo contratto che tutti devono rispettare. La sua esistenza è la risposta al costo marginale di manutenzione che cresce con il numero di moduli.

## Cosa Mancherebbe (Gap Analysis)

| Gap | Severità | Suggerimento |
|-----|----------|--------------|
| Nessun modulo di osservabilità nativo (metrics, tracing, distributed tracing) | Alta | Creare un modulo `Observability` che estenda Xot con dashboard, alerting e distributed tracing |
| Manca un modulo di event sourcing/decision log | Alta | Aggiungere `EventSourcing` come sottodominio di Xot o modulo dedicato |
| Nessun modulo di feature flags | Media | Creare `FeatureFlags` basato su `Extra` model con toggle runtime |
| Assenza di modulo di audit trail centralizzato | Media | Estendere `Activity` o creare `AuditTrail` come Xot submodule |
| Nessun pattern CQRS esplicito | Media | Documentare se `Actions` coprono il pattern CQRS o se serve un modulo `Cqrs` |
| Nessun modulo per task scheduling avanzato | Bassa | Creare `Scheduler` con cron visuale e dashboard |
| Manca documentazione `docs/archived/project-religion-politics-zen.md` referenziata | Bassa | Verificare esistenza o creare questo file storico |

## Proposte di Split/Merge (Solo Documentazione)

### Split Consigliati

1. **Xot → XotCore + XotFilament** — Separare le astrazioni core (model, migration, service provider) dalle astrazioni Filament (resource, page, widget, panel provider). Motivo: ridurre il coupling tra il layer di persistenza e il layer di presentazione admin. Se Filament cambia versione major, solo `XotFilament` deve essere aggiornato.

2. **Xot → XotCore + XotActions** — Estrazione del sistema di Actions in un modulo separato per renderlo riutilizzabile indipendentemente dal framework base.

### Merge Consigliati

1. **Activity + AuditTrail** — Se `Activity` traccia solo azioni utente e `AuditTrail` traccia cambiamenti dati, creare un modulo `Audit` con una visione unificata.

2. **Job + Notify + Email** — Se `Job` gestisce code, `Notify` notifiche e `Email` invio email, fondere in `Communications` con un layer di astrazione comune (message bus).

---

*Documento generato secondo le convenzioni del progetto — modulo `Xot` — data 2026-05-27*
>>>>>>> 7f6cf6be (.)
>>>>>>> 28b0298a (fix: phpstan issues)
