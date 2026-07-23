# TwentyOne Theme — JSON-Based Architecture

> **Data**: 2026-03-18  
> **Tema**: TwentyOne (agnostico, JSON-based)  
> **Architettura**: Folio + Volt + CMS JSON Blocks  
> **Back Office**: Filament Builder ready

---

## 🎯 Filosofia del Tema

### 1. **Agnosticismo**
Il tema **NON** conosce:
- Il tipo di contenuto (predict, article, event, etc.)
- La struttura specifica dei dati
- I blocchi hardcoded

Il tema **RICEVE** solo:
- `slug` → Identificativo pagina
- `side` → Layout (content, sidebar, full)
- `data` → Dati contestuali (opzionale)

### 2. **JSON-Driven**
Tutti i contenuti sono in file JSON:
```
config/local/predict/database/content/pages/
├── home.json              ← Homepage
├── predicts.json          ← Predict list
├── predicts.view.json     ← Predict detail
└── ...
```

### 3. **Traduzioni e fallback**

**Pattern OBBLIGATORIO**: `__('<namespace>::<contesto>.<collezione>.<key>.<tipologia>')`

Esempi corretti:
```blade
{{-- CORRETTO: con tipologia finale --}}
{{ __('pub_theme::home.cta.explore.button') }}
{{ __('pub_theme::home.cta.learn.button') }}
{{ __('pub_theme::home.labels.credits.label') }}
{{ __('pub_theme::home.hero.title.title') }}

{{-- ERRATO: manca tipologia --}}
{{ __('predict::home.hero.cta_learn') }}   <!-- ERRORE! Manca .label -->
{{ __('predict::home.hero.cta_learn.label') }}   <!-- CORRETTO -->
```

**Tipologie richieste**: `.label`, `.button`, `.title`, `.subtitle`, `.aria`, `.placeholder`, `.tooltip`, `.error`, `.success`, `.warning`, `.info`

- **Fallback**: Se il JSON non ha valore per un campo, usare la traduzione del modulo dominio
- **Tema agnostico**: Le view usano `pub_theme::` e traduzioni dal modulo (es. `predict::`)
- **Namespace**: Usa `pub_theme::` per il tema pubblico, `predict::` per il modulo Predict

### 4. **Filament Builder Ready**
I JSON sono strutturati per essere gestiti da back office:
```php
Builder::make('content_blocks')
    ->blocks([
        Builder\Block::make('hero')->schema([...]),
        Builder\Block::make('featured_markets')->schema([...]),
    ])
```

---

## 📁 Struttura File

```
Themes/TwentyOne/
├── resources/views/
│   ├── pages/
│   │   └── index.blade.php          ← Homepage (Folio)
│   │   └── [container0]/
│   │       └── [slug0]/
│   │           └── index.blade.php  ← Generic page (Folio)
│   ├── components/
│   │   ├── layouts/
│   │   │   └── app.blade.php        ← Layout principale
│   │   └── seo-meta.blade.php       ← SEO + JSON-LD
│   └── components/blocks/
│       ├── hero/
│       │   ├── fomo-explosive.blade.php
│       │   ├── kalshi-inspired.blade.php
│       │   └── simple.blade.php
│       ├── trust/
│       │   └── bar.blade.php
│       ├── markets/
│       │   ├── featured.blade.php
│       │   ├── featured_grid.blade.php
│       │   └── trending_explosive.blade.php
│       └── ...
└── docs/
    ├── HOMEPAGE_IMPROVEMENT_PLAN.md
    ├── HOMEPAGE_SEO.md
    ├── HOMEPAGE_SPRINT1_SUMMARY.md
    └── JSON_ARCHITECTURE.md         ← Questo file
```

---

## 🔄 Runtime Flow

### Homepage (/it)

```
1. HTTP GET /it
   ↓
2. Folio Route: Themes/TwentyOne/resources/views/pages/index.blade.php
   ↓
3. Volt Component: @volt('home')
   ↓
4. CMS Component: <x-page side="content" slug="home" />
   ↓
5. ResolvePageAction:
   - Legge: config/local/predict/database/content/pages/home.json
   - Estrae: content_blocks.it[]
   ↓
6. Render Loop:
   @foreach($content_blocks as $block)
       @include($block['view'], $block['data'])
   @endforeach
   ↓
7. HTML Output
```

### Generic Page (/it/predicts/{slug})

```
1. HTTP GET /it/predicts/my-predict
   ↓
2. Folio Route: Themes/TwentyOne/resources/views/pages/[container0]/[slug0]/index.blade.php
   ↓
3. Volt Component: mount($container0, $slug0)
   ↓
4. ResolvePageAction:
   - Legge: config/local/predict/database/content/pages/predicts.view.json
   - Estrae: content_blocks + predict data
   ↓
5. Render Blocks + Predict Components
   ↓
6. HTML Output
```

---

## 🎨 Block Views

### Hero Block (fomo-explosive.blade.php)

```blade
@props([
    'title' => 'Prevedi il Futuro',
    'subtitle' => 'La nuova piattaforma. Sii tra i primi.',
    'background' => 'gradient',
    'cta_primary' => null,
    'cta_secondary' => null,
])

<section class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 py-20 text-white md:py-32">
    {{-- Background Animations --}}
    <div class="absolute inset-0 overflow-hidden">
        <div class="animate-blob absolute -right-40 -top-40 h-80 w-80 rounded-full bg-purple-500 opacity-20 blur-3xl mix-blend-multiply filter"></div>
        <div class="animate-blob animation-delay-2000 absolute -bottom-40 -left-40 h-80 w-80 rounded-full bg-emerald-500 opacity-20 blur-3xl mix-blend-multiply filter"></div>
        <div class="animate-blob animation-delay-4000 absolute left-1/2 top-1/2 h-80 w-80 -translate-x-1/2 -translate-y-1/2 rounded-full bg-blue-500 opacity-20 blur-3xl mix-blend-multiply filter"></div>
    </div>

    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">{{ $title }}</h1>
            <p class="text-lg md:text-xl text-indigo-200 max-w-2xl mb-8">{{ $subtitle }}</p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                @if($cta_primary)
                <a href="{{ $cta_primary['url'] }}" class="...">
                    {{ $cta_primary['text'] }}
                </a>
                @endif
            </div>
        </div>
    </div>
</section>
```

## Compatibilita' View CMS

I file JSON CMS pubblici devono continuare a usare il namespace `pub_theme::`.
Quando un blocco front office viene implementato nel modulo di dominio, il tema pubblico deve esporre un file bridge stabile che preservi il contratto JSON.

Esempio corrente homepage:

```json
{
  "type": "featured_markets",
  "data": {
    "view": "pub_theme::components.blocks.markets.featured"
  }
}
```

Il file bridge corrispondente e':

```blade
{{-- Themes/TwentyOne/resources/views/components/blocks/markets/featured.blade.php --}}
@include('predict::components.blocks.home.featured-markets', get_defined_vars())
```

Questo evita regressioni quando il rendering reale vive nel modulo `Predict`, ma il CMS continua a puntare al tema tramite alias `pub_theme::`.

### Trust Bar (bar.blade.php)

```blade
@props(['sources' => []])

<section class="bg-slate-900/50 border-y border-slate-800 py-6" aria-label="As featured in">
    <div class="max-w-7xl mx-auto px-4">
        <ul class="flex flex-wrap items-center justify-center gap-6">
            @foreach($sources as $source)
            <li>
                <span class="text-2xl">{{ $source['logo'] }}</span>
                <span class="font-bold text-sm">{{ $source['name'] }}</span>
            </li>
            @endforeach
        </ul>
    </div>
</section>
```

---

## 🛠️ Filament Builder Schema

### Form Configuration

```php
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

Builder::make('content_blocks')
    ->translatable()
    ->blocks([
        // Hero Block
        Builder\Block::make('hero')
            ->schema([
                TextInput::make('title')
                    ->translatable()
                    ->required(),
                Textarea::make('subtitle')
                    ->translatable(),
                Select::make('background')
                    ->options([
                        'gradient' => 'Gradient',
                        'image' => 'Image',
                        'video' => 'Video',
                    ])
                    ->default('gradient'),
                TextInput::make('cta_primary_text')
                    ->translatable(),
                TextInput::make('cta_primary_url'),
            ])
            ->columns(2),
        
        // Trust Bar Block
        Builder\Block::make('trust_bar')
            ->schema([
                Repeater::make('sources')
                    ->schema([
                        TextInput::make('name'),
                        TextInput::make('logo'),
                    ])
                    ->columns(2),
            ]),
        
        // Featured Markets Block
        Builder\Block::make('featured_markets')
            ->schema([
                TextInput::make('title')
                    ->translatable(),
                TextInput::make('limit')
                    ->numeric()
                    ->default(6),
                Select::make('filter')
                    ->options([
                        'hot' => 'Hot',
                        'trending' => 'Trending',
                        'recent' => 'Recent',
                    ]),
            ]),
    ])
    ->reorderable()
    ->collapsible()
    ->itemLabel(fn (array $state): ?string => $state['type'] ?? null);
```

---

## 📊 JSON Example (home.json)

```json
{
    "id": "home",
    "title": {
        "it": "Home",
        "en": "Home"
    },
    "slug": "home",
    "content_blocks": {
        "it": [
            {
                "type": "hero",
                "enabled": true,
                "order": 1,
                "data": {
                    "title": {
                        "it": "Prevedi il Futuro, Guadagna Crediti",
                        "en": "Predict the Future, Earn Credits"
                    },
                    "subtitle": {
                        "it": "La nuova piattaforma di prediction market. Sii tra i primi.",
                        "en": "Join the largest prediction market community"
                    },
                    "background": "gradient",
                    "cta_primary": {
                        "text": {"it": "Esplora Mercati", "en": "Explore Markets"},
                        "url": "/predicts"
                    },
                    "cta_secondary": {
                        "text": {"it": "Come Funziona", "en": "How It Works"},
                        "url": "#how-it-works"
                    }
                },
                "view": "pub_theme::components.blocks.hero.fomo-explosive"
            },
            {
                "type": "trust_bar",
                "enabled": true,
                "order": 2,
                "data": {
                    "sources": [
                        {"name": "CNN", "logo": "📺"},
                        {"name": "Reuters", "logo": "🌍"},
                        {"name": "Bloomberg", "logo": "📊"}
                    ]
                },
                "view": "pub_theme::components.blocks.trust.bar"
            },
            {
                "type": "featured_markets",
                "enabled": true,
                "order": 3,
                "data": {
                    "title": {
                        "it": "Mercati in Evidenza",
                        "en": "Featured Markets"
                    },
                    "limit": 6,
                    "filter": "hot"
                },
                "view": "predict::components.blocks.markets.featured-grid"
            }
        ]
    },
    "meta": {
        "seo_title": {"it": "Home - Base Predict", "en": "Home - Base Predict"},
        "seo_description": {"it": "Prediction market platform", "en": "Prediction market platform"},
        "updated_at": "2026-03-18T00:00:00Z"
    }
}
```

---

## 🤝 Multi-Agent Coordination

### Regole per Altri Agenti AI

1. **LEGGI** questo file PRIMA di modificare il tema
2. **NON hardcoded** — Usa sempre JSON configuration
3. **AGGIUNGI** nuovi block types al builder schema
4. **DOCUMENTA** in questa sezione

### Agent Contributions

| Agente | Task | Stato | Block Type |
|--------|------|-------|------------|
| **Agent 1** | Hero block | ✅ | `hero/fomo-explosive` |
| **Agent 2** | Trust bar | ✅ | `trust/bar` |
| **Agent 3** | Featured markets | ✅ | `markets/featured-grid` |
| **Agent 4** | Filament schema | 🔄 | In corso |
| **Agent 5+** | Altri blocks | ⏳ | Da definire |

---

## 📚 Riferimenti

### Documentazione Progetto
- [`CMS_JSON_FILAMENT_BUILDER_ARCHITECTURE.md`](../docs/project/CMS_JSON_FILAMENT_BUILDER_ARCHITECTURE.md)
- [`MULTI_AGENT_HOMEPAGE_COORDINATION.md`](../docs/project/MULTI_AGENT_HOMEPAGE_COORDINATION.md)
- [`website-checklist.md`](../docs/project/website-checklist.md)

### Filament Documentation
- [Forms Builder](https://filamentphp.com/docs/5.x/forms/builder)
- [Builder Block](https://filamentphp.com/docs/5.x/forms/fields/builder)
- [Repeater](https://filamentphp.com/docs/5.x/forms/fields/repeater)

### Traduzioni
- [translation-prototype-rule.md](../../../docs/project/translation-prototype-rule.md)
- [TRANSLATION_STRUCTURE_RULES.md](../../../docs/project/TRANSLATION_STRUCTURE_RULES.md)

### Theme Architecture
- [`FOLIO_VOLT_ARCHITECTURE.md`](../laravel/docs/FOLIO_VOLT_ARCHITECTURE.md)
- [`page-component-context-data.md`](../laravel/Modules/Cms/docs/page-component-context-data.md)

---

**Aggiornato**: 2026-03-18  
**Stato**: ✅ Documentazione completa  
**Tema**: ✅ Agnostico, JSON-based  
**Filament Builder**: 🔄 Schema pronto
