---
name: hasxottable-reorderable
description: "HasXotTable Reorderable Design - DRY/KISS pattern for Filament table reordering"
metadata:
  type: design
  status: proposed
  created: 2026-09-15
  related_stories: [5.92, 5.93]
  github_issue: https://github.com/provtv/module_xot_fila5/issues/26
  github_discussion: https://github.com/provtv/module_xot_fila5/discussions
---

# HasXotTable Reorderable Design

## Overview
Documentation for implementing reorderable tables via `HasXotTable` trait using Filament's `->reorderable()` with configurable `order_column`.

## Architecture Context

**Critical**: `XotBaseResource` extends Filament `Resource` and delegates `table()` to `XotBaseResourceTable::configure()`, which uses `HasXotTable` trait. Concrete resources like `RatingResource` extend `XotBaseResource` and **must NOT** `use HasXotTable` directly.

**Pattern**:
```
RatingResource extends XotBaseResource
  → XotBaseResource::table() delegates to XotBaseResourceTable::configure()
    → XotBaseResourceTable uses HasXotTable trait
      → HasXotTable::table() applies reorderable if order column exists
```

## Problem
Tables in Filament resources often need drag-and-drop reordering. Currently, each resource must manually call `->reorderable('column')`, causing duplication and inconsistency.

## Solution (KISS + DRY + stateless)
Enhance `HasXotTable` trait with a SINGLE extension point: `getOrderColumn(): ?string`.

### Why only getOrderColumn()?
- **KISS**: one method, no state, no lifecycle to manage
- **DRY**: no duplicated setter/property code
- **Stateless**: the trait stores nothing; the column name is derived from the model schema at call time
- **Extensible**: if a subclass needs a different column, it simply overrides `getOrderColumn()`

### Why NOT setOrderColumn()?
- Unnecessary state (`protected ?string $orderColumn = null`)
- Property lifecycle to manage (init, reset, sync)
- Violates the trait's stateless design
- No real advantage over overriding `getOrderColumn()`

## Implementation Details

### HasXotTable Trait Extension
```php
// In laravel/Modules/Xot/app/Filament/Traits/HasXotTable.php

trait HasXotTable {
    /**
     * Get the column used for ordering/reordering.
     * Returns 'order_column' if it exists in the model's table, null otherwise.
     * Override in subclass to use a different column name.
     */
    protected function getOrderColumn(): ?string
    {
        $model = app(static::getModel());

        return \Schema::hasColumn($model->getTable(), 'order_column')
            ? 'order_column'
            : null;
    }

    /**
     * Apply reorderable to table if order column is available.
     * Called automatically from table() method.
     */
    protected function applyReorderable(Table $table): Table
    {
        if ($column = $this->getOrderColumn()) {
            return $table->reorderable($column);
        }
        return $table;
    }

    // Existing table() method: add ->applyReorderable($table) call
}
```

### Custom Column Override
```php
// In laravel/Modules/MyModule/app/Filament/Resources/MyResource/Tables/MyTable.php
class MyTable extends XotBaseResourceTable
{
    protected function getOrderColumn(): ?string
    {
        return 'custom_sort_field'; // Override only this method
    }
}
```

**NOT** `public function orderColumn(): string` — breaks getter convention and has no return type hint consistent with the trait.

**NOT** override in Resource class — Resources delegate to Table classes. Override in the concrete Table class that extends XotBaseResourceTable.

## DRY Principles Applied
- Single source of truth for reorderable logic in HasXotTable trait
- Eliminates duplicate `->reorderable('column')` calls across 7+ modules
- No state = no sync issues, no stale property
- Centralized configuration via one method
- Preserves Filament's native reorderable functionality

## KISS & Clean Code
- Simple API: `getOrderColumn()` only (no setter, no property)
- Clear separation of concerns: trait handles all reorderable logic
- Minimal impact on existing code: resources need zero changes for default case
- Backward compatible: no breaking changes to existing tables
- Stateless trait: easy to test, easy to reason about

## Second Brain Preservation
- This design lives in: `laravel/Modules/Xot/docs/design/hasxottable-reorderable.md`
- AD: `laravel/Modules/IndennitaResponsabilita/docs/architecture-decisions/hasxottable-reorderable-ad.md`
- Wiki rules: `docs/wiki/rules/hasxottable-reorderable.md`
- Graphify updates will be triggered via `graphify update .`

## GitHub
- Issue: https://github.com/provtv/module_xot_fila5/issues/26 (Design pattern review)

## Related BMAD Artifacts
- Story: `laravel/Modules/Xot/docs/stories/5.92-filament-table-reordering-pattern.story.md`
- Story: `laravel/Modules/Xot/docs/stories/5.93-filament-table-reordering-implementation.story.md`
- Architecture: `laravel/Modules/IndennitaResponsabilita/docs/architecture-decisions/hasxottable-reorderable-ad.md`

## Implementation Notes
- Requires Filament 5.x (reorderable feature available)
- Column must exist in database table
- `getOrderColumn()` uses `\Schema::hasColumn($model->getTable(), 'order_column')` (facade standard, non `schema_has_column()`)
- For custom resources, override `getOrderColumn()` appropriately
- Performance: negligible overhead (single schema query per table build, cached per model per session)
