# Theme Architecture Zen - Blade File Naming Convention

**Data**: 2026-03-22  
**Status**: ✅ DOCUMENTED  
**Philosophy**: Theme-First, Module-Agnostic

---

## 🎯 FILOSOFIA ZEN

### Il Principio Fondamentale

> **"Il Tema controlla il layout, il Modulo fornisce i dati.  
> Il Tema è il vestito, il Modulo è il corpo.  
> Il CMS JSON è il cervello che coordina."**

### Perché `[slug].blade.php.old`?

**Problema**:
- File come `[slug].blade.php` nei moduli creano **accoppiamento forte**
- Il tema perde controllo sul layout
- Il modulo diventa "opinionated" su come visualizzare i dati
- **Violazione del principio: Theme-First**

**Soluzione**:
- Rinominare a `.old` → **disabilita rendering diretto**
- Usare **generic container** (`[container0]/[slug0]/index.blade.php`) nel tema
- Il modulo fornisce **solo components/blocks**
- Il CMS JSON definisce quali blocks usare

---

## 🏗 ARCHITETTURA CORRETTA

### Struttura File

```
Themes/TwentyOne/
└── resources/views/
    ├── pages/
    │   ├── index.blade.php              ← Homepage
    │   └── [container0]/
    │       ├── index.blade.php          ← Generic container (CONTROLLA IL TEMA)
    │       └── [slug0]/
    │           └── index.blade.php      ← Generic detail (CONTROLLA IL TEMA)
    └── filament/widgets/
        └── predict-table.blade.php      ← Widget bridge

Modules/Predict/
└── resources/views/
    ├── components/
    │   ├── blocks/
    │   │   ├── hero.blade.php           ← CMS block
    │   │   ├── listing.blade.php        ← CMS block
    │   │   └── detail.blade.php         ← CMS block
    │   └── predict/
    │       ├── card.blade.php           ← Reusable component
    │       └── price-chart.blade.php    ← Reusable component
    └── filament/widgets/
        └── PredictTableWidget.php       ← Filament widget

Modules/Predict/
└── app/
    └── Filament/
        └── Widgets/
            └── PredictTableWidget.php   ← Widget logic
```

### File da Rinominare

**NEI MODULI** (sempre!):

```
Modules/Predict/resources/views/
├── pages/
│   ├── index.blade.php        → index.blade.php.old  ❌
│   └── [slug].blade.php       → [slug].blade.php.old ❌
└── filament/widgets/
    └── predict-view.blade.php → predict-view.blade.php.old ❌
```

**NEL TEMA** (questi rimangono!):

```
Themes/TwentyOne/resources/views/pages/
├── index.blade.php            ✅ (rimane)
└── [container0]/
    ├── index.blade.php        ✅ (rimane - GENERIC)
    └── [slug0]/
        └── index.blade.php    ✅ (rimane - GENERIC)
```

---

## 🧘 ZEN PRINCIPLES

### 1. Theme-First

**Il Tema decide**:
- Layout della pagina
- Struttura HTML
- CSS/Tailwind classes
- Responsive design

**Il Modulo fornisce**:
- Dati (tramite Actions/Models)
- Components riutilizzabili
- Widgets (Filament)
- Blocks (CMS JSON)

### 2. Module-Agnostic

**Il Tema NON sa**:
- Quale modulo sta mostrando
- Quali dati specifici sta visualizzando
- La logica di business

**Il Tema SA**:
- Come impaginare i contenuti
- Come stilizzare i components
- Come gestire il layout

### 3. CMS JSON Bridge

```json
{
  "id": "predicts-listing",
  "slug": "predicts-listing",
  "content_blocks": {
    "it": [
      {
        "type": "predict-listing",
        "data": {
          "view": "predict::components.listing",
          "component": "Modules\\Predict\\Resources\\Views\\Components\\Listing"
        }
      }
    ]
  }
}
```

**Il CMS JSON**:
- Definisce quali blocks usare
- Specifica la view del component
- Coordina Tema ↔ Modulo

---

## 📋 CHECKLIST RINOMINA

### Quando Rinominare a `.old`

Rinomina SEMPRE a `.blade.php.old`:

- [ ] File `pages/index.blade.php` nei moduli
- [ ] File `pages/[slug].blade.php` nei moduli
- [ ] File `pages/[container0]/index.blade.php` nei moduli
- [ ] File `pages/[container0]/[slug0]/index.blade.php` nei moduli
- [ ] File `filament/widgets/*-view.blade.php` nei moduli

### Quando NON Rinominare

NON rinominare (rimangono attivi):

- [ ] File `Themes/*/resources/views/pages/index.blade.php`
- [ ] File `Themes/*/resources/views/pages/[container0]/index.blade.php`
- [ ] File `Themes/*/resources/views/pages/[container0]/[slug0]/index.blade.php`
- [ ] File `Themes/*/resources/views/filament/widgets/*.blade.php`
- [ ] File `Modules/*/resources/views/components/**/*.blade.php`
- [ ] File `Modules/*/resources/views/blocks/**/*.blade.php`

---

## 🔧 MIGRATION PATTERN

### Da (SBAGLIATO)

```php
// Modules/Predict/resources/views/pages/[slug].blade.php ❌
<x-layouts.app>
    <h1>{{ $predict->title }}</h1>
    <p>{{ $predict->description }}</p>
</x-layouts.app>
```

**Problemi**:
- Layout hardcoded nel modulo
- Tema non può personalizzare
- Accoppiamento forte

### A (CORRETTO)

```blade
{{-- Themes/TwentyOne/resources/views/pages/[container0]/[slug0]/index.blade.php ✅ --}}
<x-layouts.app>
    @volt('[container0].show')
    <div>
        {{-- CMS JSON definisce i blocks --}}
        <x-page slug="{{ $slug0 }}" container="{{ $container0 }}" />
    </div>
    @endvolt
</x-layouts.app>
```

```blade
{{-- Modules/Predict/resources/views/components/blocks/detail.blade.php ✅ --}}
<div class="predict-detail">
    <h1>{{ $predict->title }}</h1>
    <p>{{ $predict->description }}</p>
</div>
```

**Vantaggi**:
- Tema controlla layout
- Modulo fornisce solo component
- CMS JSON coordina

---

## 📚 EXAMPLES

### Example 1: Predict List

**PRIMA** (SBAGLIATO):
```
Modules/Predict/resources/views/pages/index.blade.php ❌
```

**DOPO** (CORRETTO):
```
Modules/Predict/resources/views/pages/index.blade.php.old ✅
Themes/TwentyOne/resources/views/pages/[container0]/index.blade.php ✅
Modules/Predict/resources/views/components/blocks/listing.blade.php ✅
```

### Example 2: Predict Detail

**PRIMA** (SBAGLIATO):
```
Modules/Predict/resources/views/pages/[slug].blade.php ❌
```

**DOPO** (CORRETTO):
```
Modules/Predict/resources/views/pages/[slug].blade.php.old ✅
Themes/TwentyOne/resources/views/pages/[container0]/[slug0]/index.blade.php ✅
Modules/Predict/resources/views/components/blocks/detail.blade.php ✅
```

### Example 3: Filament Widget

**PRIMA** (SBAGLIATO):
```
Modules/Predict/resources/views/filament/widgets/predict-view.blade.php ❌
```

**DOPO** (CORRETTO):
```
Modules/Predict/resources/views/filament/widgets/predict-view.blade.php.old ✅
Themes/TwentyOne/resources/views/filament/widgets/predict-table.blade.php ✅
```

---

## 🧪 TESTING

### Test 1: Verificare Rinomina

```bash
# Cerca file .blade.php nei moduli (non dovrebbero esserci pages/)
find Modules/*/resources/views/pages -name "*.blade.php" 2>/dev/null
# Deve restituire SOLO file .old
```

### Test 2: Verificare Routing

```bash
# Testa URL
curl -I http://predict.local/it/predicts
# Deve restituire 200 OK
```

### Test 3: Verificare CMS JSON

```bash
# Controlla che CMS JSON esista
cat config/local/predict/database/content/pages/predicts-listing.json
# Deve avere content_blocks definiti
```

---

## 🚨 COMMON ERRORS

### Error 1: Dimenticare di Rinominare

**Errore**:
```
Modules/Predict/resources/views/pages/[slug].blade.php  ← ANCORA ATTIVO!
```

**Sintomi**:
- Il routing usa il file del modulo (sbagliato)
- Il tema non controlla il layout
- Stile non applicato correttamente

**Fix**:
```bash
mv [slug].blade.php [slug].blade.php.old
```

### Error 2: CMS JSON Mancante

**Errore**:
```json
{
  "id": "predicts-listing",
  "content_blocks": null  ← NULL!
}
```

**Sintomi**:
- Pagina bianca
- Nessun content renderizzato

**Fix**:
```json
{
  "content_blocks": {
    "it": [
      {
        "type": "predict-listing",
        "data": {
          "view": "predict::components.listing"
        }
      }
    ]
  }
}
```

---

## 📖 RELATED DOCUMENTATION

- `docs/project/ARCHITECTURE_ZEN.md` - Zen architecture overview
- `docs/project/CMS_JSON_ARCHITECTURE.md` - CMS JSON pattern
- `docs/project/THEME_FIRST_PHILOSOPHY.md` - Theme-first principle
- `Modules/Predict/docs/BLADE_MINIMAL_LOGIC_BEST_PRACTICES.md` - Blade best practices

---

## 🎯 PRE-COMMIT CHECKLIST

Prima di commitare:

- [ ] **File rinominati**: Tutti i `pages/*.blade.php` nei moduli sono `.old`
- [ ] **CMS JSON**: Tutti i pages hanno `content_blocks` definiti
- [ ] **Theme files**: I file nel tema sono attivi (NON `.old`)
- [ ] **Components**: I components nei moduli sono attivi (NON `.old`)
- [ ] **Test**: Le pagine funzionano correttamente
- [ ] **Docs**: Documentazione aggiornata

---

**Maintained By**: AI Agents Team  
**Last Review**: 2026-03-22  
**Next Review**: After each new module/page creation
