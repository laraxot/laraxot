# Platform Module Philosophy

Core utilities, feature gating, settings persistence, and shared infrastructure for all 47 modules.

## First Principle

> **Ask permissions once, gate everywhere. Persist a setting once, use it everywhere. One true source for every capability.**

Platform is the shared layer. Modules use Platform to ask "can I?" and "what is?", not to answer themselves.

## The Rules

### 1. AccountFeatures for Resource Gating

Features are toggled per account/panel/tenant:
```php
if ($user->accountFeatures()->can('view-reports')) {
    // show reports panel
}
```

**Never** hardcode permissions in module code. Always query AccountFeatures.

**Why:** Disabling a feature = one change. No code touching 47 modules.

### 2. SettingsRepository for Runtime Config

Settings are persisted and reused:
```php
$setting = app(SettingsRepository::class)->get('invoicing.tax_rate');
```

**Never** store config in `config/` files if it changes at runtime.

**Why:** Runtime config = no redeploy. Settings can be managed via UI.

### 3. Policies + Gates for Authorization

Authorization is centralized:
```php
gate()->authorize('create-work-order', $workOrder)
policy(WorkOrder::class)->create($user, $workOrder)
```

**Never** check permissions in route closures or controllers directly.

**Why:** Authorization logic in one place. Reusable across resources.

### 4. Event Bus for Cross-Module Communication

Modules emit events, Platform routes them:
```php
Event::dispatch(new WorkOrderCreated($workOrder))
```

**Never** call another module directly. Always use events.

**Why:** Loose coupling. Modules don't know about each other. Event handlers are configurable.

### 5. Job Queue for Async Tasks

Long-running operations queue via Platform:
```php
dispatch(new SendInvoiceEmail($invoice))->onQueue('invoicing')
```

**Never** execute long operations synchronously in request.

**Why:** Requests stay fast. Retries are automatic. Failures are logged.

### 6. Service Locator is Anti-Pattern

Never use `app()` to resolve dependencies directly. Inject via constructor:
```php
// WRONG
class MyAction {
    public function handle() {
        app(SettingsRepository::class)->get('...')
    }
}

// RIGHT
class MyAction {
    public function __construct(private SettingsRepository $settings) { }
    
    public function handle() {
        $this->settings->get('...')
    }
}
```

**Why:** Explicit dependencies = testable. Implicit = tight coupling and hard to mock.

### 7. Dashboard as Aggregator, Not Data Owner

Platform provides observability, not data:
- Activity feed (from Activity module)
- Pending approvals (from modules)
- System status (from health checks)
- Configuration UI (for SettingsRepository)

**Never** store operational data in Platform. Aggregate and display.

**Why:** Platform stays thin. Data lives in its responsible module.

## The Zen of Platform

> **Share what's common, never duplicate. Ask, don't assume. Route, don't redirect.**

- Features are asked, not hardcoded
- Settings are read, not computed
- Permissions are unified, not scattered
- Events flow, modules listen
- Jobs queue, workers execute
- Dependencies are injected, not resolved

## Breaking the Rules

If you think a rule is wrong:
1. **Document the exception** in your module's README
2. **Add integration tests** if bypassing Platform
3. **Notify maintainers** if a pattern should move to Platform
4. **Never hardcode features or permissions** — they're business rules, not code
5. **Extract reusable patterns** to Platform if repeated in 2+ modules

## See Also

- `ARCHITECTURE.md` — feature gating and settings design
- `TESTING.md` — authorization and policy testing
- `docs/event-bus.md` — cross-module communication
- `docs/job-queue.md` — async task patterns
- `docs/settings-repository.md` — runtime config management
- `docs/authorization-patterns.md` — gates and policies
