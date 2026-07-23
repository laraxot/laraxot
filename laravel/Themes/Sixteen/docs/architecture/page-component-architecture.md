# Page Component Architecture - DRY + KISS

> **Perché `<x-page>` funziona e `<x-sixteen::page>` no**

## 📋 Panoramica

**Data:** 2026-04-01  
**Stato:** ✅ Documentato  
**Tipo:** Architettura componenti CMS  

---

## 🐛 I 2 Errori Fondamentali

### ❌ ERRORE 1: `<x-sixteen::page>` - Namespace non registrato

```blade
{{-- ❌ SBAGLIATO: Exception "View [sixteen::page] not found" --}}
<x-sixteen::page side="content" :slug="$pageSlug" :data="$data" />
```

**Perché fallisce:**
1. Il namespace `sixteen` **NON è registrato** in `ThemeServiceProvider`
2. Il namespace registrato è `pub_theme`, NON `sixteen`
3. Se cambi tema (es. da `Sixteen` a `Zero`), devi modificare TUTTE le viste

**Exception:**
```
InvalidArgumentException
View [sixteen::page] not found.
```

---

### ❌ ERRORE 2: Confondere Component PHP con Blade View

```blade
{{-- ❌ SBAGLIATO: Stai chiamando la view direttamente --}}
<x-sixteen::page />

{{-- ✅ CORRETTO: Usi il componente Blade registrato --}}
<x-page />
```

**Spiegazione:**
- Il componente PHP `Modules/Cms/app/View/Components/Page.php` contiene la **logica**
- La view Blade `Themes/Sixteen/resources/views/components/page.blade.php` è il **template minimale**
- Laravel registra il componente come `<x-page>` (alias automatico)
- Non devi mai usare `<x-sixteen::page>` perché il namespace non esiste

---

## ✅ SOLUZIONE: Architettura Corretta

### Component Registration

**File:** `laravel/Themes/Sixteen/app/Providers/ThemeServiceProvider.php`

```php
// Line 94: Register views with 'pub_theme' namespace
$this->loadViewsFrom(__DIR__.'/../../resources/views', 'pub_theme');

// Line 238: Register component namespace
Blade::componentNamespace($componentNamespace, 'pub_theme');

// Line 242: Register page component alias
Blade::alias('Themes\Sixteen\View\Components\Page', 'pub_theme::page');
```

**Risultato:**
- `<x-page>` → Laravel risolve automaticamente a `pub_theme::page`
- `<x-pub_theme::page>` → Esplicito, stesso risultato
- `<x-sixteen::page>` → ❌ **ERROR**: Namespace non registrato

---

### Separation of Concerns

#### 1. Component PHP (Logica)

**File:** `laravel/Modules/Cms/app/View/Components/Page.php`

```php
<?php

namespace Modules\Cms\View\Components;

use Illuminate\View\Component;
use Modules\Cms\Datas\BlockData;
use Modules\Cms\Models\Page as PageModel;

final class Page extends Component
{
    public string $side;
    public string $slug;
    public DataCollection|array $blocks;
    public array $data = [];

    public function __construct(
        array $data = [],
        string $side = 'content',
        ?string $slug = null,
    ) {
        $this->side = $side;
        $this->data = $data;
        $this->slug = $slug ?? '';
        
        // LOGICA: Carica blocchi dal database/JSON
        $this->blocks = PageModel::getBlocksBySlug($this->slug, $this->side);
    }

    public function render(): ViewContract
    {
        // Renderizza la view CMS (NON Sixteen)
        return view('cms::components.page', [
            'blocks' => $this->blocks,
            'side' => $this->side,
            'slug' => $this->slug,
            'data' => $this->data,
        ]);
    }
}
```

**Responsabilità:**
- ✅ Caricare i blocchi dal database/JSON
- ✅ Risolvere la logica di business
- ✅ Passare i dati alla view

---

#### 2. Blade View (Template Minimale)

**File:** `laravel/Modules/Cms/resources/views/components/page.blade.php`

```blade
<?php

declare(strict_types=1);

?>
{{-- Page Component - CMS Module --}}
@props([
    'blocks' => [],
    'side' => 'content',
    'slug' => '',
    'page' => null,
    'data' => [],
])

@if(!empty($blocks))
    <div class="page-{{ $side }}-content" data-slug="{{ $slug }}" data-side="{{ $side }}">
        @foreach($blocks as $block)
            {{-- BlockData ha già gestito tutto: vista, dati, fallback --}}
            @include($block->view, array_merge($data, $block->data))
        @endforeach
    </div>
@endif
```

**Responsabilità:**
- ✅ Template minimale (DRY)
- ✅ Iterare sui blocchi
- ✅ Includere le view dei blocchi
- ❌ **NO logica di business** (è nel Component PHP)

---

#### 3. Theme View (Override Opzionale)

**File:** `laravel/Themes/Sixteen/resources/views/components/page.blade.php`

```blade
{{-- Page Component - Sixteen Theme --}}
{{-- Questo file è OPZIONALE: se esiste, override la view CMS --}}

@props([
    'side' => 'content',
    'slug' => '',
    'data' => []
])

@php
    // Load page from JSON config
    $configPath = base_path('config/local/fixcity/database/content/pages/'.$slug.'.json');
    $pageData = null;
    $blocks = [];

    if (file_exists($configPath)) {
        $pageData = json_decode(file_get_contents($configPath), true);
        $blocks = $pageData['blocks'] ?? $pageData['content_blocks']['it'] ?? [];
    }
@endphp

<div class="page-content {{ $side }}">
    @if($pageData && count($blocks) > 0)
        @foreach($blocks as $block)
            @if(isset($block['type']))
                @php
                    $blockType = $block['type'];
                    $blockData = $block['data'] ?? [];
                    $viewPath = $blockData['view'] ?? 'pub_theme::components.blocks.' . $blockType;
                @endphp

                @includeIf($viewPath, ['data' => $blockData])
            @endif
        @endforeach
    @endif
</div>
```

**Responsabilità:**
- ✅ Override del tema (opzionale)
- ✅ Caricare JSON config del tema
- ✅ Renderizzare blocchi specifici del tema

---

## 🔄 Flusso Rendering

### Scenario 1: `<x-page>` (CORRETTO)

```
1. Blade: <x-page side="content" :slug="tests.homepage" />
   ↓
2. Laravel: Cerca alias → 'pub_theme::page'
   ↓
3. ThemeServiceProvider: Blade::alias('...*Page', 'pub_theme::page')
   ↓
4. Component PHP: Modules/Cms/app/View/Components/Page.php
   ↓
5. Logica: PageModel::getBlocksBySlug('tests.homepage')
   ↓
6. View CMS: Modules/Cms/resources/views/components/page.blade.php
   ↓
7. Theme Override: Themes/Sixteen/resources/views/components/page.blade.php
   ↓
8. Render: @foreach($blocks as $block) → @includeIf($block['view'])
   ↓
9. Response: HTML completo
```

---

### Scenario 2: `<x-sixteen::page>` (SBAGLIATO)

```
1. Blade: <x-sixteen::page side="content" :slug="tests.homepage" />
   ↓
2. Laravel: Cerca namespace 'sixteen'
   ↓
3. ThemeServiceProvider: ❌ Namespace NON registrato
   ↓
4. EXCEPTION: View [sixteen::page] not found
```

---

## 📊 Confronto Architetture

### Architettura SBAGLIATA

```blade
{{-- ❌ Namespace fisso "sixteen" --}}
<x-sixteen::page side="content" :slug="$pageSlug" />

Problemi:
❌ Namespace "sixteen" non registrato
❌ Se cambi tema, devi modificare TUTTE le viste
❌ Violazione DRY
❌ Codice non portabile
❌ Exception: View not found
```

---

### Architettura CORRETTA

```blade
{{-- ✅ Namespace dinamico "pub_theme" --}}
<x-page side="content" :slug="$pageSlug" />

Vantaggi:
✅ Namespace "pub_theme" registrato
✅ Cambi tema → cambi solo config/xra.php
✅ DRY rispettato
✅ Codice portabile
✅ Laravel risolve automaticamente
```

---

## 🎯 Best Practices

### 1. Usa `<x-page>` (senza namespace)

```blade
{{-- ✅ CORRETTO: Laravel risolve automaticamente --}}
<x-page side="content" :slug="$pageSlug" :data="$data" />

{{-- ❌ SBAGLIATO: Namespace non necessario --}}
<x-pub_theme::page side="content" :slug="$pageSlug" :data="$data" />

{{-- ❌ ERRORE FATALE: Namespace non registrato --}}
<x-sixteen::page side="content" :slug="$pageSlug" :data="$data" />
```

**Perché:**
- ✅ Più corto (7 caratteri vs 20+)
- ✅ Più leggibile
- ✅ Laravel usa l'alias registrato
- ✅ Se cambi namespace, Laravel aggiorna automaticamente

---

### 2. Component PHP vs Blade View

| Aspetto | Component PHP | Blade View |
|---------|---------------|------------|
| **File** | `Modules/Cms/app/View/Components/Page.php` | `Modules/Cms/resources/views/components/page.blade.php` |
| **Responsabilità** | Logica di business | Template HTML |
| **Dati** | Carica da DB/JSON | Riceve props |
| **Namespace** | N/A | `pub_theme::page` |
| **Override** | NO | SI (tema) |

---

### 3. Theme Override

**Se il tema DEVE avere logica specifica:**

```blade
{{-- Themes/Sixteen/resources/views/components/page.blade.php --}}
{{-- Override della view CMS con logica theme-specific --}}

@props(['side' => 'content', 'slug' => '', 'data' => []])

@php
    // Logica theme-specific: carica JSON dal tema
    $configPath = base_path('config/local/fixcity/database/content/pages/'.$slug.'.json');
    // ... logica specifica del tema
@endphp

{{-- Template theme-specific --}}
<div class="page-content {{ $side }}">
    {{-- Renderizza blocchi --}}
</div>
```

**Importante:**
- ✅ La view del tema **override** quella del modulo
- ✅ La logica di business resta nel Component PHP (modulo)
- ✅ Il tema gestisce solo il rendering

---

## 🔧 Troubleshooting

### Errore: "View [sixteen::page] not found"

**Causa:**
- Stai usando `<x-sixteen::page>` invece di `<x-page>`

**Soluzione:**
```blade
{{-- ❌ SBAGLIATO --}}
<x-sixteen::page />

{{-- ✅ CORRETTO --}}
<x-page />
```

---

### Errore: "View [cms::components.page] not found"

**Causa:**
- Il componente CMS non è registrato o la view non esiste

**Soluzione:**
1. Verifica che `Modules/Cms/app/View/Components/Page.php` esista
2. Verifica che `Modules/Cms/resources/views/components/page.blade.php` esista
3. Clear cache: `php artisan view:clear && php artisan config:clear`

---

### Errore: "Page not found" o 404

**Causa:**
- Il file JSON della pagina non esiste o il path è sbagliato

**Soluzione:**
```php
// Nel componente blade del tema
// ❌ SBAGLIATO: double "laravel"
$configPath = base_path('laravel/config/local/fixcity/database/content/pages/'.$slug.'.json');

// ✅ CORRETTO: base_path() è già la directory laravel
$configPath = base_path('config/local/fixcity/database/content/pages/'.$slug.'.json');
```

---

## 📚 Documenti Correlati

- [Component Namespace](./component-namespace.md)
- [Layout Architecture](./layout-architecture.md)
- [Blocks Implementation](../BLOCKS_IMPLEMENTATION.md)
- [ThemeServiceProvider](../../app/Providers/ThemeServiceProvider.php)
- [Page Component PHP](../../../Modules/Cms/app/View/Components/Page.php)
- [Page Component Blade CMS](../../../Modules/Cms/resources/views/components/page.blade.php)
- [Page Component Blade Theme](../../resources/views/components/page.blade.php)

---

## 🎓 Lezioni Apprese

### 1. Namespace Dinamici

**Prima (SBAGLIATO):**
```blade
<x-sixteen::page />  <!-- Namespace fisso -->
```

**Dopo (CORRETTO):**
```blade
<x-page />  <!-- Namespace dinamico "pub_theme" -->
```

**Vantaggio:**
- Cambi tema → cambi solo `config/xra.php`
- Non devi modificare 100+ file blade

---

### 2. Separation of Concerns

| Layer | Responsabilità | File |
|-------|----------------|------|
| **Component PHP** | Logica di business | `Modules/Cms/app/View/Components/Page.php` |
| **Blade View (CMS)** | Template base | `Modules/Cms/resources/views/components/page.blade.php` |
| **Blade View (Theme)** | Override tema | `Themes/Sixteen/resources/views/components/page.blade.php` |

---

### 3. DRY + KISS

**DRY:**
- Namespace `pub_theme` registrato una volta
- Usato ovunque automaticamente

**KISS:**
- `<x-page>` più semplice di `<x-pub_theme::page>`
- Laravel fa il lavoro pesante

---

## ✅ Checklist Verifica

### Per Nuovi Componenti

```markdown
- [ ] Componente PHP in `Modules/*/app/View/Components/`
- [ ] Blade view in `Modules/*/resources/views/components/`
- [ ] Registrato in `ThemeServiceProvider` con alias
- [ ] Usa namespace `pub_theme` (NON `sixteen`)
- [ ] Documentato con link bidirezionali
```

### Per Nuove Viste

```markdown
- [ ] Usa `<x-page>` (NON `<x-sixteen::page>`)
- [ ] Template minimale (logica nel Component PHP)
- [ ] Props ben definite
- [ ] Override tema opzionale
```

---

**Data:** 2026-04-01  
**Versione:** 1.0  
**Stato:** ✅ Completato  

---

*"<x-page> NON <x-sixteen::page>"*  
*"Component PHP = logica, Blade View = template"*  
*"Namespace dinamico 'pub_theme', NON fisso 'sixteen'"*
