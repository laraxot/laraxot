# 🎨 SVG Icon Convention - Automatic Registration

**Version**: 1.0  
**Created**: 2026-03-30  
**Status**: ✅ Active Convention  
**Owner**: Multi-Agent Team

---

## 🚨 Golden Rule

> **GLI SVG SONO REGISTRATI AUTOMATICAMENTE - NON CREARE FILE MANUALMENTE**

Il sistema gestisce automaticamente:
- ✅ Registrazione SVG
- ✅ Sprite generation
- ✅ Icon mapping
- ✅ Namespace management

**NON FARE**:
- ❌ Creare file SVG manualmente in `resources/svg/brands/`
- ❌ Modificare sprite esistenti
- ❌ Aggiungere use href="#it-*" manualmente
- ❌ Duplicare icone già esistenti

---

## 📁 SVG Directory Structure

```
laravel/Modules/UI/resources/svg/
├── brands/                    # Brand icons (auto-registered)
│   ├── facebook.svg          # ✅ Auto-loaded
│   ├── twitter.svg           # ✅ Auto-loaded
│   ├── youtube.svg           # ✅ Auto-loaded
│   ├── telegram.svg          # ✅ Auto-loaded
│   ├── whatsapp.svg          # ✅ Auto-loaded
│   └── rss.svg               # ✅ Auto-loaded
├── avatars/                   # Avatar icons
├── bootstrap-italia/          # BI official icons
└── *.svg                      # General icons
```

---

## 🔧 Automatic Registration Process

### How It Works

1. **File Placement**: SVG files in `resources/svg/brands/`
2. **Auto-Discovery**: Theme build process scans directory
3. **Sprite Generation**: Combined into sprite sheet
4. **Namespace Mapping**: `#it-{filename}` format
5. **Cache**: Stored in `public/assets/svg/sprites.svg`

### Build Process

```bash
# During npm run build
npm run build
# → Scans resources/svg/
# → Generates public/assets/svg/sprites.svg
# → Maps all icons automatically
```

---

## ✅ Correct Usage

### In Blade Templates

```blade
{{-- CORRECT: Use Filament icon component --}}
<a href="#" aria-label="Facebook">
    <x-filament::icon 
        icon="heroicon-o-facebook" 
        class="w-6 h-6"
        aria-hidden="true" 
    />
</a>

{{-- CORRECT: With dynamic icon --}}
<x-filament::icon 
    :icon="$stat['icon']" 
    class="icon-lg icon-primary w-10 h-10" 
    aria-hidden="true" 
/>

{{-- CORRECT: Bootstrap Italia icon with SVG sprite --}}
<svg class="icon icon-sm">
    <use href="#it-facebook"></use>
</svg>
```

### Available Icons

**Social Media** (auto-registered from `brands/`):
- `#it-facebook`
- `#it-twitter` / `#it-x-twitter`
- `#it-youtube`
- `#it-telegram`
- `#it-whatsapp`
- `#it-rss`
- `#it-instagram`
- `#it-linkedin`
- `#it-github`

**Bootstrap Italia** (from `bootstrap-italia/`):
- `#it-search`
- `#it-user`
- `#it-close`
- `#it-chevron-left`
- `#it-chevron-right`
- `#it-more-items`
- `#it-info`
- `#it-help`

---

## ❌ Wrong Usage (DON'T DO THIS)

```blade
{{-- WRONG: Using <x-icon> (not Filament standard) --}}
<x-icon name="facebook" class="w-6 h-6" />

{{-- WRONG: Creating SVG inline --}}
<a href="#">
    <svg viewBox="0 0 24 24">
        <path d="..." /> {{-- DON'T inline paths --}}
    </svg>
</a>

{{-- WRONG: Manual file creation --}}
touch resources/svg/brands/custom.svg {{-- DON'T create manually --}}

{{-- WRONG: Hardcoded paths --}}
<img src="/assets/svg/facebook.svg" /> {{-- Use sprite or Filament icon --}}
```

---

## 🎯 Icon Naming Convention

### Format

```
#it-{filename}
```

**Examples**:
- `resources/svg/brands/facebook.svg` → `#it-facebook`
- `resources/svg/bootstrap-italia/search.svg` → `#it-search`
- `resources/svg/avatars/user.svg` → `#it-user`

### Case Sensitivity

- ✅ **Lowercase**: `facebook.svg` → `#it-facebook`
- ❌ **Uppercase**: `Facebook.svg` → May not work
- ❌ **Kebab-case**: `my-icon.svg` → Use `myicon.svg` or `my_icon.svg`

---

## 📋 Adding New Icons

### Step 1: Check If Exists

```bash
# Search existing icons
find laravel/Modules/UI/resources/svg/ -name "*.svg" | xargs grep -l "facebook"

# Or check sprite
cat public/assets/svg/sprites.svg | grep "facebook"
```

### Step 2: Add SVG File (If Needed)

```bash
# Place in correct directory
cp icon.svg laravel/Modules/UI/resources/svg/brands/

# DO NOT modify existing icons
```

### Step 3: Rebuild

```bash
cd laravel/Themes/Sixteen
npm run build

# Icon is now available as #it-{filename}
```

### Step 4: Use in Template

```blade
<svg class="w-6 h-6">
    <use href="#it-newicon"></use>
</svg>
```

---

## 🔍 Troubleshooting

### Issue 1: Icon Not Showing

**Symptom**: `<use href="#it-facebook">` shows nothing

**Solutions**:
```bash
# 1. Check if file exists
ls -la laravel/Modules/UI/resources/svg/brands/facebook.svg

# 2. Rebuild assets
npm run build

# 3. Clear cache
php artisan view:clear
php artisan cache:clear

# 4. Check sprite was generated
cat public/assets/svg/sprites.svg | grep facebook
```

### Issue 2: Wrong Icon Name

**Symptom**: Using `#it-facebook` but should be `#it-fb`

**Solution**:
```bash
# Check actual filename
ls laravel/Modules/UI/resources/svg/brands/

# Use exact filename (without .svg) as href
# facebook.svg → #it-facebook
```

### Issue 3: Icon Color Wrong

**Symptom**: Icon appears but wrong color

**Solution**:
```blade
{{-- Use currentColor for fill --}}
<svg class="w-6 h-6 fill-current text-primary">
    <use href="#it-facebook"></use>
</svg>

{{-- Or use icon classes --}}
<svg class="icon icon-primary">
    <use href="#it-facebook"></use>
</svg>
```

---

## 📊 Icon Sources

### Bootstrap Italia (Official)

**Location**: `bootstrap-italia/packages/icons/src/`

**Usage**:
```blade
<svg class="icon">
    <use href="#it-{icon-name}"></use>
</svg>
```

**Count**: 300+ icons

### Custom Brand Icons

**Location**: `laravel/Modules/UI/resources/svg/brands/`

**Usage**:
```blade
<svg class="w-6 h-6">
    <use href="#it-{brand-name}"></use>
</svg>
```

**Count**: Auto-registered from directory

### Heroicons (via Tailwind)

**Usage**:
```blade
<x-heroicon-o-facebook class="w-6 h-6" />
```

**Count**: 1000+ icons

---

## 🤖 Multi-Agent Coordination

### OpenViking Context

```bash
openviking add-memory "SVG icons auto-registered from resources/svg/. Use #it-{filename} format. Don't create files manually."
```

### BMAD Convention

```
_bmad/conventions/svg-icons.md
```

### GSD Phase

```
.planning/phases/svg-icon-convention/
```

---

## 📚 Related Documentation

- [Bootstrap Italia Integration](./BOOTSTRAP_ITALIA_CSS_IMPLEMENTATION.md)
- [Header Analysis](./design-comuni/screenshots/HEADER_ANALYSIS_FIX_PLAN.md)
- [Footer Analysis](./design-comuni/screenshots/FOOTER_ANALYSIS_FIX_PLAN.md)
- [Build Scripts](./BUILD_SCRIPTS.md)

---

## ✅ Checklist for Icon Usage

- [ ] Check if icon already exists in system
- [ ] Use `#it-{filename}` format
- [ ] Don't create SVG files manually (already auto-registered)
- [ ] Use `fill-current` for color control
- [ ] Add `aria-label` for accessibility
- [ ] Test after `npm run build`
- [ ] Verify in sprite sheet

---

**Last Updated**: 2026-03-30  
**Convention Status**: ✅ **ACTIVE**  
**Key Rule**: SVG AUTO-REGISTERED - DON'T CREATE MANUALLY  
**Owner**: Multi-Agent Team
