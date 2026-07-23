# Component Namespace Architecture - DRY + KISS

> **Perché usiamo `<x-pub_theme::*>` e NON `<x-sixteen::*>`**

## 📋 Panoramica

**Data:** 2026-04-01  
**Stato:** ✅ Documentato  
**Tipo:** Architettura namespace componenti

---

## 🐛 Errori da Evitare

### ❌ ERRORE 1: Usare `<x-sixteen::page>`

```blade
{{-- ❌ SBAGLIATO: Namespace "sixteen" non esiste --}}
<x-sixteen::page side="content" :slug="$pageSlug" :data="$data" />
```

**Perché è sbagliato:**
1. Il namespace `sixteen` **NON è registrato**
2. Il tema può cambiare (Sixteen, Zero, etc.)
3. Violazione principio DRY

---

### ❌ ERRORE 2: Usare `<x-sixteen::page>` invece di `<x-page>`

```blade
{{-- ❌ SBAGLIATO: Nome componente errato --}}
<x-sixteen::page side="content" :slug="$pageSlug" :data="$data" />

{{-- ✅ CORRETTO: Componente registrato --}}
<x-page side="content" :slug="$pageSlug" :data="$data" />
```

**Perché è sbagliato:**
1. Il componente è registrato come `page`, NON `sixteen::page`
2. Laravel Blade usa alias: `Blade::alias(..., 'pub_theme::page')`
3. Sintassi corretta: `<x-page>` o `<x-pub_theme::page>`

---

## ✅ SOLUZIONE: Architettura Corretta

### Namespace Registrati

**File:** `laravel/Themes/Sixteen/app/Providers/ThemeServiceProvider.php`

```php
// Line 94: Register views with 'pub_theme' namespace
$this->loadViewsFrom(__DIR__.'/../../resources/views', 'pub_theme');

// Line 238: Register component namespace
Blade::componentNamespace($componentNamespace, 'pub_theme');

// Line 242: Register page component alias
Blade::alias('Themes\Sixteen\View\Components\Page', 'pub_theme::page');
```

---

### Perché `pub_theme` e NON `sixteen`

#### 1. **Portabilità del Tema**

Il namespace `pub_theme` è **agnostico** - può puntare a qualsiasi tema:

```php
// config/local/fixcity/xra.php
'pub_theme' => 'Sixteen',  // Può diventare 'Zero', 'Seventeen', etc.
```

**Vantaggi:**
- ✅ Cambi tema senza modificare le viste
- ✅ Codice riutilizzabile
- ✅ DRY rispettato

**Se usassi `sixteen`:**
```blade
{{-- ❌ Con namespace fisso --}}
<x-sixteen::page />  <!-- Se cambi tema, devi modificare TUTTE le viste -->

{{-- ✅ Con namespace dinamico --}}
<x-pub_theme::page />  <!-- Se cambi tema, cambi solo la config -->
```

---

#### 2. **Alias Componenti**

Laravel permette alias per componenti:

```php
// ThemeServiceProvider.php Line 242
Blade::alias('Themes\Sixteen\View\Components\Page', 'pub_theme::page');
```

**Risultato:**
- `<x-page>` → Risolto a `pub_theme::page`
- `<x-pub_theme::page>` → Esplicito, stesso risultato

**Perché `<x-page>` è preferibile:**
- ✅ Più corto
- ✅ Più leggibile
- ✅ Laravel risolve automaticamente il namespace

---

### Gerarchia Namespace

```
Blade Component Resolution:

1. <x-page>
   ↓
   Laravel cerca alias → 'pub_theme::page'
   ↓
   ThemeServiceProvider → loadViewsFrom('pub_theme')
   ↓
   Themes/Sixteen/resources/views/components/page.blade.php

2. <x-pub_theme::page>
   ↓
   Esplicito namespace 'pub_theme'
   ↓
   ThemeServiceProvider → loadViewsFrom('pub_theme')
   ↓
   Themes/Sixteen/resources/views/components/page.blade.php

3. <x-sixteen::page>  ← ❌ ERRORE!
   ↓
   Namespace 'sixteen' NON registrato
   ↓
   EXCEPTION: View [sixteen::page] not found
```

---

## 📚 Componenti Registrati

### Namespace `pub_theme`

**File:** `ThemeServiceProvider.php`

```php
// Line 94: Views namespace
$this->loadViewsFrom(__DIR__.'/../../resources/views', 'pub_theme');

// Line 238: Components namespace
Blade::componentNamespace($componentNamespace, 'pub_theme');

// Line 242: Component alias
Blade::alias('Themes\Sixteen\View\Components\Page', 'pub_theme::page');

// Line 245-247: Anonymous components
Blade::anonymousComponentPath($componentsPath, 'pub_theme');
```

---

### Componenti Disponibili

| Componente | Uso Corretto | Uso Sbagliato |
|------------|--------------|---------------|
| Page | `<x-page>` o `<x-pub_theme::page>` | `<x-sixteen::page>` |
| Header | `<x-section slug="header" />` | `<x-sixteen::header>` |
| Footer | `<x-section slug="footer" />` | `<x-sixteen::footer>` |
| Blocks | `<x-pub_theme::blocks.*>` | `<x-sixteen::blocks.*>` |
| Layouts | `<x-layouts.app>` | `<x-sixteen::layouts.app>` |

---

## 🎯 Best Practices

### 1. Usa `<x-page>` (senza namespace)

```blade
{{-- ✅ CORRETTO: Laravel risolve automaticamente --}}
<x-page side="content" :slug="$pageSlug" :data="$data" />

{{-- ❌ SBAGLIATO: Namespace non necessario --}}
<x-pub_theme::page side="content" :slug="$pageSlug" :data="$data" />
```

**Perché:**
- ✅ Più corto e leggibile
- ✅ Laravel usa l'alias registrato
- ✅ Se cambi namespace, Laravel aggiorna automaticamente

---

### 2. Usa `<x-pub_theme::blocks.*>` per i blocchi

```blade
{{-- ✅ CORRETTO: Namespace esplicito per blocchi --}}
<x-pub_theme::blocks.hero.homepage
    :title="$block['title']"
    :subtitle="$block['subtitle']"
/>

{{-- ❌ SBAGLIATO: Namespace "sixteen" --}}
<x-sixteen::blocks.hero.homepage />
```

**Perché:**
- ✅ `pub_theme` è il namespace standard
- ✅ Portabile tra temi diversi
- ✅ DRY rispettato

---

### 3. Usa `<x-section>` per Header/Footer

```blade
{{-- ✅ CORRETTO: Section component --}}
<x-section slug="header" />
<x-section slug="footer" tpl="slim" />

{{-- ❌ SBAGLIATO: Componenti diretti --}}
<x-pub_theme::header />
<x-pub_theme::footer />
```

**Perché:**
- ✅ `<x-section>` è un wrapper che carica sezioni dinamiche
- ✅ Permette di cambiare header/footer via JSON
- ✅ Flessibilità massima

---

## 🔄 Flusso Rendering

### Componente `<x-page>`

```
1. Blade: <x-page side="content" :slug="$pageSlug" :data="$data" />
   ↓
2. Laravel: Cerca alias → 'pub_theme::page'
   ↓
3. ThemeServiceProvider: loadViewsFrom('pub_theme')
   ↓
4. File: Themes/Sixteen/resources/views/components/page.blade.php
   ↓
5. Carica JSON: config/local/fixcity/database/content/pages/{slug}.json
   ↓
6. Renderizza blocchi: @foreach($blocks as $block)
   ↓
7. Include view: @includeIf($blockData['view'])
   ↓
8. Response: HTML completo
```

---

## 📊 Confronto Architetture

### Architettura SBAGLIATA

```blade
{{-- Namespace fisso "sixteen" --}}
<x-sixteen::page />
<x-sixteen::header />
<x-sixteen::footer />
<x-sixteen::blocks.hero />

Problemi:
❌ Namespace "sixteen" non registrato
❌ Se cambi tema, devi modificare TUTTE le viste
❌ Violazione DRY
❌ Codice non portabile
```

---

### Architettura CORRETTA

```blade
{{-- Namespace dinamico "pub_theme" --}}
<x-page />                          {{-- Alias automatico --}}
<x-section slug="header" />         {{-- Section wrapper --}}
<x-section slug="footer" />         {{-- Section wrapper --}}
<x-pub_theme::blocks.hero />        {{-- Namespace esplicito --}}

Vantaggi:
✅ Namespace "pub_theme" registrato
✅ Cambi tema → cambi solo config
✅ DRY rispettato
✅ Codice portabile
```

---

## 🎯 Principi Applicati

### 1. DRY (Don't Repeat Yourself)

**Prima (SBAGLIATO):**
```blade
<!-- Se cambi tema, devi modificare 100+ file -->
<x-sixteen::page />
<x-sixteen::header />
<x-sixteen::footer />
```

**Dopo (CORRETTO):**
```blade
<!-- Se cambi tema, cambi solo config/xra.php -->
<x-page />
<x-section slug="header" />
<x-section slug="footer" />

<!-- config/local/fixcity/xra.php -->
'pub_theme' => 'Zero'  <!-- Da 'Sixteen' a 'Zero' -->
```

---

### 2. KISS (Keep It Simple, Stupid)

**Prima (COMPLESSO):**
```blade
<x-sixteen::page side="content" :slug="$pageSlug" :data="$data" />
```

**Dopo (SEMPLICE):**
```blade
<x-page side="content" :slug="$pageSlug" :data="$data" />
```

**Vantaggi:**
- ✅ 10 caratteri in meno
- ✅ Più leggibile
- ✅ Laravel fa il lavoro pesante

---

### 3. Separation of Concerns

| Layer | Responsabilità | Namespace |
|-------|----------------|-----------|
| Theme Config | Definire tema attivo | `config/xra.php` |
| Theme Service | Registrare namespace | `ThemeServiceProvider` |
| Blade Views | Usare componenti | `<x-page>`, `<x-pub_theme::*>` |
| Components | Implementazione | `resources/views/components/` |

---

## 📖 Esempi Pratici

### Esempio 1: Homepage

```blade
{{-- ✅ CORRETTO --}}
<x-layouts.app>
    @volt('tests.view')
        <x-page side="content" :slug="$pageSlug" :data="$data" />
    @endvolt
</x-layouts.app>

{{-- ❌ SBAGLIATO --}}
<x-layouts.app>
    @volt('tests.view')
        <x-sixteen::page side="content" :slug="$pageSlug" :data="$data" />
    @endvolt
</x-layouts.app>
```

---

### Esempio 2: Blocco Hero

```blade
{{-- ✅ CORRETTO --}}
<x-pub_theme::blocks.hero.homepage
    :title="$block['title']"
    :subtitle="$block['subtitle']"
    :image="$block['image']"
/>

{{-- ❌ SBAGLIATO --}}
<x-sixteen::blocks.hero.homepage
    :title="$block['title']"
    :subtitle="$block['subtitle']"
/>
```

---

### Esempio 3: Section Header

```blade
{{-- ✅ CORRETTO --}}
<x-section slug="header" />

{{-- ❌ SBAGLIATO --}}
<x-pub_theme::header />
<x-sixteen::header />
```

---

## ✅ Checklist Verifica

### Per Nuovi Componenti

```markdown
- [ ] Usa namespace `pub_theme` (NON `sixteen`)
- [ ] Componente registrato in ThemeServiceProvider
- [ ] Alias definito se necessario
- [ ] Documentato con link bidirezionali
```

### Per Nuove Viste

```markdown
- [ ] Usa `<x-page>` (NON `<x-sixteen::page>`)
- [ ] Usa `<x-section slug="*" />` per header/footer
- [ ] Usa `<x-pub_theme::blocks.*>` per blocchi
- [ ] Namespace esplicito solo per blocchi
```

---

## 🔗 Documenti Correlati

- [Layout Architecture](./layout-architecture.md)
- [Page Component Architecture](./page-component-architecture.md)
- [Blocks Implementation](../BLOCKS_IMPLEMENTATION.md)
- [ThemeServiceProvider](../../app/Providers/ThemeServiceProvider.php)
- [Replikate.txt](../prompts/replikate.txt)

---

## 🎓 Lezioni Apprese

### Architettura

1. **Namespace dinamici:** `pub_theme` può puntare a qualsiasi tema
2. **Alias Blade:** `<x-page>` più corto di `<x-pub_theme::page>`
3. **Sezioni dinamiche:** `<x-section>` per header/footer

### Manutenibilità

1. **Cambi tema:** 1 file config vs 100+ viste
2. **Codice portabile:** Stesse viste, temi diversi
3. **DRY:** Namespace una volta, usato ovunque

### Best Practices

1. **Sempre `pub_theme`:** Mai namespace fissi (`sixteen`)
2. **Alias automatici:** `<x-page>` preferibile
3. **Documentazione:** Spiegare il "perché", non solo il "come"

---

**Data:** 2026-04-01  
**Versione:** 1.0  
**Stato:** ✅ Completato

---

*"Namespace dinamico 'pub_theme', NON fisso 'sixteen'"*  
*"<x-page> preferibile a <x-pub_theme::page>"*  
*"DRY + KISS + Portabilità"*
