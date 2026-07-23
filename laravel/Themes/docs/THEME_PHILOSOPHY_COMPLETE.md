# 🎭 THEME PHILOSOPHY - COMPLETE GUIDE

**Data**: 2026-03-30  
**Status**: ✅ COMPLETE  
**Priority**: CRITICAL

---

## 🎯 FUNDAMENTAL PRINCIPLE

> *"Il tema è un VESTITO. Si cambia. Si configura. NON è hardcoded."*

---

## ❌ WRONG: Hardcoded Registration

```php
// laravel/app/Providers/AppServiceProvider.php

// ❌ WRONG! Theme is hardcoded!
$this->app->register(\Themes\Sixteen\Providers\ThemeServiceProvider::class);
```

**WHY IT'S WRONG**:
- ❌ Theme is **fixed** (can't change)
- ❌ Violates **open/closed** principle
- ❌ Breaks **multi-tenant** architecture
- ❌ Requires **code change** to switch theme
- ❌ Not **configurable**

---

## ✅ CORRECT: Configuration-Driven

### AppServiceProvider (CLEAN)

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * DO NOT register themes here!
 * Themes are loaded dynamically from config.
 */
class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // ✅ No hardcoded theme registration
    }

    public function boot(): void
    {
        // ✅ Themes loaded dynamically
    }
}
```

### Configuration File

**File**: `config/local/fixcity/xra.php`

```php
return [
    'pub_theme' => 'Sixteen',  // ← Theme name
    'register_pub_theme' => true,  // ← Enable/disable
];
```

### Multiple Configurations

```
config/
├── localhost/fixcity/xra.php → pub_theme = 'Sixteen'
├── eu/fixcity/xra.php → pub_theme = 'TwentyOne'
├── local/fixcityam/xra.php → pub_theme = 'Sixteen'
└── net/futurely/xra.php → pub_theme = 'Sixteen'
```

**Same codebase, DIFFERENT themes per domain!**

---

## 🔄 DYNAMIC LOADING

### How It Works

1. **Read Config**
   ```php
   $themeName = config('xra.pub_theme');  // 'Sixteen'
   $shouldRegister = config('xra.register_pub_theme');  // true
   ```

2. **Resolve Theme Class**
   ```php
   $themeClass = "Themes\\{$themeName}\\Providers\\ThemeServiceProvider";
   // Themes\Sixteen\Providers\ThemeServiceProvider
   ```

3. **Register if Enabled**
   ```php
   if ($shouldRegister) {
       app()->register($themeClass);
   }
   ```

4. **Load Resources**
   ```php
   // ThemeServiceProvider::boot()
   $this->loadViewsFrom(
       'Themes/Sixteen/resources/views', 
       'pub_theme'  // ← Constant namespace
   );
   ```

---

## 🎨 VIEW NAMESPACE

### Configuration

```php
// config/xra.php
'pub_theme' => 'Sixteen',
```

### Loading

```php
// ThemeServiceProvider
$this->loadViewsFrom(
    'Themes/Sixteen/resources/views', 
    'pub_theme'  // ← ALWAYS 'pub_theme'
);
```

### Usage

```blade
{{-- ✅ CORRECT: Use constant namespace --}}
<x-pub_theme::blocks.hero.homepage />
<x-pub_theme::components.card.featured />

{{-- ❌ WRONG: Theme-specific namespace --}}
<x-sixteen::blocks.hero.homepage />
<x-twentyone::components.card />
```

**WHY**: Theme can change, namespace stays `pub_theme`!

---

## 🧩 BASE CLASS

### XotBaseThemeServiceProvider

**File**: `Modules/Xot/app/Providers/XotBaseThemeServiceProvider.php`

```php
abstract class XotBaseThemeServiceProvider extends ServiceProvider
{
    public string $name = '';  // e.g., 'Sixteen'
    public string $nameLower = '';  // e.g., 'sixteen'
    
    public function boot(): void
    {
        // Load views dynamically
        $this->loadViewsFrom(
            $this->module_dir.'/../resources/views', 
            $this->nameLower
        );
        
        // Load translations
        $this->loadTranslationsFrom(
            $this->module_dir.'/../resources/lang', 
            $this->nameLower
        );
        
        // Register Blade components
        $this->registerBladeComponents();
    }
}
```

### Theme Implementation

**File**: `Themes/Sixteen/app/Providers/ThemeServiceProvider.php`

```php
class ThemeServiceProvider extends XotBaseThemeServiceProvider
{
    public string $name = 'Sixteen';
    public string $nameLower = 'sixteen';
    
    public function boot(): void
    {
        parent::boot();
        // Theme-specific logic
    }
}
```

---

## 📁 MULTI-TENANT EXAMPLES

### Scenario 1: Single Domain

```
Domain: fixcity.local
Config: config/localhost/fixcity/xra.php
Theme: Sixteen
```

### Scenario 2: Multi-Domain

```
Domain: fixcity.eu
Config: config/eu/fixcity/xra.php
Theme: TwentyOne

Domain: fixcityam.local
Config: config/local/fixcityam/xra.php
Theme: Sixteen
```

### Scenario 3: Future Projects

```
Domain: futurely.net
Config: config/net/futurely/xra.php
Theme: Sixteen
```

**All with SAME codebase!**

---

## 🧘 DEVELOPER MANTRAS

> *"Il tema è un vestito. Si cambia. Si configura."*

> *"MAI hardcoded. SEMPRE configurabile."*

> *"Namespace 'pub_theme' è costante. Il tema è variabile."*

> *"Multi-tenant = Multi-theme. Same code, different skins."*

> *"Configuration-driven, NOT code-driven."*

> *"AppServiceProvider pulito. No temi hardcoded."*

---

## 📖 REFERENCES

### Internal Files
- `laravel/app/Providers/AppServiceProvider.php` - Clean provider
- `Modules/Xot/app/Providers/XotBaseThemeServiceProvider.php` - Base class
- `Themes/Sixteen/app/Providers/ThemeServiceProvider.php` - Implementation
- `config/local/fixcity/xra.php` - Configuration example

### Documentation
- `.planning/THEME_PHILOSOPHY_VESTITO.md` - Full philosophy
- `.openviking/theme-philosophy.md` - Quick reference

---

## ✅ CHECKLIST

### When Creating New Theme

- [ ] Extend `XotBaseThemeServiceProvider`
- [ ] Set `$name` and `$nameLower`
- [ ] Create `config/{domain}/{project}/xra.php`
- [ ] Set `pub_theme` to new theme name
- [ ] Set `register_pub_theme` to `true`
- [ ] Test theme switching

### When Switching Theme

- [ ] Update config file only
- [ ] NO code changes needed
- [ ] Clear cache: `php artisan config:clear`
- [ ] Test new theme

### Code Review

- [ ] No hardcoded theme registration
- [ ] AppServiceProvider is clean
- [ ] Uses `pub_theme::` namespace
- [ ] Config-driven theme selection

---

**Status**: ✅ PHILOSOPHY COMPLETE  
**Next**: Apply to all documentation, educate team
