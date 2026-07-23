---
title: "Header Nav Dynamic Links — Sixteen Theme"
type: concept
sources: ["../../Modules/Cms/docs/wiki/concepts/header-nav-block-architecture.md"]
confidence: high
created: 2026-05-04
updated: 2026-05-04
tags: [header, navigation, dynamic-links, design-comuni, six-teen-theme]
related:
  - ../../Modules/Cms/docs/wiki/concepts/header-nav-block-architecture.md
  - header-ssot.md
  - header-design-comuni-parity-mapping.md
---

# Header Nav Dynamic Links — Sixteen Theme

**Tema:** Sixteen  
**Story:** 8-107  
**Data:** 2026-05-04

---

## Filosofia: Perché i link NON sono hardcoded

L'header è il "volto istituzionale" del Comune. Ogni Comune ha bisogni diversi:
- Un Comune vuole "Polizia Locale", un altro "Sicurezza"
- Le URL cambiano (`/servizi` vs `/servizi-online`)
- La lingua cambia (`Servizi` vs `Services`)

**Se i link sono hardcoded in Blade → il tema non è riutilizzabile.**

Il Blade `v1.blade.php` è un **orchestratore**, non una sorgente dati.  
La sorgente è **`header.json`** (SSoT — Single Source of Truth).

---

## Zen: La catena del dato

```
<x-section slug="header" />
    └── Section Component (Modules/Cms/app/View/Components/Section.php)
          └── SectionModel::getBlocksBySlug('header')
                └── SushiToJsons (legge JSON → SQLite in-memory)
                      └── TenantService::filePath('database/content/sections/')
                            └── header.json
                                  └── HasBlocks::getBlocks()
                                        └── BlockData[] → $blocks
```

**Il Blade riceve:** `$blocks` (array di `BlockData`)  
**Il Blade deve:** iterare sui dati, non inventare HTML statico.

---

## Come v1.blade.php accede ai dati

### 1. Lettura del JSON (già implementato in v1.blade.php righe 47-58)

```blade
@php
    $headerNavConfig = [];
    $headerNavJsonPath = \Modules\Tenant\Services\TenantService::filePath('database/content/sections/header.json');
    if (is_string($headerNavJsonPath) && file_exists($headerNavJsonPath)) {
        $headerNavConfig = \Illuminate\Support\Facades\File::json($headerNavJsonPath);
    }
    $headerNavAllItems = $headerNavConfig['sections']['primary_nav']['items'] ?? [];
    $headerNavTopicsUrl = $headerNavConfig['sections']['primary_nav']['topics_url'] ?? '/it/argomenti';
    $headerNavItems = array_values(array_filter($headerNavAllItems, fn ($i) => ($i['nav_group'] ?? 'primary') === 'primary' && ($i['enabled'] ?? true) && ($i['visible'] ?? true)));
    $headerNavSecondary = array_values(array_filter($headerNavAllItems, fn ($i) => ($i['nav_group'] ?? 'primary') === 'secondary' && ($i['enabled'] ?? true) && ($i['visible'] ?? true)));
    usort($headerNavItems, fn ($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));
    usort($headerNavSecondary, fn ($a, $b) => ($a['order'] ?? 0) <=> ($b['order'] ?? 0));

    $headerNavItemIsActive = static function (array $item): bool {
        $patterns = $item['active_patterns'] ?? [];
        if (is_array($patterns) && $patterns !== []) {
            foreach ($patterns as $p) {
                if (! is_string($p) || $p === '') {
                    continue;
                }
                $normalized = ltrim($p, '/');
                if ($normalized !== '' && request()->is($normalized)) {
                    return true;
                }
            }
            return false;
        }
        $u = (string) ($item['url'] ?? '');
        $path = $u !== '' ? ltrim((string) parse_url($u, PHP_URL_PATH), '/') : '';
        return $path !== '' && (request()->is($path) || request()->is($path.'/*'));
    };
@endphp
```

### 2. Rendering dinamico (già implementato in v1.blade.php righe 256-266)

```blade
<nav aria-label="{{ __('pub_theme::header.center.nav.primary_aria.label') }}">
    <ul class="navbar-nav" data-element="main-navigation">
        @foreach($headerNavItems as $item)
            <li class="nav-item">
                <a class="nav-link{{ $headerNavItemIsActive($item) ? ' active' : '' }}"
                   href="{{ $item['url'] ?? '#' }}"
                   @if(! empty($item['data_element'])) data-element="{{ $item['data_element'] }}" @endif>
                    <span>{{ $item['label'] ?? '' }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</nav>
```

---

## La Religione: `request()->is()` e `active_patterns`

### Il problema
Vogliamo che "Servizi" sia **attivo** su:
- `/it/servizi`
- `/it/tests/segnalazione-crea` (perché è un servizio)
- `/it/servizio/123`

### La soluzione: `active_patterns` nel JSON

```json
{
  "label": "Servizi",
  "url": "/it/servizi",
  "data_element": "all-services",
  "active_patterns": [
    "it/servizi*",
    "it/segnalazione*",
    "it/servizio*"
  ]
}
```

### Nel Blade (funzione `$headerNavItemIsActive`)

```php
$patterns = $item['active_patterns'] ?? [];
// Se ci sono pattern, usa loro
if (is_array($patterns) && $patterns !== []) {
    foreach ($patterns as $p) {
        $normalized = ltrim($p, '/');  // ← IMPORTANTE: toglie lo slash iniziale!
        if (request()->is($normalized)) {
            return true;
        }
    }
}
```

**Regola critica:** `request()->is()` NON vuole lo slash iniziale!  
✅ `it/servizi*`  
❌ `/it/servizi*`

---

## Visione: Amministrazione Filament

I link non sono solo nel JSON. Sono **editabili** via Filament:

1. Vai su `/admin/cms/sections`
2. Modifica la sezione "Header"
3. Trova il blocco "Header Nav"
4. Modifica il Repeater `items`:
   - `label` (es. "Servizi")
   - `url` (es. "/it/servizi")
   - `active_patterns` (Repeater annidato: un pattern per riga)

Quando salvi, **SushiToJsons** riscrive automaticamente `header.json`.

---

## Guardrail per il Dev

1. **MAI hardcodare link in Blade** — usa `$headerNavItems`
2. **MAI usare `->label()` nei Filament fields** — usa `->translateLabel()`
3. **MAI dimenticare `ltrim($p, '/')`** nei pattern
4. **Dopo modifica manuale del JSON** → `php artisan optimize:clear`
5. **Il Blade è SSoT-consumer** — non producer di link

---

## Vedi anche

- [[header-nav-block-architecture]] — architettura lato Cms
- [[header-ssot]] — header come Single Source of Truth
- [[header-design-comuni-parity-mapping]] — mapping classi Design Comuni
- Story 8-107: `_bmad-output/implementation-artifacts/8-107-header-nav-items-from-json-filament-builder.md`
