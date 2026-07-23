# Filament Extension Rules (Architecture)

## 🚨 CRITICAL MANDATORY RULE

**NEVER extend Filament classes directly. ALWAYS extend XotBase OR LangBase abstract classes.**

⚠️ **CRITICAL**: Check if module is multilingual FIRST before choosing base class!

This is a critical architectural rule that must be respected throughout the entire project.

## Why This Rule Exists

1. **Update Compatibility**: When Filament is updated, only XotBase classes need adaptation
2. **Centralized Functionality**: XotBase classes provide common project-specific functionality
3. **Consistency**: All Filament components follow the same pattern
4. **Maintainability**: Centralized changes in base classes
5. **Extensibility**: Easy addition of new features
6. **Multilingual Support**: LangBase classes handle internationalization automatically

## Complete Extension Mapping

### For NON-multilingual modules:

| Filament Class | XotBase Class |
|---|---|
| `Filament\Pages\Page` | `Modules\Xot\Filament\Pages\XotBasePage` |
| `Filament\Pages\Dashboard` | `Modules\Xot\Filament\Pages\XotBaseDashboard` |
| `Filament\Resources\Resource` | `Modules\Xot\Filament\Resources\XotBaseResource` ⚠️ **REQUIRES `getFormSchema()`** |
| `Filament\Resources\Pages\ListRecords` | `Modules\Xot\Filament\Resources\Pages\XotBaseListRecords` |
| `Filament\Resources\Pages\CreateRecord` | `Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord` |
| `Filament\Resources\Pages\EditRecord` | `Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord` |
| `Filament\Resources\Pages\ViewRecord` | `Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord` ⚠️ **REQUIRES `getInfolistSchema()`** |
| `Filament\Widgets\Widget` | `Modules\Xot\Filament\Widgets\XotBaseWidget` ⚠️ **REQUIRES `getFormSchema()`** |
| `Filament\Widgets\StatsOverviewWidget` | `Modules\Xot\Filament\Widgets\XotBaseStatsOverviewWidget` |
| `Filament\Widgets\ChartWidget` | `Modules\Xot\Filament\Widgets\XotBaseChartWidget` |
| `Filament\Widgets\TableWidget` | `Modules\Xot\Filament\Widgets\XotBaseTableWidget` |
| `Filament\Resources\RelationManagers\RelationManager` | `Modules\Xot\Filament\Resources\RelationManagers\XotBaseRelationManager` |

### For MULTILINGUAL modules (Cms, Blog, News, Pages):

| Filament Class | LangBase Class |
|---|---|
| `Filament\Resources\Pages\ListRecords` | `Modules\Lang\Filament\Resources\Pages\LangBaseListRecords` |
| `Filament\Resources\Pages\CreateRecord` | `Modules\Lang\Filament\Resources\Pages\LangBaseCreateRecord` |
| `Filament\Resources\Pages\EditRecord` | `Modules\Lang\Filament\Resources\Pages\LangBaseEditRecord` |
| `Filament\Resources\Pages\ViewRecord` | `Modules\Lang\Filament\Resources\Pages\LangBaseViewRecord` |

⚠️ **CRITICAL**: Classes marked with ⚠️ have REQUIRED abstract methods that MUST be implemented!

## Implementation Examples

### ❌ WRONG - Direct Filament Extension
```php
// ❌ NEVER DO THIS
use Filament\Resources\Pages\ListRecords;
class MyPage extends ListRecords
{
    // This violates the fundamental rule
}
```

### ✅ CORRECT - XotBase Extension (Non-multilingual)
```php
// ✅ FOR NON-MULTILINGUAL MODULES
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
class MyPage extends XotBaseListRecords
{
    // This respects the system architecture
    
    // Required if using HasXotTable trait
    public function getTableColumns(): array
    {
        return [
            'name' => TextColumn::make('name'),
        ];
    }
}
```

### ✅ CORRECT - LangBase Extension (Multilingual)
```php
// ✅ FOR MULTILINGUAL MODULES
use Modules\Lang\Filament\Resources\Pages\LangBaseListRecords;
class MyPage extends LangBaseListRecords
{
    // This handles multilingual functionality automatically
}
```

## 🔍 Pre-Implementation Checklist

**Before implementing any Filament class:**

- [ ] **🚨 MULTILINGUAL CHECK**: Determine if module is multilingual
- [ ] **🚨 XOTBASE TYPE CHECK**: What type of Filament class? (Page, Widget, Resource, etc.)
- [ ] **🚨 ABSTRACT METHOD CHECK**: Does this XotBase type have REQUIRED abstract methods?
- [ ] **Choose Correct Base**: Use LangBase* for multilingual, XotBase* for standard
- [ ] **Check XotBase Availability**: Verify XotBase equivalent exists
- [ ] **Read Abstract Requirements**: Check what methods need implementation
- [ ] **Verify Method Signatures**: Ensure static/non-static matches exactly
- [ ] **Test Immediately**: Load the page to verify it works

## Multilingual Module Detection

**A module IS multilingual if it has:**
- Trait `Translatable` in existing classes
- `Actions\LocaleSwitcher::make()` in actions
- Other classes using `LangBase*`
- Fields like `lang`, `locale` in models
- Typical multilingual modules: Cms, Blog, News, Pages

## Critical Method Requirements

### XotBaseResource Classes
- ✅ **REQUIRES**: `getFormSchema()`
- ❌ **NEVER**: `getTableColumns()`

### XotBaseListRecords Classes
- ✅ **MAY REQUIRE**: `getTableColumns()` (from HasXotTable trait)
- ❌ **NEVER**: `getFormSchema()`

### XotBaseViewRecord Classes
- ✅ **REQUIRES**: `getInfolistSchema()`
- ❌ **NEVER**: `getTableColumns()` or `getFormSchema()`

### XotBaseWidget Classes
- ✅ **REQUIRES**: `getFormSchema()`
- ❌ **NEVER**: `getTableColumns()`

## Related Documentation

- [Error Prevention Guide](../error-prevention/filament-xotbase-patterns.md)
- [XotBase Resource Rules](../error-prevention/xotbase-resource-rules.md)
- [Method Signature Errors](../error-prevention/method-signature-errors.md)
- [Claude Code Rules](../claude-code-rules.md) 