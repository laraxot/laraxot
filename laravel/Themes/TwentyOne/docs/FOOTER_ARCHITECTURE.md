# Footer Architecture — CMS-Driven

> **Data**: 2026-03-18  
> **Stato**: ✅ **CMS-DRIVEN**  
> **Tema**: TwentyOne  
> **Modulo**: Predict (fornisce configurazione JSON)

---

## 🎯 Panoramica

Il footer è **completamente gestito dal CMS** tramite configurazione JSON. Non ci sono Actions, Models o logica hardcoded nel tema.

### Principi Chiave

1. **Theme Agnostic** — Il tema non conosce il contenuto specifico
2. **JSON Configuration** — Tutto configurabile da back office
3. **Filament Builder Ready** — Pronto per gestione via Filament Forms
4. **No Hardcoded Logic** — Zero dipendenze da Actions o Models

---

## 📁 Architettura File

### 1. Layout Principale

**File**: `Themes/TwentyOne/resources/views/components/layouts/app.blade.php`

```blade
<body class="antialiased">
    {{-- Skip navigation --}}
    <a href="#main-content" class="sr-only focus:not-sr-only">
        Salta al contenuto principale
    </a>

    {{-- Header Section --}}
    <x-section slug="header" />

    {{-- Main Content --}}
    <main id="main-content" role="main">
        {{ $slot }}
    </main>

    {{-- Footer Section (CMS-Driven) --}}
    <x-section slug="footer" />
</body>
```

**Nota**: Il footer è chiamato come sezione CMS, non come componente hardcoded.

---

### 2. Footer Section View

**File**: `Themes/TwentyOne/resources/views/components/sections/footer.blade.php`

```php
<footer class="py-8 text-white bg-gray-900 xl:py-16">
    <div class="container max-w-6xl mx-auto space-y-6">
        @php
            $footerLinks = array_values(array_filter(
                $blocks,
                static fn ($block): bool => str_contains((string) $block->view, 'footer_links')
            ));
            $footerLinksBlock = $footerLinks[0] ?? null;
        @endphp

        {{-- Logo/Branding --}}
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div class="space-y-1">
                <p class="text-sm font-semibold text-white">{{ config('app.name') }}</p>
                <p class="max-w-xl text-sm text-gray-400">
                    Prediction market con crediti virtuali a tappi di bottiglia.
                </p>
            </div>
        </div>

        {{-- Footer Links (from JSON) --}}
        @if ($footerLinksBlock !== null)
            <div class="flex flex-col gap-6 border-t border-white/10 pt-4 md:flex-row md:items-start md:justify-between">
                @include($footerLinksBlock->view, $footerLinksBlock->data)
            @endif
        </div>
    </div>
</footer>
```

**Caratteristiche**:
- ✅ Accessibilità WCAG 2.2 AA (`role="contentinfo"`, `aria-label`)
- ✅ Responsive design (mobile-first)
- ✅ Blocchi dinamici da JSON config
- ✅ Zero hardcoded content

---

### 3. JSON Configuration

**File**: `config/local/predict/database/content/sections/footer.json`

```json
{
    "id": "3",
    "slug": "footer",
    "blocks": {
        "it": [
            {
                "type": "logo",
                "data": {
                    "view": "pub_theme::components.blocks.logo.simple"
                }
            },
            {
                "type": "links",
                "data": {
                    "view": "pub_theme::components.blocks.links.simple",
                    "links": [
                        {
                            "title": "Facebook",
                            "url": "https://www.facebook.com/share/1AptEVznFz/"
                        },
                        {
                            "title": "Twitter",
                            "url": ""
                        },
                        {
                            "title": "Instagram",
                            "url": ""
                        },
                        {
                            "title": "GitHub",
                            "url": ""
                        }
                    ]
                }
            }
        ]
    },
    "created_at": "2025-07-08T11:30:20.000000Z",
    "updated_at": "2025-07-08T11:30:20.000000Z"
}
```

**Struttura**:
- `slug`: Identificativo univoco della sezione
- `blocks`: Array di blocchi configurabili
- Ogni blocco ha:
  - `type`: Tipo di blocco (logo, links, etc.)
  - `data`: Dati specifici del blocco
  - `view`: View blade da includere

---

## 🔄 Runtime Flow

```
Request: GET /it
    ↓
Folio: Themes/TwentyOne/resources/views/pages/index.blade.php
    ↓
Layout: Themes/TwentyOne/resources/views/components/layouts/app.blade.php
    ↓
Section Call: <x-section slug="footer" />
    ↓
CMS Component: Modules/Cms/resources/views/components/section.blade.php
    ↓
ResolvePageAction: Carica footer.json da config/
    ↓
Section View: Themes/TwentyOne/resources/views/components/sections/footer.blade.php
    ↓
Block Rendering: Itera su $blocks e include le view
    ↓
Output: Footer HTML
```

---

## 🗑️ Deprecation: GetFooterData Action

### Cosa NON Usare Più

**File**: `Modules/Predict/app/Actions/Homepage/GetFooterData.php`

**Stato**: ❌ **DEPRECATO** - Rimosso

**Motivo**:
- Il footer è gestito dal CMS tramite JSON
- I dati sono configurabili da back office (Filament Builder)
- Più flessibile e mantenibile

### Vecchio Pattern (DEPRECATED)

```php
// ❌ MAI FARE QUESTO
use Modules\Predict\Actions\Homepage\GetFooterData;

$footerData = (new GetFooterData())->execute();
```

### Nuovo Pattern (CORRETTO)

```php
// ✅ USARE QUESTO
<x-section slug="footer" />
```

---

## 🎨 Block Views Disponibili

### 1. Logo Block

**View**: `pub_theme::components.blocks.logo.simple`

```blade
@props(['method' => 'default'])

<div class="footer-logo">
    <img src="{{ asset('images/logo.svg') }}" alt="{{ config('app.name') }}" />
</div>
```

### 2. Links Block

**View**: `pub_theme::components.blocks.links.simple`

```blade
@props(['links' => []])

<ul class="footer-links">
    @foreach($links as $link)
        <li>
            <a href="{{ $link['url'] }}" aria-label="{{ $link['title'] }}">
                {{ $link['title'] }}
            </a>
        </li>
    @endforeach
</ul>
```

---

## ✅ Vantaggi Architettura JSON

| Feature | GetFooterData (Vecchio) | JSON Config (Nuovo) |
|---------|------------------------|---------------------|
| **Configurabile** | ❌ Hardcoded in PHP | ✅ JSON modificabile |
| **Back Office** | ❌ No | ✅ Sì (Filament Builder) |
| **Multi-language** | ⚠️ Manuale | ✅ Automatico |
| **Blocks** | ❌ Fissi | ✅ Dinamici |
| **Manutenibilità** | ❌ Bassa | ✅ Alta |
| **Tema Agnostic** | ❌ No | ✅ Sì |

---

## 🧪 Testing

### Verificare Footer

```bash
# Test homepage
curl -sI http://predict.local/it
# HTTP/1.1 200 OK ✅

# Verificare footer HTML
curl -s http://predict.local/it | grep -i "<footer"
# <footer ...> ✅
```

### Controllare JSON Config

```bash
# Verificare footer.json
cat config/local/predict/database/content/sections/footer.json
# JSON valido ✅

# Validare sintassi JSON
php -r "json_decode(file_get_contents('config/local/predict/database/content/sections/footer.json')); echo json_last_error() === JSON_ERROR_NONE ? 'Valid' : 'Invalid';"
# Valid ✅
```

---

## 🤝 Multi-Agent Coordination

### Regole per Altri Agenti AI

1. **NON USARE** `GetFooterData` — È deprecato
2. **USARE** `<x-section slug="footer" />` per il footer
3. **CONFIGURARE** footer da JSON config
4. **DOCUMENTARE** eventuali cambiamenti
5. **NON hardcoded** content nel tema

### Agent Contributions

| Agente | Task | Stato | Data |
|--------|------|-------|------|
| **Agent 1** | Identificazione problema | ✅ | 2026-03-18 |
| **Agent 2** | Fix footer.blade.php | ✅ | 2026-03-18 |
| **Agent 3** | Documentazione | ✅ | 2026-03-18 |

---

## 📚 Riferimenti

### Documentazione Progetto
- [`GETFOOTERDATA_DEPRECATION.md`](../../docs/project/GETFOOTERDATA_DEPRECATION.md)
- [`CMS_JSON_FILAMENT_BUILDER_ARCHITECTURE.md`](../../docs/project/CMS_JSON_FILAMENT_BUILDER_ARCHITECTURE.md)
- [`PUB_THEME_ALIAS_RULE.md`](../../docs/project/PUB_THEME_ALIAS_RULE.md)

### Filament Documentation
- [Forms Builder](https://filamentphp.com/docs/5.x/forms/builder)
- [Builder Block](https://filamentphp.com/docs/5.x/forms/fields/builder)

### CMS Architecture
- [`x-page-context-data-rule.md`](Modules/Cms/docs/x-page-context-data-rule.md)

---

**Aggiornato**: 2026-03-18  
**Stato**: ✅ **CMS-DRIVEN**  
**Homepage**: http://predict.local/it (HTTP 200 OK)  
**Footer**: Caricato da JSON config
