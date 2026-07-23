# Vite + Lit Web Components Integration

## Problem Statement

When building the Sixteen theme with Vite, the build process failed with:

```
[vite]: Rollup failed to resolve import "lit" from 
  "/var/www/_bases/base_fixcity_fila5/laravel/Modules/Geo/resources/js/components/my-map-lit.js"
```

### Root Cause

1. **MyMap component** (`Geo/resources/js/components/my-map-lit.js`) imports Lit:
   ```javascript
   import { LitElement, html, css } from 'lit';
   import 'leaflet/dist/leaflet.css';
   ```

2. **Vite's entry point** (`Sixteen/resources/js/app.js`) includes it:
   ```javascript
   import '../../../../Modules/Geo/resources/js/components/my-map-lit.js';
   ```

3. **Rollup (Vite's bundler)** tries to resolve "lit" and "leaflet/dist/leaflet.css" but:
   - The component sits in an external module (Geo) outside Sixteen's context
   - Rollup couldn't resolve bare imports like `"lit"` and CSS files like `"leaflet/dist/leaflet.css"`
   - Build failed because module resolution is strict in Vite

---

## Solution Implemented

### Step 1: Install Rollup Node Resolution Plugin

```bash
cd laravel/Themes/Sixteen
npm install --save-dev @rollup/plugin-node-resolve
```

This plugin teaches Rollup how to resolve bare imports like `"lit"` and package-relative paths like `"leaflet/dist/leaflet.css"`.

### Step 2: Update vite.config.js

Added three key configurations:

#### 2a. Import the Plugin

```javascript
import nodeResolve from '@rollup/plugin-node-resolve';
import path from 'path';
```

#### 2b. Add Rollup Plugin to build.rollupOptions

```javascript
export default defineConfig({
    // ... existing config ...
    build: {
        // ... existing build options ...
        rollupOptions: {
            plugins: [
                nodeResolve({
                    browser: true,
                    preferBuiltins: false,
                    extensions: ['.mjs', '.js', '.json', '.node', '.css'],
                }),
            ],
        },
    },
});
```

**What this does:**
- `browser: true` — Use browser module conditions
- `preferBuiltins: false` — Don't prefer Node.js builtins (we're in browser)
- `extensions: [...]` — Resolve .js, .json, .css files

#### 2c. Add optimizeDeps for Pre-bundling

```javascript
export default defineConfig({
    optimizeDeps: {
        include: ['leaflet', 'lit'],
    },
    // ... rest of config
});
```

**Why this helps:**
- Pre-bundles Lit and Leaflet during dev server startup
- Vite caches the bundled versions
- Faster rebuilds and better error messages

#### 2d. Add Resolve Alias (Optional but Recommended)

```javascript
import path from 'path';

export default defineConfig({
    resolve: {
        alias: {
            '@modules': path.resolve(__dirname, '../../Modules'),
        },
    },
    // ... rest of config
});
```

Allows importing from Geo module as:
```javascript
import { MyMap } from '@modules/Geo/resources/js/components/my-map-lit.js';
```

---

## Build Warnings (Normal)

The build now succeeds but may show CSS warnings from Bootstrap Italia:

```
Found 2 warnings while optimizing generated CSS:

Issue #1: Invalid dangling combinator in selector
Issue #2: Invalid empty selector
```

**These are safe to ignore:** They come from Bootstrap Italia's internal CSS during PostCSS processing, not from our code. They don't affect functionality.

---

## Build Output

After running `npm run build && npm run copy`:

```
✓ 11 modules transformed.
rendering chunks...

public/manifest.json                     0.91 kB
public/assets/app-BRKXMWAJ.css           4.97 kB
public/assets/app-test-C16CeGmt.css    842.97 kB
public/assets/app-DWgLaj-K.css        1,122.84 kB
public/assets/app-DYvQ-VW5.js           10.27 kB
public/assets/splide.esm-BWa4TFV4.js    32.60 kB

✓ built in 10.21s
```

Assets are deployed to `public_html/themes/Sixteen/`.

---

## How to Use Web Components in Themes

### 1. Import Component in app.js

```javascript
// Sixteen/resources/js/app.js
import '../../../../Modules/Geo/resources/js/components/my-map-lit.js';
```

### 2. Use in Blade Templates

```blade
<!-- Display a map -->
<my-map 
    lat="45.7"
    lng="12.25"
    zoom="15"
    marker-title="Report Location">
</my-map>
```

### 3. Manipulate via JavaScript

```javascript
const mapEl = document.querySelector('my-map');
mapEl.lat = 45.8;
mapEl.lng = 12.3;
mapEl.zoom = 16;
// Component automatically re-renders
```

### 4. Listen to Custom Events

```javascript
document.querySelector('my-map').addEventListener('marker-click', (e) => {
    console.log('Marker clicked at:', e.detail);
});
```

---

## Lit Web Component Patterns

### Basic Structure

```javascript
import { LitElement, html, css } from 'lit';

export class MyComponent extends LitElement {
    // Declare reactive properties
    static properties = {
        title: { type: String },
        count: { type: Number },
    };

    // Component-scoped styles (Shadow DOM)
    static styles = css`
        :host { display: block; }
        h1 { color: blue; }
    `;

    // Declare default values
    constructor() {
        super();
        this.title = 'Default';
        this.count = 0;
    }

    // Render template
    render() {
        return html`
            <h1>${this.title}</h1>
            <p>Count: ${this.count}</p>
            <button @click=${this.increment}>+</button>
        `;
    }

    // Event handler
    increment() {
        this.count++;
    }

    // Lifecycle hook: after DOM is ready
    firstUpdated() {
        console.log('Component mounted');
    }

    // Cleanup when removed from DOM
    disconnectedCallback() {
        console.log('Component unmounted');
        super.disconnectedCallback();
    }
}

// Register as custom element
customElements.define('my-component', MyComponent);
```

### Template Syntax Cheatsheet

```javascript
html`
    <!-- Text interpolation -->
    <p>${message}</p>

    <!-- Event listener -->
    <button @click=${this.handleClick}>Click</button>

    <!-- Property binding (dot notation) -->
    <child-component .data=${this.data}></child-component>

    <!-- Attribute binding -->
    <div id="${this.id}"></div>

    <!-- Boolean attribute -->
    <input ?disabled=${!this.enabled} />

    <!-- Conditional rendering -->
    ${this.showDetails ? html`<details>...</details>` : ''}

    <!-- List rendering -->
    ${this.items.map(item => html`<li>${item.name}</li>`)}

    <!-- Class binding -->
    <div class="${this.isActive ? 'active' : 'inactive'}"></div>
`;
```

---

## Performance Considerations

### Bundle Size Impact

| Library | Size (min+gzip) | Status |
|---------|-----------------|--------|
| Lit | ~5KB | Already included |
| Leaflet | ~40KB | Already included |
| My custom components | Varies | Only loaded when needed |

### Optimization Tips

1. **Lazy load components:**
   ```javascript
   // Only import MyMap when needed
   const mapEl = document.querySelector('my-map');
   if (mapEl) {
       import('../../../../Modules/Geo/resources/js/components/my-map-lit.js');
   }
   ```

2. **Code splitting:**
   Vite automatically creates separate chunks for each imported module

3. **Tree-shaking:**
   Unused components are automatically removed during minification

---

## Troubleshooting

### Build Error: "Cannot resolve 'leaflet'"

**Cause:** New package added to Web Component  
**Fix:** Add to `optimizeDeps.include` in vite.config.js

```javascript
optimizeDeps: {
    include: ['leaflet', 'lit', 'new-package'],
}
```

### Component Not Registering

**Check in browser console:**
```javascript
customElements.get('my-map') // Should return MyMap class
```

If undefined, the import failed. Check:
1. File path is correct
2. `customElements.define()` is being called
3. No import errors in DevTools console

### Styles Not Applied

**Causes:**
1. Styles using wrong selector (Shadow DOM scopes them)
2. Using global class names instead of host-scoped styles
3. CSS file not imported (check paths)

**Fix example:**
```javascript
static styles = css`
    :host { /* Apply to component root */ }
    .button { /* Scoped to this component */ }
`;
```

---

## References

- **Lit Documentation:** https://lit.dev/
- **Web Components Spec:** https://developer.mozilla.org/en-US/docs/Web/Web_Components
- **Vite Config:** https://vitejs.dev/config/
- **Rollup Plugins:** https://github.com/rollup/plugins

---

## Related Documentation

- [Lit Concepts in Geo Module](../../Modules/Geo/docs/wiki/concepts/lit-web-components.md)
- [Vite Configuration](./vite.config.js)
- [App Entry Point](./resources/js/app.js)

---

**Last Updated:** 2026-04-15  
**Build Status:** ✅ Passing  
**Tested with:** Vite 7.3.2, Lit 3.3.2, Leaflet 1.9.4
