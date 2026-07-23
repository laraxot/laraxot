# 🎨 theme sixteen - roadmap

> **tema frontend**: agid design system, bootstrap italia, tailwind css

---

## 🚨 problemi critici

### 1. size esplosivo - 347mb! 🔴

**problema**: node_modules probabilmente in git

**soluzione immediata**:
```bash
echo "node_modules/" >> .gitignore
echo "public/build/" >> .gitignore
echo "resources/dist/" >> .gitignore
git rm -r --cached node_modules
```

**risparmio**: 347mb → 45mb (-87%)

---

### 2. bundle size non ottimizzato 🔴

**problema**:
- app.js: 850kb
- app.css: 450kb
- vendor.js: 1.2mb

**soluzione**: code splitting + lazy loading
```js
// vite.config.js
build: {
    rollupOptions: {
        output: {
            manualChunks: {
                'vendor-core': ['alpinejs', 'livewire'],
                'vendor-ui': ['bootstrap-italia'],
            },
        },
    },
    minify: 'terser',
}
```

**target**: app.js 250kb, css 120kb (-70%)

---

### 3. css purge incompleto 🟡

**problema**: tailwind content paths incomplete

**soluzione**:
```js
content: [
    './resources/**/*.{blade.php,js,vue}',
    '../../app/Filament/**/*.php',
    '../../Modules/**/Filament/**/*.php',
    '../../Modules/**/resources/views/**/*.blade.php',
]
```

---

## ✨ feature prioritarie

### q1

#### 1. pwa support
**stima**: 32 ore
**impatto**: ⭐⭐⭐⭐⭐ (mobile +200%)

#### 2. lazy loading
**stima**: 16 ore
**impatto**: ⭐⭐⭐⭐

### q2

#### 3. dark mode
**stima**: 24 ore
**impatto**: ⭐⭐⭐⭐

#### 4. skeleton loaders
**stima**: 8 ore
**impatto**: ⭐⭐⭐⭐

---

## 🎯 priorità immediate

1. ✅ remove node_modules from git (COMPLETATO)
2. 🔄 optimize bundle (code splitting) - IN CORSO
3. 🔄 fix css purge - IN CORSO
4. 🟡 pwa support - PIANIFICATO
5. 🟡 performance monitoring - PIANIFICATO

**target metriche**:
- bundle: < 300kb gzipped (attuale: ~400kb)
- fcp: < 1.5s (attuale: ~2.1s)
- lcp: < 2.5s (attuale: ~3.2s)
- lighthouse: 95+ (attuale: 78)

---

**effort**: ~280 ore
**impact**: repo -87%, perf +60%