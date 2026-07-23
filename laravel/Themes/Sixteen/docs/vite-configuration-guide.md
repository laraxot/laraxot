# Vite Configuration - Sixteen Theme

**Type**: Build Configuration Documentation  
**Status**: ✅ Documented  
**Last Update**: 2026-04-01  
**Related**: [[00-index]], [[build-commands-guide]], [[layout-hierarchy]]

---

## 🎯 Overview

The Sixteen theme uses **Vite** as its build tool with a **custom configuration** optimized for Laravel theme architecture.

**Key Decision**: `outDir: './public'` (NOT `../../public_html/themes/Sixteen/`)

---

## 📐 Architecture

### Why `outDir: './public'`?

```
laravel/Themes/Sixteen/
├── resources/              ← Source files
│   ├── css/
│   │   └── app.css
│   └── js/
│       └── app.js
├── public/                 ← Build output (outDir: './public')
│   ├── manifest.json
│   └── assets/
└── package.json
```

### Build Flow

```
1. Vite builds from resources/
   ↓
2. Outputs to public/ (outDir: './public')
   ↓
3. npm run copy copies to ../../public_html/themes/Sixteen/
   ↓
4. Laravel serves from public_html/themes/Sixteen/
```

---

## 🔧 Configuration Details

### vite.config.js

```javascript
import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',      // Main CSS entry
                'resources/js/app.js',        // Main JS entry
            ],
            refresh: [
                ...refreshPaths,
                'app/Livewire/**',            // Hot reload for Livewire
            ],
        }),
        tailwindcss(),                        // Tailwind CSS v4 plugin
    ],
    build: {
        outDir: './public',                   // ← CRITICAL: Output to local public/
        emptyOutDir: true,                    // Clean before build
        manifest: 'manifest.json',            // Generate manifest for @vite()
        chunkFileNames: 'js/[name]-[hash].js',
        entryFileNames: 'js/[name]-[hash].js',
        assetFileNames: (assetInfo) => {
            const ext = assetInfo.name.split('.').pop();
            if (ext === 'css') return 'css/[name]-[hash].[ext]';
            if (['png','jpg','jpeg','gif','svg','webp','ico'].includes(ext)) 
                return 'images/[name]-[hash].[ext]';
            if (['woff','woff2','eot','ttf','otf'].includes(ext)) 
                return 'fonts/[name]-[hash].[ext]';
            return 'assets/[name]-[hash].[ext]';
        },
        minify: 'esbuild',                    // Fast minification
        sourcemap: false,                     // No source maps in production
        target: 'es2015',                     // Browser compatibility
        cssCodeSplit: true,                   // Split CSS by entry
        assetsInlineLimit: 4096               // Inline small assets (<4KB)
    },
    server: {
        hmr: { host: 'localhost' }            // Hot Module Replacement
    },
});
```

---

## 🎯 Why NOT `outDir: '../../public_html/themes/Sixteen/'`?

### ❌ WRONG Approach

```javascript
// DON'T DO THIS
build: {
    outDir: '../../public_html/themes/Sixteen/',
}
```

**Problems**:
1. **Cross-filesystem issues** - public_html might be symlinked
2. **Permission conflicts** - Different ownership between directories
3. **Build race conditions** - Multiple builds can conflict
4. **No clean separation** - Source and output mixed
5. **Hard to test** - Can't test build without deploying

### ✅ CORRECT Approach

```javascript
// DO THIS
build: {
    outDir: './public',
}
```

**Benefits**:
1. **Clean separation** - Source (resources/) → Build (public/)
2. **Predictable** - Always builds to same location
3. **Easy to test** - Build locally, deploy separately
4. **No permissions issues** - All within theme directory
5. **npm run copy** - Explicit deployment step

---

## 📦 Build Process

### Step-by-Step

```bash
# 1. Build to local public/
npm run build
# Output: laravel/Themes/Sixteen/public/manifest.json
#         laravel/Themes/Sixteen/public/assets/*

# 2. Copy to Laravel public_html/
npm run copy
# Command: cp -r ./public/* ../../public_html/themes/Sixteen/
# Output: public_html/themes/Sixteen/manifest.json
#         public_html/themes/Sixteen/assets/*

# 3. Laravel serves assets
# URL: /themes/Sixteen/assets/app-*.css
#      /themes/Sixteen/assets/app-*.js
```

### package.json Scripts

```json
{
    "scripts": {
        "dev": "vite",
        "build": "vite build",
        "copy": "cp -r ./public/* ../../public_html/themes/Sixteen/"
    }
}
```

---

## 🔗 Laravel Integration

### @vite() Helper

```blade
{{-- components/layouts/main.blade.php --}}
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'], 'themes/Sixteen')
</head>
```

### How It Works

1. **Laravel reads manifest**: `public_html/themes/Sixteen/manifest.json`
2. **Maps source to built**:
   ```json
   {
     "resources/css/app.css": {
       "file": "assets/app-C1huuq--.css",
       "src": "resources/css/app.css"
     }
   }
   ```
3. **Generates HTML**:
   ```html
   <link rel="stylesheet" href="/themes/Sixteen/assets/app-C1huuq--.css">
   <script src="/themes/Sixteen/assets/app-DR9d2vyK.js"></script>
   ```

---

## 📁 File Organization

### Source Structure
```
laravel/Themes/Sixteen/
├── resources/
│   ├── css/
│   │   └── app.css           ← Tailwind CSS source
│   └── js/
│       └── app.js            ← Alpine.js + custom JS
├── public/                   ← Build output (outDir: './public')
│   ├── manifest.json         ← Vite manifest
│   └── assets/
│       ├── css/
│       ├── js/
│       ├── images/
│       └── fonts/
└── vite.config.js            ← Configuration
```

### Public Structure (After Build)
```
public_html/themes/Sixteen/
├── manifest.json             ← Copied by npm run copy
├── assets/
│   ├── app-*.css            ← Compiled CSS
│   ├── app-*.js             ← Bundled JS
│   ├── images/              ← Optimized images
│   └── fonts/               ✓ Web fonts
└── dist/                     ← Additional assets
```

---

## ⚠️ Common Issues

### Issue 1: Manifest Not Found
**Error**: `Vite manifest not found at: public_html/themes/Sixteen/manifest.json`

**Cause**: Forgot `npm run copy`

**Solution**:
```bash
npm run build
npm run copy
```

### Issue 2: Assets Not Loading
**Error**: 404 on CSS/JS files

**Cause**: Build output not copied

**Solution**:
```bash
# Rebuild and copy
npm run build
npm run copy

# Verify files exist
ls -la public_html/themes/Sixteen/assets/
```

### Issue 3: Wrong outDir
**Error**: Build fails or outputs to wrong location

**Cause**: `outDir` set to absolute path or wrong relative path

**Solution**:
```javascript
// ✅ CORRECT
outDir: './public'

// ❌ WRONG
outDir: '../../public_html/themes/Sixteen/'
outDir: '/var/www/public_html/themes/Sixteen/'
```

---

## 🧪 Verification Checklist

After build, verify:

- [ ] `laravel/Themes/Sixteen/public/manifest.json` exists
- [ ] `laravel/Themes/Sixteen/public/assets/` has files
- [ ] `public_html/themes/Sixteen/manifest.json` exists (after copy)
- [ ] `public_html/themes/Sixteen/assets/` has files (after copy)
- [ ] Page loads without Vite errors
- [ ] CSS styles applied correctly
- [ ] JavaScript works (Alpine.js)
- [ ] No console errors

---

## 🚀 Development vs Production

### Development
```bash
npm run dev
```
- **outDir**: Not used (dev server)
- **HMR**: Hot Module Replacement enabled
- **Manifest**: Not generated
- **URL**: http://localhost:5173/

### Production
```bash
npm run build && npm run copy
```
- **outDir**: `./public`
- **Manifest**: Generated
- **Minification**: Enabled
- **Cache-busting**: Hash in filenames

---

## 📊 Build Output Example

### manifest.json
```json
{
  "resources/css/app.css": {
    "file": "assets/app-C1huuq--.css",
    "src": "resources/css/app.css",
    "isEntry": true
  },
  "resources/js/app.js": {
    "file": "assets/app-DR9d2vyK.js",
    "src": "resources/js/app.js",
    "isEntry": true
  }
}
```

### File Sizes
```
public/manifest.json              0.33 kB │ gzip:  0.17 kB
public/assets/css/app-*.css     145.43 kB │ gzip: 21.50 kB
public/assets/js/app-*.js        46.65 kB │ gzip: 16.76 kB
```

---

## 🔗 Related Documentation

### Internal
- [[00-index]] - Main documentation index
- [[build-commands-guide]] - Complete build process
- [[layout-hierarchy]] - Layout architecture
- [[components-directory-structure]] - Component organization

### External
- [Vite Documentation](https://vitejs.dev/)
- [Laravel Vite Plugin](https://laravel.com/docs/vite)
- [Tailwind CSS v4](https://tailwindcss.com/docs)

---

## 📝 Changelog

| Date | Version | Change | Author |
|------|---------|--------|--------|
| 2026-04-01 | 1.0 | Initial documentation | AI Agent Team |
| 2026-04-01 | 1.1 | Added outDir explanation | AI Agent Team |

---

*Documentation conforme agli standard Laraxot - DRY + KISS compliant*
