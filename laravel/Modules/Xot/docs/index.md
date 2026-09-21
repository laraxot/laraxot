---
title: "Xot Module Documentation Index"
module: "Xot"
type: index
created: 2026-06-11
updated: 2026-06-11
---

# 📚 Xot Module - Documentation Index

**Quick Navigation**: [Overview](#overview) | [Setup](#setup) | [Architecture](#architecture) | [Testing](#testing) | [Models](#models) | [Resources](#resources)

---

## Overview

- **Status**: Stable (Core Module)
- **Test Coverage**: Excellent (108 tests: 97 unit + 11 feature)
- **Repository**: `git@github.com:laraxot/module_xot_fila5.git`
- **Last Updated**: 2026-06-11
- **Purpose**: Core module providing base architecture, utilities, and common patterns

**Module Stats**:
- Models: 45
- Test Files: 108
- Documentation Files: 1957

---

## Setup

### Installation
```bash
php artisan module:install Xot
```

### Configuration
Location: `config/xot.php`

Key Settings:
- Module base paths
- Cache configuration
- Service provider settings
- Utility options

---

## Architecture

### Directory Structure
```
Xot/
├── app/
│   ├── Actions/        # Business logic (reusable actions)
│   ├── Models/         # Eloquent models (45 total)
│   ├── Services/       # Domain services
│   ├── Contracts/      # Interfaces
│   ├── Providers/      # Service providers
│   ├── Filament/       # Admin panel integration
│   ├── Support/        # Utility classes
│   └── ...
├── database/
│   ├── migrations/
│   ├── factories/
│   └── seeders/
├── resources/
│   ├── views/
│   └── lang/
├── routes/
├── tests/
│   ├── Unit/           # 97 unit tests
│   └── Feature/        # 11 feature tests
└── docs/              # Module documentation
```

### Core Models (45 Total)

#### Base Patterns
- Base model traits and contracts

#### Domain Models
- Core business entities
- Cross-module references

#### Support Models
- Utility and helper models

---

## Architecture Patterns

### Service Layer
Xot provides reusable service patterns used across modules:
- Repository patterns
- Action classes
- Query builders
- Event handling

### Contract-Based Design
Interfaces defined in `app/Contracts/` for:
- Repository contracts
- Service contracts
- Event contracts

### Trait System
Common traits for:
- Model behaviors
- Query scoping
- Event handling
- Validation

---

## Testing

### Coverage Status
**Current**: Excellent (108 tests)  
- Unit Tests: 97 files
- Feature Tests: 11 files  
**Target**: Maintain 80%+

### Running Tests
```bash
cd laravel
./vendor/bin/pest Modules/Xot/tests/ --coverage
```

### Test Structure
- `tests/Unit/` - Core functionality, models, services
  - 97 unit test files covering models, actions, services
  - Isolated, fast execution
- `tests/Feature/` - Integration tests
  - 11 feature test files for end-to-end scenarios
  - Database interactions, event flows

### Key Test Areas
1. **Model Tests** — 45 models thoroughly tested
2. **Service Tests** — Business logic validation
3. **Action Tests** — Reusable action execution
4. **Query Tests** — Custom query builder methods
5. **Event Tests** — Event dispatching and handling

---

## Models

### Total: 45 Models

Xot models provide core functionality for:
- Base entity definitions
- Relationship patterns
- Trait implementations
- Common attributes
- Query scopes

See `app/Models/` directory for complete list.

---

## Documentation Files

### Primary Documentation
| File | Purpose |
|------|---------|
| [README.md](./README.md) | Quick start guide |
| [CHANGELOG.md](./CHANGELOG.md) | Version history |

### Knowledge Base
- **llm-wiki/** — Comprehensive knowledge base (1957 files)
  - Architecture guides
  - Service patterns
  - Integration examples
  - Best practices
  - Troubleshooting

---

## Key Files Reference

### Configuration
- `config/xot.php` - Main configuration

### Core Classes
- `app/Support/` - Utility classes
- `app/Contracts/` - Interface definitions

### Database
- `database/migrations/` - Schema migrations
- `database/factories/` - Model factories (45 models)
- `database/seeders/` - Data seeders

### Routes
- `routes/api.php` - API routes
- `routes/web.php` - Web routes

### Views
- `resources/views/` - Blade templates
- `resources/views/components/` - Reusable components

---

## Usage Patterns

### Using Xot Models
```php
// Import Xot models
use Xot\Models\YourModel;

// Use built-in traits
class YourModel extends Model {
    use Xot\App\Traits\HasTimestamps;
    use Xot\App\Traits\HasUuid;
}
```

### Using Xot Services
```php
// Inject Xot services
public function __construct(private SomeXotService $service) {}

// Call service methods
$result = $this->service->doSomething();
```

### Using Xot Actions
```php
// Execute reusable actions
$action = app(SomeXotAction::class);
$result = $action->execute($data);
```

---

## Integration Points

### Other Modules Depending on Xot
- **AI** — Uses core utilities
- **Geo** — Extends Xot models
- **Fixcity** — Uses services and traits
- **User** — Extends Xot base models
- **Cms** — Implements Xot patterns
- **Notify** — Uses Xot events
- **Job** — Uses Xot queue patterns

---

## Priority Actions

### For New Developers
1. **Start**: Read [README.md](./README.md)
2. **Explore**: Browse `app/Models/` for core entities
3. **Understand**: Study `app/Contracts/` for interfaces
4. **Learn**: Check `app/Traits/` for common behaviors

### For Contributors
1. Follow base module patterns in Xot
2. Use provided traits and contracts
3. Write tests alongside code (excellent test coverage!)
4. Document new services in llm-wiki

---

## Quick Commands

```bash
# Run all tests (108 total)
./vendor/bin/pest Modules/Xot/tests/ --coverage

# Run only unit tests (97)
./vendor/bin/pest Modules/Xot/tests/Unit/ --coverage

# Run only feature tests (11)
./vendor/bin/pest Modules/Xot/tests/Feature/ --coverage

# Check code quality
./vendor/bin/phpstan analyse Modules/Xot/

# Format code
./vendor/bin/pint Modules/Xot/

# Generate model
php artisan make:model --module=Xot

# Create migration
php artisan make:migration --module=Xot
```

---

## Resources

### External Links
- [GitHub Repository](https://github.com/laraxot/module_xot_fila5)
- [Issues & Discussions](https://github.com/laraxot/module_xot_fila5/issues)
- [Laravel Modules Documentation](https://laravelmodules.com/)

### Internal References
- [Base Module Guide](../../docs/wiki/modules/base-module-guide.md)
- [BMAD Workflow](../../docs/wiki/bmad/workflow.md)
- [Testing Standards](../../docs/wiki/testing/standards.md)
- [Xot Knowledge Base](./llm-wiki/) — 1957 wiki files

---

## Contributing

For contribution guidelines, see [CONTRIBUTING.md](../../CONTRIBUTING.md)

When contributing to Xot:
1. Maintain test coverage (currently excellent at 108 tests)
2. Follow patterns established in core models
3. Update CHANGELOG.md
4. Document in llm-wiki/ if adding new patterns

---

**Module Stats Summary**
- 45 Models (thoroughly documented)
- 108 Tests (97 unit + 11 feature)
- 1957 Documentation files
- 100% Test Coverage Focus

---

**Last Updated**: 2026-06-11  
*Generated by Module Documentation Improver Agent*

## Discussioni architetturali — 2026-09-11

Tre sessioni indipendenti hanno analizzato la stessa domanda (XotBaseManageRelatedRecords
dovrebbe delegare form/table alla Resource correlata, come XotBaseResource fa per se
stessa?) nello stesso giorno. Punto di ingresso unico, in ordine di lettura consigliato:

1. [architecture/xotbasemanagerelatedrecords-convention-over-configuration-brainstorm.md](architecture/xotbasemanagerelatedrecords-convention-over-configuration-brainstorm.md) — censimento delle 8 pagine, verifica dal vivo del confine pannello/modulo, percentuali.
2. [architecture/xotbasemanagerelatedrecords-remove-traits-addendum.md](architecture/xotbasemanagerelatedrecords-remove-traits-addendum.md) — perche' `HasXotTable` non va tolta (fondazione condivisa con `XotBaseResourceTable`).
3. [architecture/xotbasemanagerelatedrecords-form-table-delegation-feasibility.md](architecture/xotbasemanagerelatedrecords-form-table-delegation-feasibility.md) — prova tecnica: un metodo di classe vince su un trait `final`; **corretto 2026-09-11 (bis)**: `$relatedResource` oggi e' quasi inerte per columns/actions perche' `HasXotTable::table()` sovrascrive dopo (vedi vendor trace in `filament/...brainstorming.md`).
4. [filament/manage-related-records-resource-delegation-brainstorming.md](filament/manage-related-records-resource-delegation-brainstorming.md) — traccia vendor completa (`makeTable()` → `configureTable()` → `Resource::table()`); conferma con evidenza di codice che `$this->getResource()` risolve la Resource **proprietaria** (mai quella correlata).
5. Prima bozza, superata: [xotbasemanagerelatedrecords-filament-demo-philosophy.md](xotbasemanagerelatedrecords-filament-demo-philosophy.md).

Story BMAD collegate: [stories/xotbasemanagerelatedrecords-convention-over-configuration.story.md](stories/xotbasemanagerelatedrecords-convention-over-configuration.story.md) (link corretto 2026-09-11: puntava a `11.1.manage-related-records-convention-over-configuration.story.md`, mai esistito — nome giusto senza prefisso numerico), [stories/manage-related-records-convention-over-configuration.story.md](stories/manage-related-records-convention-over-configuration.story.md), [stories/manage-related-records-resource-delegation.story.md](stories/manage-related-records-resource-delegation.story.md). Nessuna implementazione in nessuno dei tre filoni. Aggiornamento 2026-09-11 (v5): [[xotbasemanagerelatedrecords-v5-tutti-gli-hook-non-solo-colonne]] — il contratto va esteso a getTableActions/Filters/BulkActions, non solo colonne. IdColumn/TimestampColumn: [Modules/UI/docs/stories/8.1.id-timestamp-columns-extraction.story.md](../../UI/docs/stories/8.1.id-timestamp-columns-extraction.story.md).

- [XotBaseManageRelatedRecords.php — proposta completa con PHPDoc](app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php.md): delega completa, coordinamento nella story, finding di review e limiti di migrazione.

**Aggiornamento 2026-09-11 (stesso giorno, piu' tardi)**: il link a
`stories/11.1.manage-related-records-convention-over-configuration.story.md`
sopra e' rotto (il file non esiste in `stories/`, solo la versione senza
prefisso numerico) — non corretto qui per non alterare la riga originale,
segnalato. La proposta corrente (quinta direzione, non implementata) e' in
fondo a
[XotBaseManageRelatedRecords.php.md](app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php.md)
("REVISIONE 2026-09-11 (quinta direzione)"): `form()`/`table()` delegano
per intero alla Resource correlata (mai bridge per singolo hook), con
`getTableColumns()`/`getTableHeaderActions()` come unici due override
point per-pagina. Story canonica aggiornata:
[stories/xotbasemanagerelatedrecords-convention-over-configuration.story.md](stories/xotbasemanagerelatedrecords-convention-over-configuration.story.md)
(issue/discussion #115/#117). Esiste anche un secondo filone di tracking
GitHub sullo stesso argomento, issue/discussion #112/#114 (vedi
[stories/manage-related-records-resource-delegation.story.md](stories/manage-related-records-resource-delegation.story.md))
— non riconciliato in un'unica issue per evitare di chiudere tracking
altrui senza conferma, ma i due filoni descrivono la stessa decisione.

**ERRORE CRITICO TROVATO (2026-09-11)**: GetRelatedResourceClassAction fallisce
con "Nessuna Resource correlata risolvibile per ManageContacts". Probabile
causa: ambiguità tra le due ContactResource (Quaeris e Notify) o mancanza
di form/table class. Analisi completa senza implementazione in
[XotBaseManageRelatedRecords.php.md](app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php.md)
sezione "ERRORE CRITICO TROVATO". Story riaperta come 'needs-followup'.
Coordinate GitHub: issue #115, discussion #117.

**Aggiornamento 2026-09-11 (sera) — perche' questa sezione era sparita**:
questo file non era mai stato committato oltre il boilerplate iniziale
("Generated by Module Documentation Improver Agent", HEAD); tutto il
contenuto sopra viveva SOLO come modifica non committata. Un'altra sessione
ha riportato il working tree a HEAD (probabilmente un `git checkout`/reset
non intenzionale sul file), cancellando questa intera sezione senza lasciare
traccia — nessun marcatore di conflitto, nessun errore. Recuperata qui
testuale dal diff locale ancora presente in questa sessione, non da git
(mai committata). Vedi [[git-forward-only]] e memoria second-brain
`xot-index-md-uncommitted-loss-2026-09-11.md`: la lezione e' che un file mai
committato non ha alcuna rete di sicurezza — committare subito, non a fine
sessione, e' l'unica difesa reale contro un reset altrui.

(Nota igiene 2026-09-15: questa sezione "Discussioni architetturali — 2026-09-11"
era duplicata due volte di seguito, byte-identica salvo questo paragrafo finale
presente solo nella prima copia. Rimossa la seconda copia, nessun contenuto perso.)

## Indice storico consolidato (ex 00-index.md / 00-INDEX.md / 00-index-v2.md / 00-master-index.md / 00-MASTER-INDEX.md, 2026-09-15)

I cinque file sopra elencati erano varianti duplicate/parziali di un indice di
modulo più vecchio (snapshot datati fra dicembre 2025 e aprile 2026), accumulate
in `docs/` root come `00-*.md`. `00-master-index.md` e `00-MASTER-INDEX.md` erano
byte-identici fra loro; `00-index.md` conteneva marker di conflitto Git non risolti
(`<<<<<<< HEAD` / `>>>>>>> laraxot/dev`) su una riga poi effettivamente presente nel
lato `laraxot/dev` (Widget Method Visibility Rules) — risolto qui tenendo il lato
`laraxot/dev`, per coerenza con la regola già verificata in
[[feedback-laraxot-master-pollution-signature]]. I cinque file sono stati eliminati
dopo aver riportato qui sotto ogni link/sezione univoco che contenevano e che non è
già coperto sopra in questo `index.md`. Nessun contenuto perso.

### 🌐 Master Index globale (da `00-master-index.md`, 2026-04-14 — il più recente dei 5)

**Core Architecture (The Religion)**
- [XotBase Philosophy](./XOTBASE_ARCHITECTURE_PHILOSOPHY.md) — perché si estende sempre XotBase. ✅ CANONICAL
- [Laraxot Commandments](./laraxot-10-commandments-wiki.md) — le 10 regole fondamentali del progetto. ✅ CANONICAL
- [Development Philosophy](./philosophy/index.md) — forward-only, DRY, KISS, Zen. ✅ CANONICAL

**Filament Framework Patterns (The Zen)**
- [Filament Widget Index](./filament/widgets/index.md) — hub centrale regole Wizard/Table widget.
- [Infolists for Summary](./filament/widgets/infolists-for-summary.md) — Infolists per i summary dei Wizard.
- [AutoLabel Guidelines](../../UI/docs/autolabel-guidelines.md) — regola "no explicit label".
- [XotBaseWizardWidget](./filament/widgets/xot-base-wizard-widget-philosophy.md) — logica cross-cutting form multi-step.

**Module-Specific Documentation**
- [Fixcity Module](../../Fixcity/docs/INDEX.md) — tickets, wizard, integrazione frontoffice.
- [Geo Module](../../Geo/docs/INDEX.md) — geolocalizzazione e componenti mappa.
- [Predict Module](../../Predict/docs/00-INDEX.md) — logica outcome e market.

**Documentation Hygiene (regole da preservare)**
1. Verificare che un documento non esista già prima di crearne uno nuovo.
2. Mai creare file con suffissi tipo `-1`, `-copy`, `-conflict`, salvo risoluzione merge temporanea.
3. Canonical over Local: una regola valida per tutti i moduli sta in `Modules/Xot/docs/`; gli altri moduli linkano, non duplicano.
4. Logic vs Dress: doc di modulo (`Modules/*/docs/`) = regole di business/logica/endpoint; doc di tema (`Themes/*/docs/`) = grafica/componenti/stili.
5. File in `Modules/Xot/docs/` non presenti in questo indice e con suffissi numerici o "conflict" nel nome sono probabilmente ridondanti: da consolidare o rimuovere (esattamente il lavoro fatto in questa sessione).

**Search Faster (indici di dettaglio per argomento)**
- [Filament Detailed Index](./filament/index.md)
- [Architecture Detailed Index](./architecture/index.md)
- [PHPStan Fixes Index](./phpstan/index.md)

### 🏛️ Architettura Core, Naming, Utility, Testing, Manutenzione (da `00-index.md` + `00-INDEX.md`, unione deduplicata)

- 📐 [Architecture Complete Guide](./architecture-complete.md) — deep dive sistema modulare.
- 🧬 [Base Classes (XotBase)](./xot-base-classes.md) — regole per estendere Resource, Page, Widget.
- ⚙️ [Action Architecture](./action-service-provider-architecture.md) — pattern Actions atomiche e testabili.
- 🧩 [Service Providers](./service-provider-architecture.md) — ciclo di vita e boot dei moduli.
- 🔢 [EnumTrait Pattern](./enum-trait-pattern.md) — standard Enum con traduzioni e UI Filament.
- 📜 [PHPStan Code Quality Guide](./phpstan-code-quality-guide.md) — la bibbia del Livello 10.
- 🚫 [No Services Rule](./critical-no-services-rule.md) — perché Actions invece di Service.
- 🗂️ [Filament Class Extension Rules](./filament-class-extension-rules.md) — regole obbligatorie Filament.
- 📋 [Widget Method Visibility Rules](./filament/widget-method-visibility-rules.md) — visibilità metodi `getTable*()` e naming Filament 5 (lato `laraxot/dev` del conflitto irrisolto in `00-index.md`, non presente affatto in `00-INDEX.md`).
- 🧬 [Trait Patterns](./traits-complete-guide.md) — HasTeams, HasXotTable e altri trait core.
- 🔧 [HasXotTable Fixes](./phpstan-hasxottable-trait-fixes-february.md) — correzioni type safety trait multi-contesto (da `00-index-v2.md`).
- 🐚 [Bashscripts Organization](./bashscripts-organization.md) — strumenti CLI manutenzione.
- 🚀 [Safe Casting Actions](./safe-casting-actions.md) — gestione type-safe dei dati.
- ✅ [PHPStan Level 10 Status](./phpstan-level10-xot-fixes.md) — conformità e report.
- 🔬 [Pest Testing Philosophy](./testing-philosophy-unified.md) — approccio al testing del core.
- 🗑️ [Cleanup Plan](./cleanup-action-plan.md) — strategia per consolidare i documenti accumulati (780+ secondo `00-index-v2.md`).
- 🪮 [Ponytail audit over-engineering](./ponytail-audit-over-engineering.md) — GetFactoryAction, contracts, vincoli MetatagData/XotData (ripetuta 3× identica in `00-INDEX.md`, deduplicata qui).
- 🔁 [Migrazione Services -> QueueableAction](./wiki/decisions/services-to-actions-migration.md) — UrlService/ThemeService/HtmlService migrati ad Actions; ConfigService/XotService/ArrayService/ProfileTest archiviati in `.bak` (codice morto); ArtisanService/RouteService/ModuleService/Translators/Trend lasciati intatti per sessione dedicata. Aggiornamento successivo: include anche la chiusura di HtmlService e la scomposizione di RouteService in Action contestuali con ingresso unico `execute()` (due descrizioni diverse in `00-INDEX.md`, fuse qui).
- Tutti i moduli del sistema dipendono da **Xot**.

**Lettura essenziale (da `00-index-v2.md`)**
1. [README.md](./readme.md) — panoramica del framework Laraxot.
2. [roadmap.md](./roadmap.md) — evoluzione 2026: Laravel 12 & Stability.
3. [super-mucca-methodology.md](./super-mucca-methodology.md) — filosofia di sviluppo del progetto.

### 📄 Xot Module Documentation Index — snapshot 2025-12-18 (solo in `00-index.md`, mai fuso altrove)

**Core Architecture**
- [Architecture Complete Guide (2025)](./architecture-complete-2025.md)
- [Filament Extension Rules Implementation Report](./filament-extension-rules-implementation-report.md)
- [Array Keys Filament Methods](./array-keys-filament-methods.md) — regole obbligatorie chiavi array.
- [Implementation Summary: Filament & PHPStan Fixes](./implementation_summary_filament_phpstan_fixes.md)
- [Filament Extension Violations Report](./filament_extension_violations.md)
- [Project Philosophy, Religion, Politics, Zen](./project-philosophy-religion-politics-zen.md)
- [Autonomous Priority Rule](./autonomous-priority-rule.md)

**Configuration & Services**
- [MCP Configuration Optimized](./mcp-configuration-optimized.md)
- [Model Casting Rules](./model-casting-rules.md)
- [ServiceProvider Best Practices](./serviceprovider-best-practices.md)

**Development Guidelines**
- [Super Cow Methodology](./super-cow-methodology.md)
- [PHP Quality Guide](./php-quality-guide.md)
- [GitHub Workflows Standard](./github-workflows-standard.md)

**Quality Analysis**
- [Module Quality Analysis Summary](./module-quality-analysis-summary.md) — cross-module quality metrics.
- [PHPStan Analysis 2025-01-27](./phpstan-analysis-2025-01-27.md)
- [PHPStan Analysis 2025-12-17](./phpstan-analysis-2025-12-17.md)
- [PHPStan Analysis 2025-12-18](./phpstan-analysis-2025-12-18.md)
- [PHPStan Specific Patterns](./phpstan-specific-patterns.md)

**Quality & Improvement**
- [Quality Improvements Summary 2025-11-18](./quality-improvements-summary-2025-11-18.md)
- [Laraxot Meetup Service Provider Refactor](./laraxot-meetup-service-provider-refactor.md)
- [PHPStan Fix Meetup Service Provider](./phpstan-fix-meetup-service-provider.md)

**Archives & References**
- [Archive Directory](./archive/)
- [Consolidated Directory](./consolidated/)
- [Roadmap Directory](./roadmap/)
- [Helpers Directory](./helpers/)

**Filament v4 Migration**
- [Filament V4 Upgrade Notes](./filament-v4-upgrade-notes.md)
- [Widget Initialization Guide](./widgets-initialization.md)
- [Panel Provider Patterns](./panel-provider-patterns.md) — pattern e best practices Panel Provider.

**Architectural Rules**
- [Architectural Rules Directory](./architectural_rules/)
- [Laravel Modules Namespace Critical Rule](./laravel-modules-namespace-critical-rule.md) — ⚠️ namespace senza "app".

### 📚 Documentation Sections — snapshot 2026-03-02 (unione `00-index.md` + `00-INDEX.md`)

- [Roadmap Xot](roadmap/00-index.md) — visione, fasi, qualità.

**Core Architecture**
- [XotBase Classes & Inheritance Patterns](./xotbase-extension.md)

**Composer / dipendenze** (titolo di `00-INDEX.md`; contenuto unione con `00-index.md`)
- [composer-root-skeleton-modular](./wiki/concepts/composer-root-skeleton-modular.md) — root skeleton + merge solo moduli (solo in `00-INDEX.md`).
- [theme-psr4-autoload-without-merge](./wiki/concepts/theme-psr4-autoload-without-merge.md) — autoload temi senza merge root (solo in `00-INDEX.md`).
- [Module Dependency Management](./composer-module-dependency-management.md)
- [Composer Packages Reference](../../../../bashscripts/ai/wiki/memories/composer-packages-reference.md) — mappatura pacchetti per modulo.
- [Composer Packages Deep Study (2026-03-02)](./composer-packages-deep-study.md)
- [Composer Packages Full Catalog (2026-03-02)](./composer-packages-full-catalog.md) — studio completo package-by-package da `composer show`.
- [Database Connection Configuration](./database-configuration-critical-rules.md)

**Development Standards**
- [PHPStan Level 10 Compliance Guide](./phpstan-level10.md)
- [Code Quality Workflow](./code-quality-tools-guide.md)
- [TDD Laravel Pest Complete Guide](./tdd-laravel-pestd-complete-guide.md)
- [Testing Best Practices](./testing-best-practices.md)

**Memory & Performance**
- [Filament Memory Optimization](./memory-optimization.md)
- [Optimize Filament Memory Command](./memory-optimization-dashboard-fixes.md)
- [Performance Analysis Guide](./performance-guidelines.md)

**Filament**
- [HasXotForm form() DEVE essere final](./hasxotform-form-final.md) — regola: `form()` final, usare `getFormSchema()`.

**PHPStan**
- [phpstan.neon immutabile](./phpstan-neon-immutable.md) — `laravel/phpstan.neon` è l'unico config, non modificare, non crearne altri.

**Error Prevention & Fixes**
- [Common PHPStan Errors & Solutions](./analisi-phpstan.md)
- [Git Conflict Resolution Workflow](./git-conflicts-resolution-strategy.md)
- [Chaos Monkey Operability Rules](./chaos-monkey-operability-rules.md)

**Utilities & Helpers**
- [Translation Management](./translation-system-standardization.md)

**Quick Start**
1. Understand XotBase Pattern: tutti i moduli estendono classi XotBase.
2. Follow TDD: ciclo Red-Green-Refactor con Pest.
3. Maintain Quality: PHPStan Level 10 dopo ogni modifica.
4. Document Everything: aggiornare i docs prima e dopo l'implementazione.

**Recently Updated (snapshot storico, 2026-02-23)**
- Guida TDD completa con integrazione Pest.
- Pattern di test OAuth aggiornati.
- Standard di test per QueueableAction.

**Related Modules**
- [User Module](../../User/docs/00-index.md) — autenticazione e autorizzazione.
- [Activity Module](../../Activity/docs/00-index.md) — event logging e tracking.
- [Tenant Module](../../Tenant/docs/00-index.md) — isolamento multi-tenant.

**Dependency Intelligence**
- [Dependency intelligence](dependency-intelligence.md) (ripetuta 3× identica in entrambi i file, deduplicata qui).

*Metadati snapshot storico: Module Version 1.0, Laravel 12.x, PHP 8.2+, Last Updated 2026-03-02 (superato dai metadati reali in cima a questo file).*
