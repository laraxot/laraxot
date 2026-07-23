# CRITICAL: Theme Namespace Rules

## 🚨 ABSOLUTE RULE - NEVER VIOLATE

**`pub_theme::` is the CORRECT namespace for ALL theme views and translations**
**`sixteen::` is WRONG for views and translations**

## ✅ Correct Usage

```blade
// Views
@extends('pub_theme::layouts.base')
<x-pub_theme::ui.logo />
<x-pub_theme::blocks.navigation.header-slim />

// Translations
{{ __('pub_theme::cookies.title') }}
{{ __('pub_theme::auth.login') }}
```

## ❌ WRONG Usage (NEVER DO THIS)

```blade
// WRONG - DO NOT USE
@extends('sixteen::layouts.base')
<x-sixteen::ui.logo />
{{ __('sixteen::cookies.title') }}
```

## 📋 Namespace Usage by Type

| Resource Type | Correct Namespace | Usage |
|---------------|------------------|--------|
| **Views** | `pub_theme::` | All Blade templates, components, layouts |
| **Translations** | `pub_theme::` | All language strings |
| **Config** | `sixteen` | Configuration files only |

## 🔧 Technical Explanation

From `ThemeServiceProvider.php`:

```php
// IMPORTANTE: pub_theme è il namespace standard per i temi
$this->loadViewsFrom(__DIR__ . '/../../resources/views', 'pub_theme');
$this->loadTranslationsFrom(__DIR__ . '/../../lang', 'pub_theme');

// Configuration uses the theme name
$this->loadConfigFrom(__DIR__ . '/../../config', 'sixteen');
```

## 🎯 Why This Rule Exists

1. **Standardization**: All themes in the ecosystem use `pub_theme::` for consistency
2. **Interoperability**: Theme switching works because all themes use the same namespace
3. **Laravel Architecture**: Follows Laravel's view namespace conventions
4. **Documentation**: Official AGID/Bootstrap Italia docs expect this pattern

## 🛡️ Prevention Guidelines

1. **Always search the codebase** for existing namespace patterns before making changes
2. **Check the ServiceProvider** to understand how namespaces are registered
3. **Follow existing patterns** in the theme files
4. **Never assume** - always verify namespace usage in existing code

---

**This rule is non-negotiable and must be followed in all theme development.**