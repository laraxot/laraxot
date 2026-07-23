# 404 Error Fix - Homepage Not Found

> **Risolto: http://127.0.0.1:8000/it/tests/homepage dava errore 404**

## 📋 Panoramica

**Data:** 2026-04-01  
**Stato:** ✅ Risolto  
**Tipo:** Errore routing + JSON mancante

---

## 🐛 Problema

```
URL: http://127.0.0.1:8000/it/tests/homepage
Errore: 404 Not Found
```

---

## 🔍 Analisi

### 1. Verifica Routing

```bash
php artisan folio:list | grep tests
```

**Risultato:**
```
GET /it/tests/{slug} ... tests.view › Themes/Sixteen/resources/views/pages/tests/[slug].blade.php
```

✅ **La rotta ESISTE**

---

### 2. Verifica File

**File Blade:**
```
✅ Esiste: Themes/Sixteen/resources/views/pages/tests/[slug].blade.php
```

**File JSON:**
```
❌ NON Esiste: config/local/fixcity/database/content/pages/tests.homepage.json
```

---

## ✅ Soluzione

### Causa Principale

Il file JSON della homepage **NON esisteva**:

```bash
# File mancante
config/local/fixcity/database/content/pages/tests.homepage.json  ❌
```

### Correzione

1. **Creato file JSON:** `tests.homepage.json`
2. **Aggiunti 7 blocchi:**
   - header-slim
   - header-main
   - navigation
   - hero
   - quick-links
   - news-carousel
   - footer-main

3. **Pulito cache Laravel:**
   ```bash
   php artisan optimize:clear
   php artisan config:clear
   ```

---

## 📄 File JSON Creato

**Percorso:** `config/local/fixcity/database/content/pages/tests.homepage.json`

**Struttura:**
```json
{
  "id": "tests.homepage",
  "slug": "tests.homepage",
  "content_blocks": {
    "it": [
      {
        "id": "block-001",
        "type": "header-slim",
        "data": {...}
      },
      {
        "id": "block-002",
        "type": "header-main",
        "data": {...}
      },
      ...
    ]
  }
}
```

---

## 🎯 Verifica Funzionamento

### 1. Controlla Rotte

```bash
cd /var/www/_bases/base_fixcity_fila5/laravel
php artisan folio:list | grep "tests/homepage"
```

**Atteso:**
```
GET /it/tests/{slug} ... tests.view
```

---

### 2. Controlla JSON

```bash
ls -la config/local/fixcity/database/content/pages/tests.homepage.json
```

**Atteso:**
```
-rw-r--r-- 1 zorin zorin  5000+ Apr  1 18:00 tests.homepage.json
```

---

### 3. Test Pagina

```
http://127.0.0.1:8000/it/tests/homepage
```

**Atteso:** Homepage visibile con tutti i blocchi

---

## 🚨 Errori Comuni Correlati

### Errore 1: `[slug].blade.php` usa `@include` invece di `<x-page>`

**❌ SBAGLIATO:**
```blade
<x-layouts.app>
    @volt('tests.view')
    <div>
        @include('pub_theme::components.page', [...])
    </div>
    @endvolt
</x-layouts.app>
```

**✅ CORRETTO:**
```blade
<x-layouts.app>
    @volt('tests.view')
        <x-page side="content" :slug="$pageSlug" :data="$data" />
    @endvolt
</x-layouts.app>
```

**Perché:**
- `<x-page>` è un componente Blade registrato
- `@include` è per viste semplici
- I componenti hanno logica propria (caricamento JSON)

---

### Errore 2: JSON con struttura sbagliata

**❌ SBAGLIATO:**
```json
{
  "blocks": [...]  // Struttura vecchia
}
```

**✅ CORRETTO:**
```json
{
  "content_blocks": {
    "it": [...]  // Struttura nuova con localizzazione
  }
}
```

**Perché:**
- Supporto multi-lingua (it/en)
- Compatibilità con `page.blade.php`

---

### Errore 3: Cache non pulita

**Sintomi:**
- Modifichi JSON ma la pagina non cambia
- Aggiungi blocchi ma non compaiono

**Soluzione:**
```bash
php artisan optimize:clear
php artisan config:clear
php artisan view:clear
```

---

## 📊 Checklist Debug 404

### Per Pagine Tests

```markdown
## Routing
- [ ] Rotte Folio verificate: php artisan folio:list
- [ ] Rotta /it/tests/{slug} esiste
- [ ] Route name: tests.view

## File Blade
- [ ] [slug].blade.php esiste
- [ ] Usa <x-page> (NON @include)
- [ ] Estende <x-layouts.app>
- [ ] Volt component corretto

## File JSON
- [ ] tests.{slug}.json esiste
- [ ] Struttura: content_blocks.it[]
- [ ] Blocchi con "view" definita
- [ ] Percorso JSON corretto

## Cache
- [ ] php artisan optimize:clear
- [ ] php artisan config:clear
- [ ] php artisan view:clear

## Test
- [ ] http://127.0.0.1:8000/it/tests/{slug} funziona
- [ ] Blocchi renderizzati
- [ ] Nessun errore 500
```

---

## 🔗 Documenti Correlati

- [Component Namespace](./architecture/component-namespace.md) - Perché usare `<x-page>`
- [Layout Architecture](./architecture/layout-architecture.md) - Gerarchia layout
- [Blocks Implementation](./BLOCKS_IMPLEMENTATION.md) - Blocchi Homepage
- [Product Brief](./design-comuni/product-brief.md) - Panoramica progetto

---

## 🎓 Lezioni Apprese

### 1. Routing Folio

```
Folio crea rotte automaticamente:
[slug].blade.php → /it/tests/{slug}

MA: La rotta esiste SEMPRE
Il contenuto dipende dal JSON!
```

---

### 2. Struttura JSON

```json
{
  "slug": "tests.homepage",  ← DEVE corrispondere al file
  "content_blocks": {
    "it": [  ← Supporto multi-lingua
      {
        "type": "hero",
        "data": {
          "view": "pub_theme::components.blocks.hero.homepage"
        }
      }
    ]
  }
}
```

---

### 3. Cache Laravel

**Quando pulire:**
- ✅ Dopo aver creato/modificato JSON
- ✅ Dopo aver modificato Blade
- ✅ Dopo aver cambiato config
- ✅ Prima di testare una pagina

**Comandi:**
```bash
php artisan optimize:clear  # Tutto
php artisan config:clear    # Solo config
php artisan view:clear      # Solo views
```

---

## ✅ Stato Attuale

**Homepage:** ✅ **FUNZIONANTE**  
**File JSON:** ✅ **Creato (tests.homepage.json)**  
**Blocchi:** ✅ **7 blocchi implementati**  
**Cache:** ✅ **Pulita**

---

**Data Risoluzione:** 2026-04-01  
**Versione:** 1.0  
**Stato:** ✅ **Risolto**

---

*"File JSON obbligatorio per ogni pagina"*  
*"Cache sempre pulita dopo modifiche"*  
*"<x-page> componente, NON @include"*
