# PHASE 2: Visual Enhancement - CSS/JS Only
**Focus**: Massimizzare somiglianza visiva SENZA toccare l'HTML

---

## 📋 OBIETTIVO

**Target**: Raggiungere 90%+ parità visiva lavorando SOLO su:
- CSS (comune-custom.css, tailwind.config.js)
- JavaScript (comune-functions.js)
- Build configuration (vite.config.js, package.json)

**VINCOLI**:
- ❌ NO modifiche all'HTML (blade.php, .json)
- ✅ Solo CSS/JS per migliorare visualizzazione

---

## 📁 FILE CSS/JS

### Core CSS Files
```
resources/assets/css/comune-custom.css    ← Custom overrides
tailwind.config.js                      ← Theme config
resources/assets/css/app.css           ← Main entry (se esiste)
```

### Core JS Files  
```
resources/assets/js/comune-functions.js    ← Custom functions
resources/js/app.js                 ← Main entry (se esiste)
```

### Build Output
```
public/assets/app-*.css             ← Compiled
public/assets/app-*.js              ← Compiled
```

---

## 🎨 AREE DI MIGLIORAMENTO

### 1. Header & Navigation
- AGID Blue (#0066CC) - ✅ Presente
- Header slim styling - ⏳ Da verificare
- Mobile responsive - ⏳ Da verificare

### 2. Color Palette
- Primary: #007A52 (verde PA) - ✅ Configurato
- Secondary: #003D73 (blu footer) - ✅ Presente
- Accent: #FF6600 - ⏳ Verificare uso

### 3. Typography
- Font: Titillium Web - ⏳ Verificare caricamento
- Scale: Tailwind default - ✅

### 4. Components
- Cards styling - ⏳ Da analizzare
- Buttons - ⏳ Da analizzare  
- Forms - ⏳ Da analizzare

### 5. Animations & Interactions
- Smooth transitions - ⏳ Da implementare
- Hover effects - ⏳ Da migliorare
- Alpine.js - ✅ Presente

---

## 🔧 BUILD COMMANDS

```bash
# Build CSS/JS
cd /var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen
npm run build

# Copy to public_html
npm run copy

# Watch mode
npm run copy:watch
```

---

## 📊 PROGRESS TRACKING

### Checklist
- [ ] Analisi gap visivi (screenshots)
- [ ] Identificazione CSS mancanti
- [ ] Identificazione JS mancanti
- [ ] Applicazione CSS fix
- [ ] Applicazione JS fix
- [ ] Build & copy verificati
- [ ] Screenshot confronto post-fix

---

## 🔄 WORKFLOW

1. **Analisi**: Confronta screenshot reference vs local
2. **Identificazione**: Elenca gap CSS/JS
3. **Implementazione**: Applica fix solo CSS/JS
4. **Build**: `npm run build && npm run copy`
5. **Verifica**: Nuovo screenshot e confronto
6. **Documentazione**: Aggiorna docs/

---

## 📞 COMANDI RAPIDI

```bash
# Build e copia
cd laravel/Themes/Sixteen && npm run build && npm run copy

# Verifica output
ls -la public/assets/
```

---

**Status**: 🔵 READY TO START  
**Next**: Esecuzione analisi gap visivi