# BMAD: Proposed architecture mismatch in XotBaseManageRelatedRecords.php

## Summary

The documentation file `XotBaseManageRelatedRecords.php.md` describes a proposed architecture for `XotBaseManageRelatedRecords` that **does not match the actual implementation** in `XotBaseManageRelatedRecords.php`. The `.md` file proposes a template method pattern with `getTableColumns()` and `getTableHeaderActions()` overrides, but these methods do not exist in the actual PHP code.

## Problem

### 1. Missing Methods in Implementation

The actual `XotBaseManageRelatedRecords` class **does not contain** the methods described in the documentation:

- `getTableColumns()` — **NOT IMPLEMENTED**
- `getTableHeaderActions()` — **NOT IMPLEMENTED**

These methods are only mentioned in the `.md` file as proposed overrides, but they are absent from the real code.

### 2. Incorrect Property Declaration

The `.md` file references a protected property `$_table` that is **NOT declared** in the actual PHP class:

```php
// In .md: protected ?Table $_table = null;
// In actual .php: No such property exists
```

This causes a fatal error if someone tries to instantiate the class.

### 3. Wrong `table()` Method Logic

The `.md` describes a `table()` method that:
- Assigns `$this->_table = $resourceClass::table($table)`
- Then calls `->columns($this->getTableColumns())` and `->headerActions($this->getTableHeaderActions())`

But the **actual** `table()` method in `HasXotTable` (which is inherited via `use HasXotTable`) works differently:
- It sets `$this->_table = $resourceClass::table($table)`
- But then **returns** the table directly, without applying column/header manipulations
- The column/header logic happens elsewhere in the `table()` chain (via `parentTable()`)

### 4. Misleading `parentTable()` Usage

The `.md` suggests using `...parent::getTableColumns()` and `...parent::getTableHeaderActions()` to extend defaults. However, the actual implementation uses `HasXotTable` trait with `table as parentTable` alias, which is a different mechanism entirely.

## Impact

- Any developer reading the `.md` will believe `getTableColumns()` and `getTableHeaderActions()` are available and will attempt to override them
- The actual code will fail with "Undefined method getTableColumns()" or similar errors
- The documentation is misleading and should be either updated to match the actual implementation OR removed as a proposal

## Resolution Options

1. **Update the `.md` file** to accurately reflect the actual implementation (which uses `HasXotTable` trait with `table as parentTable`)
2. **Remove the `.md` file** if it's meant to be a future proposal (since it was never implemented)
3. **Add the missing methods** (`getTableColumns()`, `getTableHeaderActions()`) to the actual class if the proposed architecture is desired

## Related Stories

- [xotbasemanagerelatedrecords-convention-over-configuration.story.md](../../stories/xotbasemanagerelatedrecords-convention-over-configuration.story.md) - Main BMAD story for this module
- [managecontacts-gettablecolumns-headeractions.story.md](../../stories/manage-contacts-gettablecolumns-headeractions.story.md) - Specific to ManageContacts

## Evidence

- **Actual file**: `/var/www/_bases/base_quaeris_fila5/laravel/Modules/Xot/docs/app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php`
- **Proposed file**: `/var/www/_bases/base_quaeris_fila5/laravel/Modules/Xot/docs/app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php.md`

## Conclusion

The `.md` file describes a proposed architecture that was never implemented. The actual implementation uses a different pattern (inheriting from `HasXotTable` trait with `table as parentTable`). The documentation must be aligned with the real code or removed.
