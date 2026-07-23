# Build Troubleshooting Guide

**Tema:** Sixteen  
**Scope:** Vite, npm, asset build  
**Data:** 2026-06-03

---

## Comandi Standard

```bash
# Build production
cd laravel/Themes/Sixteen
npm run build

# Build with watch (dev)
npm run dev

# Verifica bundle
cat public_html/themes/Sixteen/manifest.json
```

---

## Problemi Risolti

### 1. CSS: Cluster tremano al hover (STORY-123)

**File:** `resources/css/app/07-map-clusters-and-leaflet.css`

**❌ ERRATO:**
```css
.geo-cluster-wrapper { transition: transform 0.2s ease, box-shadow 0.2s ease !important; }
.geo-cluster-wrapper:hover { transform: scale(1.1) !important; }
```

**✅ CORRETTO:**
```css
.geo-cluster-wrapper { 
    /* NO transform !important */
    box-shadow: 0 2px 8px rgba(0,0,0,.35);
}
.geo-cluster-wrapper:hover { 
    /* Solo box-shadow, no transform:scale */
    box-shadow: 0 4px 16px rgba(0,0,0,.45);
}
```

**Lezione:** `transform: scale()` con `!important` sposta il centro visivo rispetto a `iconAnchor: (40,40)`, causando instabilità.

---

### 2. Bundle non include Leaflet

**Sintomo:** `map-lit-*.js` piccolo (<50KB), `L` undefined

**Fix vite.config.js:**
```javascript
resolve: {
    alias: {
        'leaflet': path.resolve(__dirname, 'node_modules/leaflet/dist/leaflet.js'),
        'leaflet.markercluster': path.resolve(__dirname, 'node_modules/leaflet.markercluster/dist/leaflet.markercluster.js'),
    }
}
```

### 2. Manifest mancante

**Sintomo:** 404 su assets

**Fix:**
```bash
cd laravel/Themes/Sixteen
rm -rf node_modules
npm install
npm run build
```

### 3. View cache stale

**Sintomo:** Cambiamenti JS non visibili

**Fix:**
```bash
cd laravel
php artisan view:clear
php artisan cache:clear
```

---

## Verifica Build

```bash
# Controlla dimensione bundle
ls -lh public_html/themes/Sixteen/assets/map-lit-*.js
# → ~220KB (corretto)

# Verifica contenuto
curl -s http://127.0.0.1:8000/themes/Sixteen/assets/map-lit-*.js | grep -o "leaflet\|markerCluster" | wc -l
```

---

## Collegamenti

- [THEME_INTEGRATION_PATTERNS.md](THEME_INTEGRATION_PATTERNS.md)
