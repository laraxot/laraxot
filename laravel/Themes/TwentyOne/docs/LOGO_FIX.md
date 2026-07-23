# Logo Duplication Fix - TwentyOne Theme

## Problem
Logo appeared duplicated in header and footer with both light and dark variants:
```html
<img class="fi-logo fi-logo-light size-8" ...>
<img class="fi-logo fi-logo-dark size-8" ...>
```

## Root Cause
Using `<x-filament-panels::logo>` component from Filament 3/4 which automatically generates both light and dark logo variants.

## Solution (KISS)
Replace Filament component with direct `<img>` tag:

**Before**:
```blade
<a href="/">
    <div class="size-8">
        <x-filament-panels::logo class="size-8"/>
    </div>
</a>
```

**After**:
```blade
<a href="/">
    <div class="size-8">
        <img 
            src="{{ asset('assets/predict/img/logo-ft.svg') }}" 
            alt="Logo di {{ config('app.name') }}" 
            class="size-8 h-8 w-8"
        />
    </div>
</a>
```

## Rationale
- **KISS**: Single logo instead of dual light/dark variants
- **Performance**: One HTTP request instead of two
- **Simplicity**: Direct control over logo rendering
- **Filament 4 Ready**: No dependency on Filament's logo component

## Files Modified
- `laravel/Themes/TwentyOne/resources/views/components/blocks/logo/simple.blade.php`

## Verification
```bash
curl -s http://predict.local/it | grep "logo" | wc -l
# Should show fewer occurrences (no duplicates)
```

## Related
- GitHub Issue: #12
- Principle: KISS (Keep It Simple, Stupid)
