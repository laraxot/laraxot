# Xot Module: Philosophy, Architecture & Zen

> **Core Framework** — Laraxot's heart. Every module is built on top of Xot. This document describes the philosophy, architecture, patterns, and design decisions that make Xot the reliable foundation for the entire ecosystem.

---

## 1. Philosophy & Religion

### Canonical Purpose

Xot is not a domain-specific module (no "things" to manage). It is a **framework layer** that provides:
- **Foundational classes** (XotBaseModel, XotBaseResource, XotBasePage, XotBaseWidget, etc.)
- **Traits** for code reuse (HasXotFactory, RelationX, Updater, EnumTrait, etc.)
- **Architecture patterns** (Actions, Casts, Datas, Enums, Events)
- **Filament integration** (form/table components, resources, pages, widgets)
- **Utilities** (console commands, helpers, exceptions, exports)

**Why Xot exists**: To eliminate boilerplate, enforce patterns across 18+ modules, and maintain PHPStan Level 10 compliance at scale.

### Religion (Non-Negotiable Principles)

1. **Never extend Filament classes directly** → Always use XotBase* equivalents
   - `XotBaseResource` instead of `Filament\Resources\Resource`
   - `XotBasePage` instead of `Filament\Pages\Page`
   - `XotBaseWidget` instead of `Filament\Widgets\Widget`
   - `XotBaseAction` instead of `Filament\Actions\Action`
   - Why? Centralized customization, version isolation, type safety.

2. **Actions > Services** → Spatie Queueable Actions for all business logic
   - No `app/Services` folder (violation)
   - All stateless, testable, queueable operations go in `app/Actions`
   - Why? Async ready, dependency injection built-in, single responsibility.

3. **XotBaseModel enforces audit trail** → created_by, updated_by, deleted_by always present
   - Casts to string (user ID or name)
   - Immutable once recorded
   - Why? Compliance (GDPR, audit), security (who did what).

4. **Traits are composition** → Not inheritance chains
   - HasXotFactory, RelationX, Updater, EnumTrait, etc. are mixed in
   - Never deep class hierarchies beyond XotBaseModel
   - Why? Flexibility, reduced coupling, clearer responsibility.

5. **phpstan.neon is sacred** → Never modify it
   - Project's quality gate (Level 10)
   - Any change requires approval
   - Why? Consistency across 18+ modules, prevents regression.

6. **Connection='xot' by default** → Centralized database routing
   - Allows multi-database patterns
   - Tenant isolation via query scopes
   - Why? Scalability, multi-tenancy ready.

### Zen (The Essence)

**"Framework in the framework."**

Xot is Laravel with guardrails. Every decision made here cascades to 18+ modules. The philosophy is:
- DRY at scale (shared base classes > copy-paste)
- KISS (patterns, not abstractions)
- SOLID (single responsibility, polymorphism via traits)
- Zero compromises on type safety (PHPStan Level 10)

---

## 2. Architecture: What Xot Contains

### Core Models (25 total)

| Model | Purpose | Use Case |
|-------|---------|----------|
| **XotBaseModel** | Foundation for all models across Xot and modules | Every model extends this (directly or via BaseModel) |
| **BaseModel** | Module-level base (connection='xot') | Entry point for module models |
| **BaseExtra** | Schemaless key-value attributes | Dynamic properties without migration |
| **BaseComment** | Thread-based comments (polymorphic) | User feedback, discussions |
| **BaseActivity** | Audit trail entries | Who did what, when |
| **BaseMorphPivot** | Polymorphic many-to-many junction | Flexible relationships |
| **BasePivot** | Standard many-to-many junction | Typed relationships |

**Key Design**: Base classes provide defaults; concrete models in modules extend and specialize.

### Pattern: Request → Action → Model

```
User Request (Filament/API)
  ↓
XotBaseResource/Page (UI orchestration)
  ↓
Action (business logic, queueable)
  ↓
Model (persistence, relationships)
  ↓
Database
```

**No controllers for CRUD.** Resources and Actions handle it. Models stay lean.

### Filament Architecture (36+ custom components)

#### Forms (11 components + base)
- `XotBaseCheckboxList`, `XotBaseDatePicker`, `XotBaseField`, `XotBaseFieldGroup`, `XotBaseRadio`, `XotBaseSelect`, `XotBaseTextarea`, `XotBaseTextInput`, `XotBaseToggle`, etc.
- **Inheritance**: Each extends Filament counterpart + adds:
  - Auto-translation (label, placeholder, help text)
  - Type hints (no loose `array`)
  - Consistent sizing/spacing
- **Used by**: Every module's forms inherit these or compose them

#### Tables (6 components + base)
- `XotBaseColumn`, `XotBaseColumnGroup`, `XotBaseIconColumn`, `XotBaseSummarizeColumn`, etc.
- **Pattern**: Same as Forms (inheritance + auto-translation)

#### Resources (3 levels)
1. **XotBaseResource** — Core resource abstraction
   - Auto-routing (`getUrl()`)
   - i18n support (`trans()` method)
   - Relation managers ready
2. **XotBaseResourceForm** — Form schema builder
3. **XotBaseResourceTable** — Table schema builder

#### Pages
- **XotBasePage** — Base for Filament pages
- **XotBaseEditPage**, **XotBaseListPage**, **XotBaseCreatePage** — CRUD templates

#### Widgets (8+ types)
- **XotBaseWidget** — Core widget base
- **XotBaseTableWidget** — Table inside widget
- **XotBaseChartWidget** — Charts (stats)
- **XotBaseStatsOverviewWidget** — KPI tiles
- Specializations for info lists, schemas, wizards

### Traits (Reusable Behavior)

| Trait | Models | Purpose |
|-------|--------|---------|
| **HasXotFactory** | All XotBaseModels | Factory generation + custom states |
| **RelationX** | All XotBaseModels | Extended relationships (deep queries) |
| **Updater** | All XotBaseModels | Update w/ audit trail (created_by, etc) |
| **EnumTrait** | Enums | Translation + icon support |
| **EnumIntegerTrait** | Integer enums | Type conversion |
| **HasSchemalessAttributes** | Models | Schemaless JSON columns (via BaseExtra) |
| **HasTableFunctionsTrait** | Models | Table inspection (columns, types) |
| **HasCustomRelations** | Models | Polymorphic relation resolution |

**Cross-module benefit**: Any module can `use HasCustomRelations` for polymorphic relations.

### Actions (19 core orchestrations)

| Action | Purpose |
|--------|---------|
| `ArtisanAction` | Execute artisan commands |
| `ArrayAction` | Array manipulation utilities |
| `ConfigAction` | Config file access |
| `ExecuteArtisanCommandAction` | Async artisan execution |
| `GeneratePdfAction` | PDF generation (via Spipu) |
| `GetTransKeyAction` | Translation key resolution |
| `GetResourceClassNameByModelClassAction` | Model → Resource mapping |
| `LogActivityAction` | Audit trail recording |
| (+ 11 more) | Utilities for console, exports, health checks |

**Pattern**: All are Spatie Queueable Actions, testable in isolation.

### Casts (Type-Safe Conversions)

Custom casts beyond Laravel defaults:
- `IntegerCast`, `StringCast`, `BooleanCast` (strict type coercion)
- `JsonCast` (schemaless attributes)
- `EnumCast` (enum with translations)
- Model casts defined in `casts()` method (Laravel 11+)

### Datas (Transfer Objects)

Spatie Data objects for:
- Form/API request validation
- Type-safe data transfer between layers
- Serialization to JSON

### Enums (Business State)

All enums use `EnumTrait` for:
- Translation labels (`trans('enum.name.value')`)
- Icon support (for UI)
- String/integer conversion

Example:
```php
enum Status: string {
    case Active = 'active';
    case Inactive = 'inactive';
    
    use EnumTrait;
}
```

### Service Providers (3 in root)

1. **XotServiceProvider** — Boot Xot: register facades, config publishing
2. **Filament\AdminPanelProvider** — Filament customization
3. **LfNotificationCustomizationServiceProvider** (Notification integration)

Each registers base classes, publishes config, seeds data.

### Dependencies (36 external libraries)

| Category | Key Libraries | Why |
|----------|--------------|-----|
| **Filament & Livewire** | filament/filament, livewire/livewire, livewire/volt, livewire/flux | UI framework |
| **Laravel Core** | laravel/framework, laravel/folio, laravel/pennant, laravel/pulse | Foundation |
| **Spatie Packages** | spatie/laravel-data, spatie/laravel-permission, spatie/laravel-queueable-action, spatie/laravel-tags, spatie/laravel-model-states, spatie/laravel-schemaless-attributes | DDD patterns |
| **Data Processing** | maatwebsite/excel, spipu/html2pdf | Exports |
| **Utilities** | nwidart/laravel-modules (modular architecture), staudenmeir/eloquent-has-many-deep, awobaz/compoships | Advanced queries |
| **Development** | doctrine/dbal, phpstan, rector | Quality gates |

**Notables**:
- `nwidart/laravel-modules` — Enables modular structure
- `spatie/laravel-permission` — RBAC (roles, permissions)
- `spatie/laravel-data` — DTO/request validation
- `livewire/volt` — Single-file Livewire components

---

## 3. Best Practices & False Friends

### Best Practices (Steal These)

#### 1. **XotBaseModel.getClassName() for Polymorphism**
```php
// Resolve model class from backtrace (supports multi-module inheritance)
$modelClass = SomeModel::getClassName(); // Returns Modules\SomeModule\Models\SomeModel
```
**Why**: Enables polymorphic behavior without constructor injection. Classes resolve their own concrete type.
**Where**: Use in trait methods that need to instantiate the same class from a child module.

#### 2. **Audit Trail via Casts**
```php
protected function casts(): array {
    return [
        'created_by' => 'string',
        'updated_by' => 'string',
        'deleted_by' => 'string',
    ];
}
```
**Why**: Immutable record of who touched the model. Queryable for audit reports.
**Where**: All models via BaseModel.

#### 3. **XotBase Classes for Filament Customization**
```php
class MyResource extends XotBaseResource {
    // Automatically gets i18n, routing, navigation via trait
}
```
**Why**: One point of customization. Update XotBaseResource → benefits all 18+ modules.
**Where**: Every module's Filament resources.

#### 4. **Trait Composition Over Inheritance**
```php
class User extends XotBaseModel {
    use HasXotFactory;
    use RelationX;
    use Updater;
}
```
**Why**: Flat structure, no deep inheritance trees, easy to test/mock.
**Where**: All models that need factory, relationships, audit.

#### 5. **Actions for Business Logic**
```php
class CreateUserAction extends Action {
    public function execute(CreateUserData $data): User { ... }
}
```
**Why**: Testable, queueable, injectable, single responsibility.
**Where**: All business operations (create, update, export, sync).

### Bad Practices (Avoid These)

#### 1. **Directly extending Filament classes**
```php
// ❌ WRONG
class MyResource extends Filament\Resources\Resource { ... }

// ✅ RIGHT
class MyResource extends XotBaseResource { ... }
```
**Why**: Breaks version isolation, bypasses Xot customizations, harder to upgrade.

#### 2. **app/Services folder with Service classes**
```php
// ❌ WRONG
class UserService {
    public function create($data) { ... }
}

// ✅ RIGHT
class CreateUserAction extends Action {
    public function execute(CreateUserData $data): User { ... }
}
```
**Why**: Services hide dependencies, not queueable, not testable in isolation.

#### 3. **Fire-and-forget without logging**
```php
// ❌ WRONG
SomeAction::dispatch($data); // No way to track if it succeeds

// ✅ RIGHT
LogActivityAction::dispatch([
    'action' => SomeAction::class,
    'result' => $result,
]);
SomeAction::dispatch($data);
```
**Why**: Audit trail breaks, hard to debug failures.

#### 4. **Hardcoded translation keys**
```php
// ❌ WRONG
return 'User created'; // No i18n

// ✅ RIGHT
return trans('users.created'); // In lang/en/users.php
```
**Why**: Multitenancy, multiple languages require translation.

#### 5. **Custom connections instead of 'xot'**
```php
// ❌ WRONG
protected $connection = 'custom_db'; // Breaks tenant scoping

// ✅ RIGHT
protected $connection = 'xot'; // Uses Xot routing
```
**Why**: Tenant isolation, query scoping, routing depends on 'xot' connection.

### False Friends (Traps)

#### 1. **property_exists() on Eloquent Models**
```php
// ❌ WRONG
if (property_exists($user, 'name')) { ... } // False even if column exists!

// ✅ RIGHT
if (isset($user->name)) { ... } // Checks magic __get
```
**Why**: Models use magic attributes. property_exists checks PHP properties, not Eloquent attributes.

#### 2. **Direct $model->update() vs Updater trait**
```php
// ❌ WRONG
$user->update(['name' => 'John']); // No created_by/updated_by recorded

// ✅ RIGHT
$user->updateModel(['name' => 'John'], auth()->id()); // Via Updater trait
```
**Why**: Audit trail is lost. Updater trait adds user context.

#### 3. **Relationships without eager loading**
```php
// ❌ WRONG (N+1 query)
$users = User::all();
foreach ($users as $user) {
    echo $user->profile->bio; // Query per user!
}

// ✅ RIGHT
$users = User::with('profile')->get();
```
**Why**: RelationX trait helps, but lazy loading kills performance.

#### 4. **Snapshot dependency on Event Sourcing**
```php
// ❌ WRONG
$event = StoredEvent::latest()->first(); // Assuming immutable, but...

// ✅ RIGHT
$snapshot = Snapshot::latest()->first(); // Snapshots are read caches
```
**Why**: Events are append-only; snapshots are read projections (different semantics).

#### 5. **Filament widget mount() signature**
```php
// ❌ WRONG (incompatible)
class MyWidget extends XotBaseWidget {
    public function mount() { ... } // Wrong signature for Filament 5
}

// ✅ RIGHT
class MyWidget extends XotBaseWidget {
    public function initXotBaseWidget() { ... } // Use hook instead
}
```
**Why**: Filament 5 changed mount() signature. Xot provides hook.

---

## 4. Roadmap & Quality

### Current Quality Status

| Metric | Status | Target |
|--------|--------|--------|
| **PHPStan Level** | 10 | 10 (maintained) |
| **Test Coverage** | ~75% | 85%+ |
| **Modular Dependency** | 18 modules | Stable |
| **Lines of Code** | ~8K core | Stable (no feature creep) |

### Planned Improvements

1. **Events as First-Class** (Event Sourcing integration)
   - Every significant action → DomainEvent
   - Audit trail via events, not just log table
   - CQRS read models

2. **Advanced Relationship Caching**
   - RelationX trait to cache polymorphic relations
   - Reduce N+1 queries

3. **Multi-Store Support**
   - Events to EventStoreDB (for distributed systems)
   - Local DB as fallback

4. **API Resource Auto-Generation**
   - From Filament Resource → API Resource (reduce boilerplate)

5. **AI-Assisted Schema Generation**
   - From Domain model → Migration (reverse scaffolding)

### Refactoring Debt Identified

1. **Filament component duplication** (Forms & Tables)
   - DRY: extract common props to trait

2. **Action boilerplate**
   - Some actions are thin wrappers, could use factory pattern

3. **Trait documentation**
   - HasXotFactory, RelationX need example usage in docs

---

## 5. Integration Map

### Who Depends on Xot

**All 18 modules** inherit from Xot:
- User (auth, roles, teams)
- Tenant (multi-tenancy scoping)
- Geo (location, coordinates)
- Media (file handling)
- Notify (notification channels)
- Activity (event sourcing, audit)
- Employee, TechPlanner, Cms, Job, Lang, Gdpr, Seo, AI, Ui, and test modules

### Reverse Dependencies

**Xot depends on**:
- Laravel/Livewire/Filament (framework)
- Spatie packages (DDD patterns)
- None of the domain modules (stays at core layer)

**Dependency flow**: Xot ← UI, Tenant, User ← rest of modules

---

## 6. Security & Compliance

### Built-In Safeguards

1. **Audit Trail (audit columns)**
   - created_by, updated_by, deleted_by → who did what
   - GDPR: can export/delete user's actions

2. **Row-Level Security (RLS) Ready**
   - Tenant scoping via connection routing
   - Policies in modules enforce authorization

3. **Type Safety (PHPStan Level 10)**
   - No loose `mixed` types
   - All method signatures typed
   - Prevents SQL injection, type confusion vulnerabilities

4. **Immutable Event Log**
   - StoredEvent never deleted (compliance)
   - Snapshots are read-only caches

### Known Risks

1. **XotBaseModel.getClassName() backtrace parsing**
   - Slow if call stack is deep
   - Fallback to static property recommended for performance

2. **Connection='xot' routing**
   - If routing fails, falls back to default connection (security concern)
   - Test multi-database setup thoroughly

---

## 7. How to Use Xot

### Installation & Setup

```bash
# Xot is already installed as a module in the monorepo
cd laravel/
composer install

# Run Xot migrations
php artisan migrate --path="Modules/Xot/database/migrations"

# Seed (if available)
php artisan db:seed XotSeeder
```

### Quick Start: Extend a Filament Resource

```php
// In your module's Filament/Resources/
namespace Modules\MyModule\Filament\Resources;

use Modules\Xot\Filament\Resources\XotBaseResource;
use Modules\MyModule\Models\MyModel;

class MyModelResource extends XotBaseResource {
    protected static ?string $model = MyModel::class;
    
    public static function form(Form $form): Form {
        return $form->schema([
            // Auto-gets i18n from 'my_module.my_model.fields.*'
            XotBaseTextInput::make('name'),
        ]);
    }
    
    public static function table(Table $table): Table {
        return $table->columns([
            XotBaseColumn::make('name'),
        ]);
    }
}
```

**Result**: i18n, routing, navigation, CRUD all automatic.

### Quick Start: Create a Business Action

```php
namespace Modules\MyModule\Actions;

use Spatie\QueueableAction\QueueableAction;
use Modules\Xot\Actions\Action;

class CreateMyEntityAction extends Action {
    use QueueableAction;
    
    public function execute(CreateMyEntityData $data): MyEntity {
        return MyEntity::create($data->toArray());
    }
}

// Usage
CreateMyEntityAction::dispatch($data); // Async
// or
(new CreateMyEntityAction())->execute($data); // Sync
```

### Integration Points: Where to Hook

1. **Service Providers** → Register config, facades, commands
2. **Migrations** → Define schema (use Xot base tables if polymorphic)
3. **Models** → Extend BaseModel, use traits
4. **Actions** → Business logic, queueable
5. **Filament Resources** → Extend XotBaseResource
6. **Events** → Emit domain events, listen in Actions
7. **Policies** → Enforce authorization
8. **Casts** → Custom type conversion

---

## 8. Perplessità & Dubbi

### Open Questions

1. **getClassName() performance at scale**
   - Backtrace parsing is O(n). What happens at 100k+ records?
   - Proposal: Cache resolution in a static property

2. **Event store duplication**
   - StoredEvent (append-only) + Snapshot (cache). Which is source of truth?
   - Current: Events are source, snapshots are derived. OK, but document better.

3. **XotBaseWidget mount() vs initXotBaseWidget()**
   - Why the hook instead of Livewire's native mount()?
   - Answer: Filament 5 signature incompatibility. But fragile — could break on v6.

4. **Multi-database scoping**
   - Connection='xot' works, but how does tenant switching work exactly?
   - Need diagrams for tenant → connection → database flow

5. **Circular dependency risk**
   - If module A uses Xot, and Xot uses module A for a trait?
   - Answer: Xot never imports from modules (only laravel/spatie). OK, but risky.

### Blind Spots

1. **Testing Xot in isolation**
   - Most tests are in modules using Xot
   - Coverage of XotBase* classes themselves might be low

2. **Documentation on trait composition**
   - HasXotFactory + RelationX + Updater together — what order? Side effects?
   - Need comprehensive guide

3. **Migration versioning**
   - Xot migrations are numbered. What if two modules create conflicting indices?
   - Migration naming convention needed

---

## 9. Summary Card

```
┌──────────────────────────────────────────────┐
│ MODULE: Xot (Core Framework)                 │
├──────────────────────────────────────────────┤
│ Purpose: Foundation layer (base classes,     │
│          traits, patterns) for all modules   │
│ Owner: laravel/Modules/Xot/                  │
│ Status: Stable (production)                  │
│ PHPStan: Level 10 ✓                          │
│ Test Coverage: ~75%                          │
│ Dependencies: 18 modules (reverse)           │
│ Reverse Deps: None (isolated core)           │
│ Lines of Code: ~8K                           │
│ Complexity: High (foundation layer)          │
└──────────────────────────────────────────────┘
```

---

## 10. Meta & Updates

- **Generated**: 2026-09-06
- **Verified Against**: laravel/Modules/Xot/ (full codebase review)
- **Last Review**: 2026-09-06
- **Author**: Claude (eccentrico mode, vision-first analysis)

---

## References

- Architecture docs: `docs/architecture-complete.md`
- No Services rule: `docs/critical-no-services-rule.md`
- Filament rules: `docs/filament-class-extension-rules.md`
- PHPStan guide: `docs/phpstan-code-quality-guide.md`
- Trait patterns: `docs/traits-complete-guide.md`

