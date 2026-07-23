# 🧘 Zen Architecture Philosophy

**Tema**: TwentyOne  
**Data**: 2026-03-24  
**Stato**: ✅ FOUNDATIONAL - Leggere PRIMA di modificare qualsiasi file

---

## 🔴 CRITICAL RULE: AGNOSTIC CONTAINER BLADE

### La Filosofia Zen

> **"Il contenitore è vuoto. Non ha forma. Prende la forma del contenuto."**

`[container0]/[slug0]/index.blade.php` è **AGNOSTICO**. Non sa cosa contiene.

Oggi mostra un **predict**.  
Domani mostrerà un **articolo blog**.  
Dopodomani un **profilo utente**.  
Poi un **prodotto e-commerce**.

**NON INQUINARE IL CONTENITORE CON LOGICA SPECIFICA!**

---

## ❌ COSA NON FARE (MAI!)

### 1. NO File Specifici per Tipo

```blade
❌ predicts/[slug].blade.php          ← SBAGLIATO!
❌ articles/[slug].blade.php          ← SBAGLIATO!
❌ profiles/[slug].blade.php          ← SBAGLIATO!
❌ products/[slug].blade.php          ← SBAGLIATO!
```

**Perché è sbagliato?**
- Duplicazione codice (100 tipi = 100 file)
- Manutenibilità zero
- Violazione DRY
- Impossibile fare evoluzione

### 2. NO Logica Specifica nel Container

```blade
❌ SBAGLIATO - [container0]/[slug0]/index.blade.php:
@if($container0 === 'predicts')
    @livewire(\Modules\Predict\Filament\Widgets\ViewPredictWidget::class)
@elseif($container0 === 'articles')
    @livewire(\Modules\Blog\Filament\Widgets\ViewArticleWidget::class)
@endif
```

```blade
❌ SBAGLIATO - [container0]/[slug0]/index.blade.php:
@php
    if ($container0 === 'predicts') {
        $predict = \Modules\Predict\Models\Predict::where('slug', $slug0)->first();
    }
@endphp
```

**Perché è sbagliato?**
- Il tema non conosce i moduli
- Accoppiamento forte (violazione Zen)
- Se aggiungo modulo, modifico tema (violazione Open/Closed)

### 3. NO Import di Moduli Specifici

```blade
❌ SBAGLIATO:
use Modules\Predict\Models\Predict;
use Modules\Blog\Models\Article;
use Modules\User\Models\Profile;
```

**Perché è sbagliato?**
- Il tema TwentyOne diventa dipendente da Predict, Blog, User
- Non puoi riusare TwentyOne in altro progetto senza Predict
- Violazione: **Theme Agnosticism**

---

## ✅ COSA FARE (SEMPRE!)

### 1. SI File Unico Agnostico

```blade
✅ CORRETTO - [container0]/[slug0]/index.blade.php:
<x-layouts.app :title="$pageTitle">
    <div>
        <x-page side="content" :slug="$this->pageSlug" :data="$this->data" />
    </div>
</x-layouts.app>
```

**Perché è corretto?**
- Un solo file per TUTTI i tipi
- Nessuna logica specifica
- Delega al CMS (`x-page`) e ai moduli (widgets)

### 2. SI Delega al CMS

```blade
✅ CORRETTO:
<x-page side="content" :slug="$this->pageSlug" :data="$this->data" />
```

Il CMS (tramite `x-page` component) decide quale block mostrare basandosi su:
- `container0` (predicts, articles, profiles, products)
- `slug0` (f1-world-champion-2026, my-profile, etc.)
- `data` (context per i blocks)

### 3. SI Delega ai Widgets dei Moduli

```blade
✅ CORRETTO - Il modulo fornisce widget agnostici:
Modules/Predict/Filament/Widgets/ViewPredictWidget.php
Modules/Blog/Filament/Widgets/ViewArticleWidget.php
Modules/User/Filament/Widgets/ViewProfileWidget.php
```

Il widget:
- È nel modulo (conosce il dominio)
- Estende `XotBaseWidget` (agnostico)
- Può essere riusato in qualsiasi tema

---

## 🎯 I 10 COMANDAMENTI ZEN

| # | Comandamento | Spiegazione |
|---|--------------|-------------|
| 1 | **Il Tema è Vuoto** | Non ha opinione, non ha logica |
| 2 | **Il Tema è Agnostico** | Non conosce moduli specifici |
| 3 | **Il Tema è Nudo** | NO styling hardcoded, solo layout |
| 4 | **Il Contenitore è Agnostico** | `[container0]` non sa cosa contiene |
| 5 | **La Logica è nei Moduli** | Moduli = dominio, Tema = vestito |
| 6 | **I Widgets sono Ponti** | Collegano Tema (agnostico) e Moduli (dominio) |
| 7 | **Il CMS è il Direttore** | Decide quali blocks mostrare |
| 8 | **NO Duplicazione** | 1 file = tutti i tipi |
| 9 | **NO Accoppiamento** | Tema ↔ Moduli = debole (widgets) |
| 10 | **SI Riusabilità** | Stesso tema, moduli diversi |

---

## 📊 ARCHITETTURA ZEN

### Layer Architecture

```
┌─────────────────────────────────────────────────────────┐
│                    REQUEST: /it/predicts/f1-2026        │
└─────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────┐
│  Folio Routing                                          │
│  [container0]/[slug0]/index.blade.php                   │
│  - Mount: container0='predicts', slug0='f1-2026'        │
│  - NO logica specifica                                  │
│  - NO import moduli                                     │
└─────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────┐
│  CMS Component (x-page)                                 │
│  - Legge JSON: config/local/predict/database/content/   │
│  - Risolve: container0='predicts' → predict.index       │
│  - Carica blocks da JSON                                │
└─────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────┐
│  Blocks (dal JSON)                                      │
│  - type: 'widget'                                       │
│  - view: 'pub_theme::filament.widgets.view-predict'     │
│  - widget: 'Modules\Predict\Filament\Widgets\...'       │
└─────────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────────┐
│  Module Widget (Predict)                                │
│  - ViewPredictWidget.php                                │
│  - Carica dati: Predict::where('slug', 'f1-2026')       │
│  - Renderizza UI con Filament                           │
└─────────────────────────────────────────────────────────┘
```

### File Structure

```
Themes/TwentyOne/resources/views/
├── components/
│   ├── layouts/
│   │   └── app.blade.php              ← Layout unico (bg-slate-950, etc.)
│   └── page.blade.php                 ← CMS resolver (agnostico)
└── pages/
    ├── index.blade.php                ← Homepage
    └── [container0]/
        ├── index.blade.php            ← List (agnostico)
        └── [slug0]/
            └── index.blade.php        ← Detail (agnostico)

Modules/Predict/
├── Filament/Widgets/
│   ├── PredictTableWidget.php         ← Per le liste
│   └── ViewPredictWidget.php          ← Per i dettagli
└── resources/views/
    └── filament/widgets/
        ├── predict-table.blade.php    ← View del widget (lista)
        └── view-predict.blade.php     ← View del widget (dettaglio)
```

---

## 🔄 COME AGGIUNGERE UN NUOVO TIPO

### Scenario: Aggiungiamo "Recipes" (Ricette)

**NON FARE**:
```blade
❌ recipes/[slug].blade.php            ← NO nuovo file!
❌ Modificare [container0]/[slug0]/index.blade.php ← NO if per recipes!
```

**FARE COSI**:

### Step 1: Crea Modulo Recipes (o usa esistente)

```bash
php artisan make:module Recipes
```

### Step 2: Crea Widget nel Modulo

```php
// Modules/Recipes/Filament/Widgets/ViewRecipeWidget.php
class ViewRecipeWidget extends XotBaseViewWidget
{
    public function view(): View
    {
        return view('recipes::filament.widgets.view-recipe', [
            'recipe' => Recipe::where('slug', $this->slug)->first()
        ]);
    }
}
```

### Step 3: Configura CMS JSON

```json
// config/local/recipes/database/content/pages/recipes.json
{
    "blocks": [
        {
            "type": "widget",
            "enabled": true,
            "order": 1,
            "data": {
                "view": "pub_theme::filament.widgets.view-recipe",
                "widget": "Modules\\Recipes\\Filament\\Widgets\\ViewRecipeWidget"
            }
        }
    ]
}
```

### Step 4: Il Tema NON SA NULLA

`[container0]/[slug0]/index.blade.php` rimane **INVARIATO**:

```blade
<x-layouts.app :title="$pageTitle">
    <div>
        <x-page side="content" :slug="$this->pageSlug" :data="$this->data" />
    </div>
</x-layouts.app>
```

**Funziona per magia** perché:
- `x-page` legge JSON
- JSON dice "usa widget ViewRecipeWidget"
- Widget è nel modulo Recipes
- Tema non ha mai visto Recipes!

---

## 🎯 PREDICT DETAIL PAGE - ESEMPIO PRATICO

### URL: `/it/predicts/f1-world-champion-2026`

### 1. Folio Routing

File: `[container0]/[slug0]/index.blade.php`

```php
name('container0.view');

new class extends Component {
    public string $container0 = '';
    public string $slug0 = '';

    public function mount(string $container0, string $slug0): void
    {
        $this->container0 = $container0;  // 'predicts'
        $this->slug0 = $slug0;            // 'f1-world-champion-2026'
    }
};
```

### 2. Page Title & Meta (Agnostici)

```php
$predict = \Modules\Predict\Models\Predict::query()
    ->where('slug', $slug0)
    ->first();

$pageTitle = $predict?->title ?? 'Mercato non trovato';
$pageMetaDescription = $predict?->description ?? 'Dettagli mercato';
```

**Nota**: Questa è l'**UNICA** eccezione accettabile per:
- Avere title/meta significativi per SEO
- Fare fallback se risorsa non esiste

**MA**: La logica di business (caricare dati, calcolare odds, etc.) va SEMPRE nei widgets!

### 3. View (Agnostica)

```blade
<x-layouts.app :title="$pageTitle" :meta-description="$pageMetaDescription">
    <div>
        @if($predict instanceof \Modules\Predict\Models\Predict)
            @livewire(\Modules\Predict\Filament\Widgets\ViewPredictWidget::class, [
                'predict' => $predict,
            ])
        @else
            {{-- Empty State --}}
            <div>
                <h2>Mercato non trovato</h2>
                <a href="/it/predicts">Torna alla lista</a>
            </div>
        @endif
    </div>
</x-layouts.app>
```

### 4. Widget (Modulo Predict)

File: `Modules/Predict/Filament/Widgets/ViewPredictWidget.php`

```php
class ViewPredictWidget extends XotBaseViewWidget
{
    public Predict $predict;

    public function view(): View
    {
        return view('predict::filament.widgets.view-predict', [
            'predict' => $this->predict,
            'outcomes' => $this->predict->outcomes,
            'orderBook' => $this->predict->orderBook,
            'priceHistory' => $this->predict->priceHistory,
        ]);
    }
}
```

### 5. Widget View (Modulo Predict)

File: `Modules/Predict/resources/views/filament/widgets/view-predict.blade.php`

```blade
<div class="predict-detail-widget">
    {{-- Order Book --}}
    <x-predict::order-book :predict="$predict" />

    {{-- Trading Form --}}
    <x-predict::trading-form :predict="$predict" />

    {{-- Price Chart --}}
    <x-predict::price-chart :predict="$predict" />

    {{-- Market Stats --}}
    <x-predict::market-stats :predict="$predict" />
</div>
```

**Tutta la logica è QUI**, non nel tema!

---

## 🚨 RED FLAGS 🚩

### Se vedi questi pattern, FERMATI!

| Red Flag | Problema | Soluzione |
|----------|----------|-----------|
| `predicts/[slug].blade.php` | File specifico per tipo | Usa `[container0]/[slug0]/index.blade.php` |
| `if ($container0 === 'predicts')` | Logica specifica nel container | Delega a widget del modulo |
| `use Modules\Predict\Models\Predict;` | Import modulo nel tema | Sposta logica in widget |
| `bg-slate-950` hardcoded | Styling nel container | Metti in `app.blade.php` o CSS |
| `getMarketData()` method | Logica dominio nel tema | Sposta in Action/Widget |

---

## ✅ CHECKLIST PRE-COMMIT

Prima di commitare modifiche a `[container0]/[slug0]/index.blade.php`:

- [ ] ✅ Il file è agnostico? (funziona per predicts, articles, profiles, products?)
- [ ] ✅ NO import di moduli specifici? (tranne per title/meta)
- [ ] ✅ NO if/switch per tipo di contenuto?
- [ ] ✅ NO styling hardcoded? (solo `<div>` wrapper)
- [ ] ✅ Delega a `x-page` o widgets?
- [ ] ✅ La logica è nei widgets del modulo?
- [ ] ✅ Posso riusare questo tema con moduli diversi?

Se **TUTTE** le risposte sono **SI** → ✅ COMMIT

Se **ALMENO UNA** è **NO** → ❌ REFACTOR PRIMA DI COMMIT

---

## 📚 RIFERIMENTI

### Documentazione Correlata

| File | Descrizione |
|------|-------------|
| `docs/ZEN_NAKED_PAGE_PHILOSOPHY.md` | NO styling hardcoded |
| `docs/CONTAINER_AGNOSTICISM.md` | Container non sa cosa contiene |
| `docs/WIDGET_BRIDGE_PATTERN.md` | Widgets come ponte Tema↔Moduli |
| `docs/CMS_JSON_ARCHITECTURE.md` | CMS guida i blocks |

### Moduli

| Modulo | Widget Detail | Widget List |
|--------|---------------|-------------|
| Predict | `ViewPredictWidget` | `PredictTableWidget` |
| Blog | `ViewArticleWidget` | `ArticleTableWidget` |
| User | `ViewProfileWidget` | `ProfileTableWidget` |
| E-commerce | `ViewProductWidget` | `ProductTableWidget` |

### URL Pattern

| URL | Container | Widget |
|-----|-----------|--------|
| `/it/predicts/f1-2026` | `predicts` | `ViewPredictWidget` |
| `/it/articles/my-post` | `articles` | `ViewArticleWidget` |
| `/it/profiles/mario` | `profiles` | `ViewProfileWidget` |
| `/it/products/laptop` | `products` | `ViewProductWidget` |

**Stesso file**: `[container0]/[slug0]/index.blade.php`  
**Widget diverso**: Deciso da CMS JSON

---

## 🧘 MEDITAZIONE ZEN

> **"Il tema perfetto è come l'acqua:  
> non ha forma propria,  
> ma prende la forma del contenitore.  
> Non oppone resistenza,  
> ma scorre dove il CMS la guida.  
> Non conosce i moduli,  
> ma li ospita tutti.  
> È vuoto, e per questo è infinito."**

---

**Ultimo Aggiornamento**: 2026-03-24  
**Review**: Dopo ogni modifica a `[container0]/[slug0]/index.blade.php`  
**Enforcer**: AI Agents (reject PR che violano Zen)
