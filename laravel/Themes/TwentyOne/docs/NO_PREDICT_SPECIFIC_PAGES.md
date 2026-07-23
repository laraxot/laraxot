# 🚫 NO Pagine Predict-Specific nel Theme

**Data**: 2026-03-20  
**Stato**: ✅ OBBLIGATORIO  
**Priorità**: CRITICAL  
**Enforcement**: Pre-commit Hook + Code Review

---

## 🔴 REGOLA D'ORO

### MAI creare pagine specifiche per moduli nel theme

```
❌ VIETATO
Themes/TwentyOne/resources/views/pages/predicts/index.blade.php
Themes/TwentyOne/resources/views/pages/events/index.blade.php
Themes/TwentyOne/resources/views/pages/blog/index.blade.php

✅ CORRETTO
Themes/TwentyOne/resources/views/pages/[container0]/index.blade.php
```

In più, è vietato creare una variante “hardcoded” per la lingua dentro la struttura views:
`Themes/TwentyOne/resources/views/pages/it/predicts/index.blade.php`

---

## 🧘 FILOSOFIA: Il Theme è il Vestito

> **"Il theme non sa, non giudica, non possiede. Indossa e basta."**

### Separazione dei Concern

| Componente | Responsabilità | Esempio |
|------------|----------------|---------|
| **Theme** | Layout, CSS, Routing | `pages/[container0]/index.blade.php` |
| **Module** | Logica, Dati, Widgets | `Filament/Widgets/*Widget.php` |
| **CMS** | Configurazione blocchi | `config/local/predict/database/content/pages/*.json` |

---

## 📊 Architecture Flow

### Request: `/it/predicts`

```
1. Request → Laravel Folio
   ↓
2. Matches: pages/[container0]/index.blade.php (GENERIC)
   ↓
3. Volt Component mounts
   ↓
4. ResolvePageAction (agnostic)
   ↓
5. <x-page side="content" slug="predicts.index" />
   ↓
6. CMS loads predicts.index.json
   ↓
7. Blocks render (PredictTableWidget, etc.)
   ↓
8. Response
```

---

## ✅ Come Funziona il Container Generic

```php
<?php
// Themes/TwentyOne/resources/views/pages/[container0]/index.blade.php

use function Laravel\Folio\middleware;
use function Laravel\Folio\name;
use Livewire\Volt\Component;
use Modules\Cms\Http\Middleware\PageSlugMiddleware;

name('container0.index');
middleware(PageSlugMiddleware::class);

new class extends Component {
    public string $container0 = '';
    public array $data = [];

    public function mount(string $container0): void
    {
        $this->container0 = $container0;
        $this->pageSlug = $container0 . '.index';
        $this->data = ['container0' => $container0];
    }
};
?>

<x-layouts.app :title="$pageTitle">
    @volt('container0.index')
    <div class="min-h-screen">
        {{-- GENERIC: Works for ANY container --}}
        <x-page side="content" :slug="$this->pageSlug" :data="$this->data" />
    </div>
    @endvolt
</x-layouts.app>
```

### Cosa Fa Questo File

1. ✅ Riceve `container0` (può essere ANYTHING)
2. ✅ Chiama `ResolvePageAction` (agnostico)
3. ✅ Imposta `pageSlug` = `{container0}.index`
4. ✅ Renderizza `<x-page>` con CMS blocks

### Cosa NON Fa

1. ❌ Non sa che esiste Predict
2. ❌ Non carica dati specifici
3. ❌ Non ha logica di dominio
4. ❌ Non ha `if ($container0 === 'predicts')`

---

## 🎯 Dove Va la Logica Predict

### 1. Filament Widgets

```php
// Modules/Predict/Filament/Widgets/PredictTableWidget.php
class PredictTableWidget extends XotBaseTableWidget {
    public function table(Table $table): Table {
        return $table
            ->model(Predict::class)
            ->query(fn() => Predict::query()->visible())
            ->searchable()
            ->filters([...])
            ->paginated([12, 24, 48]);
    }
}
```

### 2. Actions

```php
// Modules/Predict/Actions/GetPredictListAction.php
class GetPredictListAction {
    use QueueableAction;
    
    public function execute(): array {
        return [
            'predicts' => Predict::visible()->paginate(12),
        ];
    }
}
```

### 3. CMS Blocks

```blade
{{-- Modules/Predict/resources/views/components/blocks/predict-list.blade.php --}}
@props(['record'])

@php
    $data = app(GetPredictListAction::class)->execute();
@endphp

<div class="predict-list">
    @foreach($data['predicts'] as $predict)
        <x-predict.card :predict="$predict" />
    @endforeach
</div>
```

### 4. CMS JSON

```json
// config/local/predict/database/content/pages/predicts.index.json
{
    "slug": "predicts.index",
    "content_blocks": {
        "it": [
            {
                "type": "widget",
                "data": {
                    "widget": "Modules\\Predict\\Filament\\Widgets\\PredictTableWidget"
                }
            }
        ]
    }
}
```

---

## ❌ Errori da Evitare

### Errore 1: Pagina Specifica

```blade
{{-- ❌ NO --}}
Themes/TwentyOne/resources/views/pages/predicts/index.blade.php

{{-- ✅ YES --}}
Themes/TwentyOne/resources/views/pages/[container0]/index.blade.php
```

### Errore 2: Foreach in Blade

```blade
{{-- ❌ NO --}}
@foreach($predicts as $predict)
    <div>{{ $predict->title }}</div>
@endforeach

{{-- ✅ YES --}}
<x-page side="content" slug="predicts.index" />
```

### Errore 3: Logica nel Theme

```php
{{-- ❌ NO --}}
@php
    use Modules\Predict\Models\Predict;
    $predicts = Predict::paginate(12);
@endphp

{{-- ✅ YES --}}
@php
    // Theme does NOTHING
@endphp
```

---

## ✅ Checklist Code Review

### Prima di Commitare

- [ ] ✅ File è in `[container0]/index.blade.php`?
- [ ] ✅ NON in `predicts/index.blade.php`?
- [ ] ✅ NO `use Modules\Predict` nel theme?
- [ ] ✅ NO `if ($container0 === 'predicts')`?
- [ ] ✅ Usa `<x-page>` component?
- [ ] ✅ CMS JSON config esiste?
- [ ] ✅ Filament Widget esiste?

---

## 🔗 Riferimenti

### Documentazione
- `docs/project/ARCHITECTURE_ZEN.md` - Architettura base
- `docs/project/CONTAINER_BLADE_CORRECT_ARCHITECTURE.md` - Container blade
- `Modules/Predict/docs/NO_PREDICT_SPECIFIC_PAGES_IN_THEME.md` - Predict docs
- `Modules/Predict/docs/GENERIC_PAGE_ARCHITECTURE.md` - Generic pages

### GitHub
- Issue #047 - Architecture Violation
- Discussion #006 - Container Blade Philosophy

---

**Ultimo Aggiornamento**: 2026-03-20  
**Stato**: ✅ OBBLIGATORIO  
**Violazioni**: ZERO TOLLERANZA
