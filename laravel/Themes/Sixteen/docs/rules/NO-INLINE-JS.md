# NO INLINE JAVASCRIPT IN BLADE — Critical Rule

**Severity**: 🔴 CRITICAL  
**Scope**: All Blade templates (`resources/views/**/*.blade.php`)  
**Enforcement**: Zero tolerance — block all PRs with inline `<script>`

---

## ❌ FORBIDDEN

```blade
{{-- NEVER DO THIS --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Any JS logic here
    });
</script>

<script>
    window.myConfig = {{ json_encode($config) }};
</script>

<script>
    (function() {
        'use strict';
        // Alpine data factory inline
        Alpine.data('myComponent', () => ({
            // ...
        }));
    })();
</script>
```

---

## ✅ CORRECT PATTERNS

### Pattern 1: External JS File (Preferred)

```blade
{{-- Blade Template --}}
<script src="{{ asset('themes/Sixteen/assets/my-component.js') }}"></script>
@vite(['resources/js/app.js'], 'themes/Sixteen')
```

```javascript
// resources/js/my-component.js → built to public/
(function() {
    'use strict';
    // Component logic here
})();
```

### Pattern 2: Vite ES Module (for deferred loading)

```blade
{{-- Blade Template --}}
@vite(['resources/js/app.js'], 'themes/Sixteen')
```

```javascript
// resources/js/app.js
import myComponent from './components/my-component.js';

Alpine.data('myComponent', myComponent);
```

### Pattern 3: Vanilla JS (No Alpine Timing Issues)

When you need JS that works without Alpine.js synchronization complexity:

```blade
@filamentScripts
@vite(['resources/js/app.js'], 'themes/Sixteen')
```

```javascript
// resources/js/theme/my-component.js — Vanilla JS
export function initMyComponent() {
    document.querySelectorAll('[data-my-component]').forEach(el => {
        if (el.dataset.bound) return; // Idempotent
        el.addEventListener('click', handleClick);
        el.dataset.bound = 'true';
    });
}

// Auto-init on import
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMyComponent);
} else {
    initMyComponent();
}
```

```javascript
// app.js — Simple import
import './theme/my-component.js';
```

**Why no dual-file needed?**
- Alpine.js requires `alpine:init` registration BEFORE Alpine starts (timing critical)
- Vanilla JS uses `DOMContentLoaded` or immediate execution — works fine with defer
- No sync/async timing conflicts
- Single entry point (`app.js`) — clean and simple

---

## Why Inline JS Is Bad

| Issue | Impact | Mitigation |
|-------|--------|------------|
| **CSP Violations** | Requires `unsafe-inline` directive | External files respect CSP |
| **Caching** | JS re-downloaded with every HTML page | External files cached separately |
| **Security** | XSS injection risk via Blade variables | External files isolate logic |
| **Maintenance** | Logic scattered in templates | Centralized in JS files |
| **Testing** | Can't unit test inline JS | External files testable |
| **Bundle Analysis** | Invisible to build tools | Tracked in Vite/webpack |

---

## Exceptions

**ONLY these inline scripts are allowed:**

1. **Dark mode boot** (anti-FOUC) — 3 lines max:
```blade
<script>
    if (localStorage.getItem('dark_mode') === 'true') {
        document.documentElement.classList.add('dark');
    }
</script>
```

2. **CSRF token** for external APIs:
```blade
<meta name="csrf-token" content="{{ csrf_token() }}">
```

3. **JSON data** via `data-*` attributes:
```blade
<div data-config="{{ json_encode($config) }}"></div>
```

**NO EXCEPTIONS** for Alpine.js factories, event listeners, or complex logic.

---

## Detection

```bash
# Find all inline scripts in Blade templates
grep -r "<script>" laravel/Themes/Sixteen/resources/views/ --include="*.blade.php" | grep -v "<script src"

# Should return empty
```

---

## Enforcement

1. **Pre-commit hook**: Block commits with inline `<script>` (except dark mode)
2. **PR review**: Automated check for inline JS
3. **IDE highlighting**: Mark inline scripts as errors

---

## References

- [Alpine.js Components](../ALPINE-JS-COMPONENTS.md) — Header Mobile Nav pattern
- [CSP Mozilla](https://developer.mozilla.org/en-US/docs/Web/HTTP/CSP)
- [Vite Build](https://vitejs.dev/guide/build.html)

---

**Created**: 2026-05-26  
**Author**: Claude (Cascade)  
**Status**: ✅ Active Rule
