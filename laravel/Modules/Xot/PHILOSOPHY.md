# Xot Philosophy

The credo of Laraxot and the non-negotiables that bind all 47 modules.

## First Principle

> **One change to `XotBaseResource` updates all 47 modules at once.**

This is possible because every module obeys the same constraints. Violating them breaks the invariant.

## The Rules

### 1. Never Filament Directly

Filament classes **must** be extended through Xot:
- Resource → `XotBaseResource`
- Page → `XotBasePage`
- Widget → `XotBaseWidget` (and variants: `XotBaseChartWidget`, `XotBaseStatsOverviewWidget`, `XotBaseTableWidget`)
- Action → `XotBaseAction`
- List/Create/Edit/View → `XotBaseListRecords`, `XotBaseCreateRecord`, `XotBaseEditRecord`, `XotBaseViewRecord`
- PanelProvider → `XotBasePanelProvider`
- Dashboard → `XotBaseDashboard`
- Login/Register → `XotBaseLogin`, `XotBaseRegister`

**Why:** Filament upgrades require changes in one place, not 47. Direct Filament usage locks in version-specific code.

### 2. Actions, Not Services

Business logic lives in:
```php
namespace Modules\{Modulo}\Actions;

class MyAction
{
    use QueueableAction;

    public function execute(...): mixed
    {
        // business logic here
    }
}
```

**Never** use:
- `app/Services/` folder
- Classes named `*Service`
- Action-like classes without `execute()` method
- Actions that proxy Eloquent relationships (use the relationship method directly)

**Why:** Standard location, standard method name, queueable by default. Predictability across modules.

### 3. `phpstan.neon` is Sacred

The file `phpstan.neon` **is not modified** by any module. No `ignoreErrors`, no module-specific overrides. Errors are **fixed in the code**, never silenced.

**Why:** Static analysis strictness catches bugs before runtime. Ignoring errors defeats the point.

### 4. Folio and Volt for Frontend, Filament for Admin

- **Public pages**: `resources/views/pages/` (Folio routes), Volt components, no controllers, no routes in `routes/web.php`.
- **Admin pages**: Filament resources, pages, widgets in `Modules/{Mod}/app/Filament/`.

**Why:** Clean separation. Folio is file-based and simple for public sites. Filament is declarative and reusable for admin.

### 5. Translations from Language Files

Never hardcode labels in code:
```php
// WRONG
$input->label('User Email');

// RIGHT
->label(trans('user::user.email'))  // or
->label(trans_string('user.email'))
```

Translation keys are computed by `GetTransKeyAction`: class path → `{module}::{resource}.{field}`.

**Why:** Translations are centralized, maintainable, and reusable. A label change updates everywhere.

### 6. Base Classes for Everything

Models from `XotBaseModel`, migrations from `XotBaseMigration`, providers from `XotBaseServiceProvider`.

**Why:** Consistency. Shared features (created_by, updated_by, auto-discovery) are inherited, not reimplemented.

### 7. The Folder `docs` is Memory

The `docs/` folder is the source of truth for:
- Architecture decisions
- Conventions
- Examples
- Integration points

**Update `docs/` first.** Code follows documentation, not the reverse.

**Why:** Future maintainers (and future you) should read before writing code. Undocumented code is technical debt.

## The Zen of Xot

> **DRY and KISS taken to logical conclusion.**

- One place to update = one framework change
- Standard naming = instantly readable
- Declared constraints = safer refactoring
- Consistency = no surprises

A developer opening a module for the first time finds the same structure, the same patterns, the same base classes. That familiarity is Xot's gift.

## Breaking the Rules

If you think a rule is wrong:
1. **Document the exception** in the module's README and notify other maintainers.
2. **Update `docs/` with the new pattern** if it becomes common.
3. **Never silently violate the rules.** An undocumented exception is a bug.

## See Also

- `ARCHITECTURE.md` — design and integrations
- `TESTING.md` — test strategies
- `docs/actions-over-services.md` — Actions pattern deep-dive
- `docs/xotbaseresource.md` — resource implementation
- `docs/xotbasepanelprovider-discovery.md` — panel discovery rules
