# Build System Best Practices - Tema Sixteen

## 🚀 Panoramica

Questo documento definisce le best practices per il sistema di build del tema Sixteen, basato su **Vite 6.3.5** e **Tailwind CSS 3.4.17**. Il sistema è ottimizzato per performance, developer experience e conformità alle linee guida PA.

## 🔧 Configurazione Vite

### Configurazione Base Corretta

```javascript
// vite.config.js
import { defineConfig } from 'vite';
import laravel, { refreshPaths } from 'laravel-vite-plugin';

export default defineConfig({
    build: {
        outDir: './public',
        emptyOutDir: false,
        manifest: 'manifest.json',
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['alpinejs', 'swiper'],
                    filament: ['@filamentphp/forms', '@filamentphp/support'],
                },
            },
        },
    },
    plugins: [
        laravel({
            publicDirectory: '../../../public_html/',
            input: [
                __dirname + '/resources/css/app.css',
                __dirname + '/resources/js/app.js',
            ],
            refresh: [
                ...refreshPaths,
                'app/Livewire/**',
                'resources/views/**/*.blade.php',
            ],
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources',
        },
    },
    optimizeDeps: {
        include: ['alpinejs', 'swiper', 'flowbite'],
    },
});
```

### Regola Critica - Direttiva @vite

**SEMPRE usare il secondo parametro 'themes/Sixteen' nella direttiva @vite**

```blade
{{-- ✅ CORRETTO - Con parametro tema --}}
@vite(['resources/css/app.css', 'resources/js/app.js'], 'themes/Sixteen')

{{-- ✅ CORRETTO - Configurazione separata --}}
@vite(['resources/css/app.css'], 'themes/Sixteen/dist')
@vite(['resources/js/app.js'], 'themes/Sixteen/dist')

{{-- ❌ ERRATO - Senza parametro tema --}}
@vite(['resources/css/app.css', 'resources/js/app.js'])
```

## 📦 Script di Build

### Package.json Scripts

```json
{
    "scripts": {
        "dev": "vite",
        "build": "vite build",
        "build:analyze": "vite build --mode analyze",
        "build:production": "vite build --mode production",
        "preview": "vite preview",
        "copy": "cp -r ./public/* ../../../public_html/themes/Sixteen/",
        "copy:watch": "nodemon --watch ./public --exec 'npm run copy'",
        "lint": "eslint resources/js/**/*.js",
        "lint:fix": "eslint resources/js/**/*.js --fix",
        "format": "prettier --write resources/**/*.{js,css,blade.php}",
        "test": "jest",
        "test:watch": "jest --watch"
    }
}
```

### Script Avanzati

```bash
#!/bin/bash
# build-advanced.sh

echo "🚀 Build avanzato tema Sixteen"

# Pulizia cache
echo "🧹 Pulizia cache..."
rm -rf node_modules/.vite
rm -rf public/*

# Installazione dipendenze
echo "📦 Installazione dipendenze..."
npm ci

# Build produzione
echo "🔨 Build produzione..."
npm run build:production

# Analisi bundle
echo "📊 Analisi bundle..."
npm run build:analyze

# Copia asset
echo "📋 Copia asset..."
npm run copy

# Verifica build
echo "✅ Verifica build..."
if [ -f "public/manifest.json" ]; then
    echo "✅ Build completato con successo!"
    echo "📁 Asset disponibili in: public/"
    echo "📄 Manifest: public/manifest.json"
else
    echo "❌ Errore nel build!"
    exit 1
fi
```

## 🎯 Ottimizzazioni Performance

### Code Splitting

```javascript
// vite.config.js - Code splitting ottimizzato
export default defineConfig({
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    // Vendor chunks
                    vendor: ['alpinejs', 'swiper'],
                    
                    // Filament chunks
                    filament: ['@filamentphp/forms', '@filamentphp/support'],
                    
                    // UI libraries
                    ui: ['flowbite', 'daisyui'],
                    
                    // Theme specific
                    theme: ['./resources/js/custom.js'],
                },
            },
        },
    },
});
```

### Tree Shaking

```javascript
// Configurazione tree shaking
export default defineConfig({
    build: {
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true,
                drop_debugger: true,
            },
        },
    },
    optimizeDeps: {
        include: [
            'alpinejs',
            'swiper',
            'flowbite',
            '@tailwindcss/forms',
            '@tailwindcss/typography',
        ],
    },
});
```

### Compressione Asset

```javascript
// vite.config.js - Compressione avanzata
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import compression from 'vite-plugin-compression';

export default defineConfig({
    plugins: [
        laravel({
            // ... configurazione laravel
        }),
        compression({
            algorithm: 'gzip',
            ext: '.gz',
        }),
        compression({
            algorithm: 'brotliCompress',
            ext: '.br',
        }),
    ],
});
```

## 🔍 Analisi Bundle

### Configurazione Analisi

```javascript
// vite.config.js - Analisi bundle
import { defineConfig } from 'vite';
import { visualizer } from 'rollup-plugin-visualizer';

export default defineConfig({
    plugins: [
        // ... altri plugin
        visualizer({
            filename: 'dist/stats.html',
            open: true,
            gzipSize: true,
            brotliSize: true,
        }),
    ],
});
```

### Script Analisi

```json
{
    "scripts": {
        "analyze": "vite build --mode analyze",
        "analyze:serve": "npx serve dist",
        "bundle-report": "npm run analyze && open dist/stats.html"
    }
}
```

## 🚀 Hot Reload e Development

### Configurazione Development

```javascript
// vite.config.js - Development ottimizzato
export default defineConfig({
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            host: 'localhost',
        },
        watch: {
            usePolling: true,
            interval: 100,
        },
    },
    plugins: [
        laravel({
            refresh: [
                'resources/views/**/*.blade.php',
                'app/Livewire/**',
                'resources/css/**/*.css',
                'resources/js/**/*.js',
            ],
        }),
    ],
});
```

### File Watching Ottimizzato

```javascript
// vite.config.js - File watching
export default defineConfig({
    server: {
        watch: {
            usePolling: true,
            interval: 100,
            ignored: [
                '**/node_modules/**',
                '**/storage/**',
                '**/vendor/**',
                '**/.git/**',
            ],
        },
    },
});
```

## 📱 Build Responsive

### Configurazione Multi-Device

```javascript
// vite.config.js - Build responsive
export default defineConfig({
    build: {
        rollupOptions: {
            output: {
                assetFileNames: (assetInfo) => {
                    const info = assetInfo.name.split('.');
                    const ext = info[info.length - 1];
                    if (/png|jpe?g|svg|gif|tiff|bmp|ico/i.test(ext)) {
                        return `images/[name]-[hash][extname]`;
                    }
                    if (/css/i.test(ext)) {
                        return `css/[name]-[hash][extname]`;
                    }
                    return `assets/[name]-[hash][extname]`;
                },
                chunkFileNames: 'js/[name]-[hash].js',
                entryFileNames: 'js/[name]-[hash].js',
            },
        },
    },
});
```

## 🔒 Sicurezza Build

### Content Security Policy

```javascript
// vite.config.js - CSP
export default defineConfig({
    server: {
        headers: {
            'Content-Security-Policy': [
                "default-src 'self'",
                "script-src 'self' 'unsafe-inline' 'unsafe-eval'",
                "style-src 'self' 'unsafe-inline'",
                "img-src 'self' data: https:",
                "font-src 'self' https:",
                "connect-src 'self' ws: wss:",
            ].join('; '),
        },
    },
});
```

### Source Maps Sicuri

```javascript
// vite.config.js - Source maps
export default defineConfig({
    build: {
        sourcemap: process.env.NODE_ENV === 'development',
        minify: process.env.NODE_ENV === 'production' ? 'terser' : false,
    },
});
```

## 📊 Monitoraggio Performance

### Lighthouse CI

```yaml
# .lighthouserc.js
module.exports = {
    ci: {
        collect: {
            url: ['http://localhost:8000'],
            numberOfRuns: 3,
        },
        assert: {
            assertions: {
                'categories:performance': ['warn', { minScore: 0.9 }],
                'categories:accessibility': ['error', { minScore: 0.95 }],
                'categories:best-practices': ['warn', { minScore: 0.9 }],
                'categories:seo': ['warn', { minScore: 0.9 }],
            },
        },
        upload: {
            target: 'temporary-public-storage',
        },
    },
};
```

### Bundle Analyzer

```javascript
// vite.config.js - Bundle analyzer
import { defineConfig } from 'vite';
import { visualizer } from 'rollup-plugin-visualizer';

export default defineConfig({
    plugins: [
        // ... altri plugin
        visualizer({
            filename: 'dist/bundle-analysis.html',
            open: true,
            gzipSize: true,
            brotliSize: true,
            template: 'treemap',
        }),
    ],
});
```

## 🧪 Testing Build

### Test Build

```javascript
// test-build.js
import { build } from 'vite';
import { expect } from 'chai';

describe('Build Test', () => {
    it('should build successfully', async () => {
        const result = await build({
            configFile: 'vite.config.js',
            mode: 'production',
        });
        
        expect(result).to.have.property('output');
        expect(result.output).to.be.an('array');
    });
});
```

### Script Test

```json
{
    "scripts": {
        "test:build": "node test-build.js",
        "test:build:ci": "npm run test:build -- --ci",
        "validate:build": "npm run build && npm run test:build"
    }
}
```

## 🔄 CI/CD Integration

### GitHub Actions

```yaml
# .github/workflows/build.yml
name: Build Tema Sixteen

on:
  push:
    branches: [main, develop]
  pull_request:
    branches: [main]

jobs:
  build:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v3
    
    - name: Setup Node.js
      uses: actions/setup-node@v3
      with:
        node-version: '18'
        cache: 'npm'
        cache-dependency-path: 'laravel/Themes/Sixteen/package-lock.json'
    
    - name: Install dependencies
      run: |
        cd laravel/Themes/Sixteen
        npm ci
    
    - name: Build
      run: |
        cd laravel/Themes/Sixteen
        npm run build:production
    
    - name: Test build
      run: |
        cd laravel/Themes/Sixteen
        npm run test:build
    
    - name: Upload artifacts
      uses: actions/upload-artifact@v3
      with:
        name: build-artifacts
        path: laravel/Themes/Sixteen/public/
```

## 📋 Checklist Build

### Pre-Build
- [ ] Verificare dipendenze aggiornate
- [ ] Controllare configurazione Vite
- [ ] Verificare file di input
- [ ] Controllare variabili ambiente

### Build
- [ ] Eseguire build produzione
- [ ] Verificare manifest generato
- [ ] Controllare dimensioni bundle
- [ ] Testare asset generati

### Post-Build
- [ ] Copiare asset in directory finale
- [ ] Verificare funzionamento
- [ ] Testare performance
- [ ] Documentare build

## 🆘 Troubleshooting

### Problemi Comuni

#### Asset non trovati
```bash
# Verificare manifest
cat public/manifest.json

# Verificare path asset
ls -la public/assets/
```

#### Build lento
```bash
# Pulire cache
rm -rf node_modules/.vite
rm -rf public/*

# Reinstallare dipendenze
npm ci
```

#### Errori di dipendenze
```bash
# Aggiornare dipendenze
npm update

# Verificare compatibilità
npm audit
```

## 📚 Riferimenti

### Documentazione Ufficiale
- [Vite Documentation](https://vitejs.dev/)
- [Laravel Vite Plugin](https://laravel-vite.dev/)
- [Tailwind CSS](https://tailwindcss.com/docs)

### Strumenti
- [Bundle Analyzer](https://github.com/webpack-contrib/webpack-bundle-analyzer)
- [Lighthouse CI](https://github.com/GoogleChrome/lighthouse-ci)
- [Vite Plugin Compression](https://github.com/vbenjs/vite-plugin-compression)

---

**Ultimo aggiornamento**: Gennaio 2025  
**Versione**: 1.0.0  
**Compatibilità**: Vite 6.x, Laravel 10+, Node.js 18+


